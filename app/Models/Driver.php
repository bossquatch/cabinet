<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function disks()
    {
        return $this->hasMany(Disk::class);
    }

    public function driverFields()
    {
        return $this->hasMany(DriverField::class);
    }
}
