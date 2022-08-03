<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleKey extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'key_id'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function key()
    {
        return $this->belongsTo(Key::class);
    }
}
