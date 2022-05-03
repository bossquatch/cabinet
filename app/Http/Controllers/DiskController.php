<?php

namespace App\Http\Controllers;

use App\Models\Disk;
use App\Models\Driver;
use App\Models\DiskDriverField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class DiskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Disks/Index', [
            'disks' => $this->allowedDisks()->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                    'team' => $disk->team->name,
                    'edit_url' => route('disk.show', $disk),
                ];
            }),
            'templates' => $this->allowedDisks(true)->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                    'team' => $disk->team->name,
                    'edit_url' => route('disk.show', $disk),
                ];
            }),
            //'create_url' => URL::route('users.create'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Disks/Create', [
            'drivers' => Driver::all()->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'display_name' => $driver->display_name,
                ];
            }),
            'backup_disks' => $this->allowedDisks()->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                ];
            }),
            'templates' => $this->allowedDisks(true)->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                ];
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $team_ids = auth()->user()->allTeams()->map(function ($team) { return ['id' => $team->id]; })->pluck('id');

        Validator::make($input, [
            'name' => ['required', 'string', 'max:60'],
            'driver_id' => ['required', 'integer'],
            'team_id' => ['required', Rule::in($team_ids)],
            'backup_id' => ['nullable', 'integer'],
            'template_id' => ['nullable', 'integer'],
            'is_template' => ['required', 'boolean'],
            'private' => ['required', 'boolean'],
            'encode_files' => ['required', 'boolean'],
        ])->validateWithBag('createDisk');

        $disk = Disk::create($input);

        return redirect()->route('disk.show', ['disk' => $disk->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Disk  $disk
     * @return \Illuminate\Http\Response
     */
    public function show(Disk $disk)
    {
        return Inertia::render('Disks/Show', [
            'disk' => $disk->load('driver', 'template', 'template.diskDriverFields'),
            'driverFields' => $disk->driver->driverFields->map(function ($field) use ($disk) {
                $driverField = $disk->diskDriverFields()->where('driver_field_id', $field->id)->first();
                return [
                    'id' => $field->id,
                    'name' => $field->display_name,
                    'encrypt' => $field->encrypt ? '1' : '0',
                    'is_file' => $field->is_file ? '1' : '0',
                    'is_filled' => ($field->is_file && $driverField) ? '1' : '0',
                    //'required' => $field->required ? '1' : '0',
                    'value' => $driverField ? ($field->encrypt ? \Illuminate\Support\Facades\Crypt::decryptString($driverField->value) : $driverField->value) : null,
                ];
            }),
            'backup_disks' => Disk::where('is_template', false)->where('id', '!=', $disk->id)->whereIn('team_id', auth()->user()->allTeams()->map(function ($team) { return ['id' => $team->id]; })->pluck('id'))->get()->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                ];
            }),
            'templates' => Disk::where('is_template', true)->where('id', '!=', $disk->id)->whereIn('team_id', auth()->user()->allTeams()->map(function ($team) { return ['id' => $team->id]; })->pluck('id'))->get()->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                ];
            }),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Disk  $disk
     * @return \Illuminate\Http\Response
     */
    public function edit(Disk $disk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Disk  $disk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disk $disk)
    {
        $input = $request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:60'],
            'private' => ['required', 'boolean'],
            'backup_id' => ['nullable', 'integer'],
            'template_id' => ['nullable', 'integer'],
            'is_template' => ['required', 'boolean'],
        ])->validateWithBag('updateDiskName');
        
        $disk->update($input);

        return back(303);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Disk  $disk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disk $disk)
    {
        //
    }

    /**
     * Display a listing of files within the disk
     * 
     * @param \App\Models\Disk $disk
     * @return \Illuminate\Http\Response
     */
    public function files(Disk $disk)
    {
        $files_temp = $disk->files;
        $files = [];

        foreach ($files_temp as $filename) {
            $files[] = [
                'name' => $filename,
                'last_modified' => $disk->lastModifiedDate($filename),
                'size' => $disk->getSize($filename),
            ];
        }

        return Inertia::render('Disks/Files', [
            'disk' => $disk,
            'files' => $files,
        ]);
    }

    /**
     * Download a file off of a disk
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Request $request, Disk $disk)
    {
        $file = $request->input('file');

        return $disk->download($file);
    }

    /**
     * Delete a file off of a disk
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function deleteFile(Request $request, Disk $disk)
    {
        $file = $request->input('file');

        try {
            $disk->deleteFile($file);
            return back(303)->with('success_message', 'File successfully deleted');
        } catch (\Exception $e) {
            abort(500, 'File deletion ran into an issue.  Please contact a system administrator.');
        }
    }

    private function allowedDisks($is_template = false)
    {
        $user = auth()->user();

        return $user->is_admin ?
            Disk::where('is_template', $is_template)->get() :
            Disk::where('is_template', $is_template)->whereIn('team_id', $user->allTeams()->map(function ($team) { return ['id' => $team->id]; })->pluck('id'))->get();
    }
}
