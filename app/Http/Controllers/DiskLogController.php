<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiskLog;

class DiskLogController extends Controller
{
    public function index()
    {
        dd(request()->user()->id);
        return Inertia::render('Dashboard', [
            'disks' => $this->allowedDisks()->map(function ($disk) {
                return [
                    'name' => $disk->name,
                    'files_url' => route('disk.files', $disk),
                ];
            }),
        ]);
    }
}
