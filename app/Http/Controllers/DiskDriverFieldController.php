<?php

namespace App\Http\Controllers;

use App\Models\Disk;
use App\Models\DriverField;
use App\Models\DiskDriverField;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class DiskDriverFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Disk  $disk
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Disk $disk)
    {
        $input = $request->all();

        $fields = $disk->driver->driverFields;
        $validation = $this->makeValidationRules($fields , $disk);

        Validator::make($input, $validation['rules'], $validation['messages'])->validateWithBag('updateDiskField');

        //dd('all validated');
        foreach ($input as $index => $value) {
            $field_id = Str::replace('field_', '', $index);
            $driverField = $fields->where('id', $field_id)->first();

            if ($driverField->is_file) {
                if ($request->hasFile($index)) {
                    $file = $request->file($index);
                    $newFileName = 'file-' . $field_id . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('/'.($disk->id),$newFileName,'local');

                    DiskDriverField::updateOrCreate(
                        ['driver_field_id' => $field_id, 'disk_id' => $disk->id],
                        ['value' => $path]
                    );
                }
            } else {   
                DiskDriverField::updateOrCreate(
                    ['driver_field_id' => $field_id, 'disk_id' => $disk->id],
                    ['value' => $driverField->encrypt ? \Illuminate\Support\Facades\Crypt::encryptString($value) : $value]
                );
            } 
        }

        return back(303);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DiskDriverField  $diskDriverField
     * @return \Illuminate\Http\Response
     */
    public function show(DiskDriverField $diskDriverField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DiskDriverField  $diskDriverField
     * @return \Illuminate\Http\Response
     */
    public function edit(DiskDriverField $diskDriverField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DiskDriverField  $diskDriverField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DiskDriverField $diskDriverField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DiskDriverField  $diskDriverField
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiskDriverField $diskDriverField)
    {
        //
    }

    /**
     * Create the validation rules and error messages for the disk driver fields
     * 
     * @param  Illuminate\Support\Collection  $driverFields
     * @param  \App\Models\Disk  $disk
     * @return Array
     */
    public function makeValidationRules(Collection $driverFields, Disk $disk)
    {
        $return = [
            'rules' => [],
            'messages' => [],
        ];

        foreach($driverFields as $field)
        {
            $rules = [];

            if ($field->required && !$field->is_file) {
                $rules[] = 'required';
                $return['messages']['field_' . $field->id . '.required'] = $field->display_name . ' is required.';
            } else if ($field->required && $field->is_file) {
                $rules[] = Rule::requiredIf($disk->diskDriverFields()->where('driver_field_id', $field->id)->doesntExist());
                $rules[] = 'nullable';
                $return['messages']['field_' . $field->id . '.required'] = $field->display_name . ' is required.';
            } else {
                $rules[] = 'nullable';
            }

            if ($field->is_file) {
                $rules[] = 'file';
                $return['messages']['field_' . $field->id . '.file'] = $field->display_name . ' must be a file.';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
                $return['messages']['field_' . $field->id . '.string'] = $field->display_name . ' must be a string.';
                $return['messages']['field_' . $field->id . '.max'] = $field->display_name . ' cannot be longer than 255 characters.';
            }
            // rules / messages for file uploads

            $return['rules']['field_' . $field->id] = $rules;
        }

        return $return;
    }
}
