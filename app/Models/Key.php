<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function vehicles()
    {
        return $this->hasManyThrough(
            Vehicle::class,
            VehicleKey::class
        );
    }
}
