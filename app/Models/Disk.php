<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use League\Flysystem\Util\MimeType;
use Pear\Crypt\GPG;
use Carbon\Carbon;

class Disk extends Model
{
    use HasFactory;

    protected $guarded = [];
    private $activeDisk = null;
    private $homeFolderSaved = null;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function template()
    {
        return $this->belongsTo(Disk::class, 'template_id');
    }

    public function templatedDisks()
    {
        return $this->hasMany(Disk::class, 'template_id');
    }

    public function diskDriverFields()
    {
        return $this->hasMany(DiskDriverField::class);
    }

    public function diskLogs()
    {
        return $this->hasMany(DiskLog::class);
    }

    public function backupDisk()
    {
        return $this->belongsTo(Disk::class, 'backup_id');
    }

    public function relientDisks()
    {
        return $this->hasMany(Disk::class, 'backup_id');
    }

    public function upload(String $fileContents, String $filename, bool $backup = false)
    {
        $fileSize = $this->fileSize($fileContents);

        if ($this->backupDisk()->exists()) {
            $this->backupDisk->upload($fileContents, $filename, true);
        }
        if (config('crypt.encrypt_uploads') && $this->encode_files) {
            // encrypt file contents
            $fileContents = $this->encode($fileContents);

            // change file name
            $filename = $filename . '.' . config('crypt.encode_ext');
        }

        if ($this->homeFolder != '/') {
            $filename = $this->homeFolder . '/' . $filename;
        }

        // upload
        $this->build?->put($filename, $fileContents);
        $this->fileLog($backup ? 'backup' : 'upload', $this->decodedFilename($filename), $fileSize);
        return true;
    }

    public function download(String $file)
    {
        $contents = null;
        if ($this->build?->exists($file)) {
            if ($this->encoded($file)) {
                $contents = $this->decode($this->build?->get($file));
            } else {
                $contents = $this->build?->get($file);
            }
        } else {
            abort(500, 'Unable to pull file from disk.');
        }

        $decodedFilename = $this->decodedFilename($file);

        $this->fileLog('download', $decodedFilename, $this->fileSize($contents));

        return response()->attachment($contents, $decodedFilename, MimeType::detectByFileExtension($decodedFilename));
    }

    public function deleteFile(String $file)
    {
        if ($this->build?->exists($file)) {
            $this->build?->delete($file);

            $this->fileLog('delete', $this->decodedFilename($file));
        } else {
            abort(500, 'Unable to pull file from disk.');
        }

        return true;
    }

    public function lastModifiedDate(String $file)
    {
        return Carbon::parse($this->build?->lastModified($file))?->format('H:i m-d-Y');
    }

    public function getSize(String $file)
    {
        return $this->formatBytes($this->build?->size($file));
    }

    public function getBuildAttribute()
    {
        if (!$this->activeDisk) {
            $this->activeDisk = $this->build();
        }
        return $this->activeDisk;
    }

    public function getDirectoriesAttribute()
    {
        return $this->build?->directories($this->homeFolder);
    }

    public function getFilesAttribute()
    {
        return $this->build?->files($this->homeFolder);
    }

    public function getHomeFolderAttribute()
    {
        if (!$this->homeFolderSaved) {
            $field = $this->driver->driverFields->where('name', 'folder')->first();
            if ($field) {
                $filledField = $this->diskDriverFields->where('driver_field_id', $field->id)->first();
                if ($filledField) {
                    $this->homeFolderSaved = $filledField->value;
                } else {
                    $this->homeFolderSaved = '/';
                }
            } else {
                $this->homeFolderSaved = '/';
            }
        }
        return $this->homeFolderSaved;
    }

    private function build()
    {
        try {
            return Storage::build($this->buildOptions());
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                abort(500, 'Disk unable to connect to filesystem.  Please validate the configuration options.');
            }
        }
    }

    private function buildOptions()
    {
        $template = $this->template;
        $options = [
            'driver' => $this->driver->name,
        ];
        foreach ($this->driver->driverFields as $field) {
            // If template exists and contains that field, use it; otherwise use own field
            $filledField = $template?->diskDriverFields->where('driver_field_id', $field->id)->first() ?? $this->diskDriverFields->where('driver_field_id', $field->id)->first();
            if ($filledField) {
                if ($field->is_file) {
                    $options[$field->name] = storage_path('app') . '/' . $filledField->value;
                } else if ($field->encrypt) {
                    $options[$field->name] = \Illuminate\Support\Facades\Crypt::decryptString($filledField->value);
                } else {
                    $options[$field->name] = $filledField->value;
                }
            } else {
                $options[$field->name] = null;
            }
        }
        return $options;
    }

    private function decodedFilename($filename)
    {
        $filename = str_replace($this->homeFolder . '/', '', $filename);
        $fileArr = explode('.', $filename);
        if (($key = array_search(config('crypt.encode_ext'), $fileArr)) !== false) {
            unset($fileArr[$key]);
        }
        return implode('.', $fileArr);
    }

    private function encoded($filename)
    {
        return $this->getExtension($filename) == config('crypt.encode_ext');
    }

    private function getExtension($filename)
    {
        return Arr::last(explode('.', $filename));
    }

    private function decode($contents)
    {
        $gpg = new \gnupg();

        $priKey = file_get_contents(config('crypt.decrypt_key'));
    
        $info = $gpg->import($priKey);
        $encKey = $gpg->adddecryptkey($info['fingerprint'],config('crypt.passphrase'));
        return $gpg->decrypt($contents);
    }

    private function encode($contents)
    {
        $gpg = new \gnupg();

        $pubKey = file_get_contents(config('crypt.encrypt_key'));
    
        $info = $gpg->import($pubKey);
        $encKey = $gpg->addencryptkey($info['fingerprint']);
        return $gpg->encrypt($contents);
    }

    private function fileLog(String $type, String $filename, String $size = null)
    {
        if(!DiskLog::verifyType($type)) {
            throw new \Exception("Ineligible log type used.  Please contact a system administrator.");
        }

        $this->diskLogs()->create([
            'user_id' => request()->user()->id,
            'personal_access_token_id' => request()->route()->getPrefix() == 'api' ? request()->user()->currentAccessToken()->id : null,
            'file' => $size ? ($filename . ' (' . $size . ')') : $filename,
            'type' => $type,
        ]);
    }

    private function fileSize(String $content)
    {
        return $this->formatBytes(\strlen($content));
    }

    private function formatBytes($size, $precision = 2) { 
        $base = \log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');   
    
        return \round(\pow(1024, $base - \floor($base)), $precision) .' '. $suffixes[\floor($base)] . 'B';
    }
}
