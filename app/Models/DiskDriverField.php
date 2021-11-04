<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskDriverField extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function disk()
    {
        return $this->belongsTo(Disk::class);
    }

    public function driverField()
    {
        return $this->belongsTo(DriverField::class);
    }
}
