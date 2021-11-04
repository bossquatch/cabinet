<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function diskDriverFields()
    {
        return $this->hasMany(DiskDriverField::class);
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
        $options = [
            'driver' => $this->driver->name,
        ];
        foreach ($this->driver->driverFields as $field) {
            $filledField = $this->diskDriverFields->where('driver_field_id', $field->id)->first();
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
}
