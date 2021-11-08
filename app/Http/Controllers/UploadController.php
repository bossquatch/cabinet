<?php

namespace App\Http\Controllers;

use App\Models\Disk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UploadController extends Controller
{
    public function index()
    {
        return Inertia::render('Upload/Index', [
            'disks' => Disk::whereIn('team_id', auth()->user()->allTeams()->map(function ($team) { return ['id' => $team->id]; })->pluck('id'))
                            ->orWhere('private', false)
                            ->get()
                            ->map(function ($disk) {
                                return [
                                    'id' => $disk->id,
                                    'name' => $disk->name,
                                ];
                            }),
        ]);
    }

    public function upload(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'file' => ['required', 'file'],
            'disk_id' => ['required', 'integer'],
        ])->validateWithBag('uploadFile');

        $disk = Disk::findOrFail($input['disk_id']);

        try {
            $disk->upload($request->file('file')->get(), $request->file('file')->getClientOriginalName());
            return back(303);
        } catch (\Exception $e) {
            abort(500, 'File upload ran into an issue.  Please contact a system administrator.');
        }
    }
}
