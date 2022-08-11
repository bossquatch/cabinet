<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\DiskLog;
use App\Models\Disk;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DiskLogController extends Controller
{
    public function index()
    {
        $logs = DiskLog::with(['disk', 'user', 'token'])->orderBy('created_at', 'desc')->paginate(25);

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
        $logs = DiskLog::with(['disk', 'user', 'token'])->where('disk_id', $disk->id)->orderBy('created_at', 'desc')->paginate(25);

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
        $logs = DiskLog::with(['disk', 'user', 'token'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(25);

        $logs->getCollection()->transform(function($log) {
            return $this->logTransform($log);
        });
        
        return Inertia::render('Logs/Index', [
            'logs' => $logs,
            'title' => $user->name
            //'types' => config('logging.disk.types'),
        ]);
    }

    public function analytics()
    {
        /** Analytics List
         *      [*] Activity amount over last 30 days
         *      [*] Activity percentage day of week
         *      [*] Counts per activity type
         *      [*] Disks with most activity
         *      [*] Users with most activity
         */
        $logs = DiskLog::get();

        $recent_activity = $this->recentActivity($logs);
        $dow_activity = $this->dayOfWeekActivity($logs);
        $activity_type = $this->typesOfActivity($logs);
        $disk_activity = $this->diskActivity($logs);
        $user_activity = $this->userActivity($logs);
        $token_activity = $this->tokenActivity($logs);

        return Inertia::render('Logs/Analytics', [
            'recent_activity' => $recent_activity,
            'dow_activity' => $dow_activity,
            'activity_type' => $activity_type,
            'disk_activity' => $disk_activity,
            'user_activity' => $user_activity,
            'token_activity' => $token_activity,
        ]);
    }

    private function recentActivity($logs)
    {
        $return = [];

        $period = CarbonPeriod::create(Carbon::now()->subDays(29), '1 day', Carbon::now());
        foreach($period as $date) {
            $date_logs = $logs->filter(function($value, $key) use ($date) {
                return Carbon::parse($value->created_at)->isSameDay($date);
            });
            $return['labels'][] = $date->format('m-d');
            $return['data'][] = $date_logs->count();
        }

        return $return;
    }

    private function dayOfWeekActivity($logs)
    {
        $return = [];

        $return = [
            'labels' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'data' => [0, 0, 0, 0, 0, 0, 0],
            'colors' => ['blue', 'red', 'green', 'yellow', 'purple', 'orange', 'cyan']
        ];
        foreach($logs as $log) {
            $date = Carbon::parse($log->created_at);
            $return['data'][$date->dayOfWeek]++;
        }

        return $return;
    }

    private function typesOfActivity($logs)
    {
        $return = [];
        $types = collect(config('logging.disk.types'));

        $activity_type_raw = $logs->groupBy('type');
        foreach($activity_type_raw as $typename => $type_logs) {
            $return['labels'][] = strtoupper($typename);
            $return['data'][] = $type_logs->count();
            $return['colors'][] = $types->where('name', $typename)->first()['color_class'];
        }

        return $return;
    }

    private function diskActivity($logs)
    {
        $return = [];
        $disks = Disk::get();

        $disk_activity_raw = $logs->groupBy('disk_id');
        foreach($disk_activity_raw as $disk_id => $disk_logs) {
            $name = $disks->where('id', $disk_id)->first()->name;
            $return['labels'][] = $name;
            $return['data'][] = $disk_logs->count();
        }

        return $return;
    }

    private function userActivity($logs)
    {
        $return = [];
        $users = User::get();

        $user_activity_raw = $logs->groupBy('user_id');
        foreach($user_activity_raw as $user_id => $user_logs) {
            $name = $users->where('id', $user_id)->first()->name;
            $return['labels'][] = $name;
            $return['data'][] = $user_logs->count();
        }

        return $return;
    }

    private function tokenActivity($logs)
    {
        $return = [];
        $tokens = PersonalAccessToken::get();

        $token_activity_raw = $logs->groupBy('personal_access_token_id');
        foreach($token_activity_raw as $token_id => $token_logs) {
            if ($token_id) {
                $name = $tokens->where('id', $token_id)->first()->name;
                $return['labels'][] = $name;
                $return['data'][] = $token_logs->count();
            }
        }

        return $return;
    }

    private function logTransform($log)
    {
        $types = collect(config('logging.disk.types'));
        $type_details = $types->where('name', $log->type)->first();
        return [
            'id' => $log->id,
            'file' => $log->file,
            'disk' => $log->disk->name,
            'user' => $log->token ? $log->token->name . ' (' . $log->user->name . ')' : $log->user->name,
            'diskid' => $log->disk->id,
            'userid' => $log->user->id,
            'type' => $log->type,
            'color' => $type_details ? $type_details['color_class'] : 'gray',
            'icon' => $type_details ? $type_details['vue_icon'] : 'X',
            'datetime' => Carbon::parse($log->created_at)->format('H:i m-d-Y'),
        ];
    }
}
