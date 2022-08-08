<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    public function getFullNameAttribute()
    {
        return "$this->last_name, $this->first_name";
    }
}
