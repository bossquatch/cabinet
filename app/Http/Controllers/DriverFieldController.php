<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DriverFieldController extends Controller
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
     * @param  \Illuminate\Http\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Driver $driver)
    {
        $input = $request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:60', Rule::notIn(['driver'])],
            'display_name' => ['required', 'string', 'max:80'],
            'encrypt' => ['required', 'boolean'],
            'is_file' => ['required', 'boolean'],
            'required' => ['required', 'boolean'],
        ])->validateWithBag('updateDriverName');
        
        $driver->driverFields()->create($input);

        return back(303);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Driver  $driver
     * @param  \App\Models\DriverField  $driverField
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver, DriverField $driverField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Driver  $driver
     * @param  \App\Models\DriverField  $driverField
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver, DriverField $driverField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Driver  $driver
     * @param  \App\Models\DriverField  $driverField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver, DriverField $driverField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Driver  $driver
     * @param  \App\Models\DriverField  $driverField
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver, DriverField $driverField)
    {
        //
    }
}
