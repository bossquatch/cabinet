<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\DiskLog;
use Carbon\Carbon;

class DiskLogController extends Controller
{
    public function index()
    {
        $types = collect(config('logging.disk.types'));

        $logs = DiskLog::with(['disk', 'user'])->orderBy('created_at', 'desc')->paginate(25);

        $logs->getCollection()->transform(function($log) use ($types) {
            $type_details = $types->where('name', $log->type)->first();
            return [
                'id' => $log->id,
                'file' => $log->file,
                'disk' => $log->disk->name,
                'user' => $log->user->name,
                'type' => $log->type,
                'color' => $type_details ? $type_details['color_class'] : 'gray',
                'icon' => $type_details ? $type_details['vue_icon'] : 'X',
                'datetime' => Carbon::parse($log->created_at)->format('H:i m-d-Y'),
            ];
        });
        
        return Inertia::render('Logs/Index', [
            'logs' => $logs,
            //'types' => config('logging.disk.types'),
        ]);
    }
}
