<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Drivers/Index', [
            'drivers' => Driver::all()->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'display_name' => $driver->display_name,
                    'name' => $driver->name,
                    'edit_url' => route('driver.show', $driver),
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
        return Inertia::render('Drivers/Create');
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

        Validator::make($input, [
            'name' => ['required', 'string', Rule::in($this->allowedDriverTypes())],
            'display_name' => ['required', 'string', 'max:60'],
        ])->validateWithBag('createDriver');

        $driver = Driver::create($input);

        return redirect()->route('driver.show', ['driver' => $driver->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        return Inertia::render('Drivers/Show', [
            'driver' => $driver->load('driverFields'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $input = $request->all();

        Validator::make($input, [
            'name' => ['required', 'string', Rule::in($this->allowedDriverTypes())],
            'display_name' => ['required', 'string', 'max:60'],
        ])->validateWithBag('updateDriverName');
        
        $driver->update($input);

        return back(303);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        //
    }

    private function allowedDriverTypes()
    {
        return [
            's3',
            'sftp',
        ];
    }
}
