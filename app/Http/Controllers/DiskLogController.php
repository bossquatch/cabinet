<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\DiskLog;
use App\Models\Disk;
use App\Models\User;
use Carbon\Carbon;

class DiskLogController extends Controller
{
    public function index()
    {
        $logs = DiskLog::with(['disk', 'user'])->orderBy('created_at', 'desc')->paginate(25);

        $logs->getCollection()->transform(function($log) {
            return $this->logTransform($log);
        });
        
        return Inertia::render('Logs/Index', [
            'logs' => $logs,
            //'types' => config('logging.disk.types'),
        ]);
    }

    public function disk(Disk $disk)
    {
        $logs = DiskLog::with(['disk', 'user'])->where('disk_id', $disk->id)->orderBy('created_at', 'desc')->paginate(25);

        $logs->getCollection()->transform(function($log) {
            return $this->logTransform($log);
        });
        
        return Inertia::render('Logs/Index', [
            'logs' => $logs,
            'title' => $disk->name
            //'types' => config('logging.disk.types'),
        ]);
    }

    public function user(User $user)
    {
        $logs = DiskLog::with(['disk', 'user'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(25);

        $logs->getCollection()->transform(function($log) {
            return $this->logTransform($log);
        });
        
        return Inertia::render('Logs/Index', [
            'logs' => $logs,
            'title' => $user->name
            //'types' => config('logging.disk.types'),
        ]);
    }

    private function logTransform($log)
    {
        $types = collect(config('logging.disk.types'));
        $type_details = $types->where('name', $log->type)->first();
        return [
            'id' => $log->id,
            'file' => $log->file,
            'disk' => $log->disk->name,
            'user' => $log->user->name,
            'diskid' => $log->disk->id,
            'userid' => $log->user->id,
            'type' => $log->type,
            'color' => $type_details ? $type_details['color_class'] : 'gray',
            'icon' => $type_details ? $type_details['vue_icon'] : 'X',
            'datetime' => Carbon::parse($log->created_at)->format('H:i m-d-Y'),
        ];
    }
}
