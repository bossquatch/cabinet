<?php

namespace App\Models;

use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskLog extends Model
{
    use HasFactory;

    public $guarded = [];

    public function disk()
    {
        return $this->belongsTo(Disk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function token()
    {
        return $this->belongsTo(Sanctum::$personalAccessTokenModel, 'personal_access_token_id');
    }

    public static function verifyType(String $type)
    {
        return in_array(strtolower($type), self::availableTypes());
    }

    public static function availableTypes()
    {
        return collect(config('logging.disk.types'))->pluck('name')->all();
    }
}
