<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_key_id', 'technician_id'];

    public function vehicleKey()
    {
        return $this->belongsTo(VehicleKey::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
