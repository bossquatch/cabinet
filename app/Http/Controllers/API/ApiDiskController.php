<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disk;

class ApiDiskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return [
            'disks' => $this->allowedDisks($request)->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                    'team' => $disk->team->name,
                ];
            }),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $disk = Disk::findOrFail($id);

        return [
            'disk' => [
                'id' => $disk->id,
                'name' => $disk->name,
                'team' => $disk->team->name,
            ],
            'files' => $disk->files,
        ];
    }

    /**
     * Download a selected file
     * 
     * @param  int  $id 
     */
    public function download(Request $request, $id)
    {
        $disk = Disk::findOrFail($id);

        $file = $request->input('file');

        if (!$file) {
            abort(400, 'File parameter is required.');
        }

        return $disk->download($file);
    }

    /**
     * Upload a file
     * 
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $id)
    {
        $disk = Disk::findOrFail($id);

        $file = $request->file('file');

        if (!$file) {
            abort(400, 'File parameter is required.');
        }

        try {
            if ($disk->upload($request->file('file')->get(), $request->file('file')->getClientOriginalName())) {
                return [
                    'status' => 'uploaded'
                ];
            } else {
                abort(500, 'File upload ran into an issue.  Please contact a system administrator.');    
            }
        } catch (\Exception $e) {
            abort(500, 'File upload ran into an issue.  Please contact a system administrator.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function allowedDisks(Request $request)
    {
        $user = $request->user();

        return $user->is_admin ?
            Disk::all() :
            Disk::whereIn('team_id', $user->allTeams()->map(function ($team) { return ['id' => $team->id]; })->pluck('id'))->get();
    }
}
