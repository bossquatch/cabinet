<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Disk;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'disks' => $this->allowedDisks()->map(function ($disk) {
                return [
                    'name' => $disk->name,
                    'files_url' => route('disk.files', $disk),
                ];
            }),
        ]);
    }

    private function allowedDisks()
    {
        $user = auth()->user();
        
        return Disk::where('is_template', false)->whereIn('team_id', $user->allTeams()->map(function ($team) { return ['id' => $team->id]; })->pluck('id'))->get();
    }
}
