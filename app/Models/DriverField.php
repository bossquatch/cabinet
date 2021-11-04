<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverField extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function diskDriverFields()
    {
        return $this->hasMany(DiskDriverField::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
