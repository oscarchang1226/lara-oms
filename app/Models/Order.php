<?php

namespace App\Models;

use App\Http\Controllers\OrderController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Order extends Model
{
    use HasFactory;

    public static function addApiRoutes()
    {
        $controller = OrderController::class;
        $path = '/orders';
        $name = 'order.api';

        Route::get($path, "$controller@index")->name($name);
        Route::post($path, "$controller@store")->name("$name.store");
        Route::patch("$path/{order}", "$controller@update")->name("$name.update");
        Route::delete("$path/{order}", "$controller@destroy")->name("$name.delete");
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function key()
    {
        return $this->belongsTo(Key::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
