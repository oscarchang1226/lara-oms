<?php

namespace App\Models;

use App\Http\Controllers\Api\OrderController as ApiController;
use App\Http\Controllers\Web\OrderController as WebController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Order extends Model
{
    use HasFactory;

    public static function addApiRoutes()
    {
        $controller = ApiController::class;
        $path = '/orders';
        $name = 'order.api';

        Route::get($path, "$controller@index")->name($name);
        Route::post($path, "$controller@store")->name("$name.store");
        Route::patch("$path/{order}", "$controller@update")->name("$name.update");
        Route::delete("$path/{order}", "$controller@destroy")->name("$name.delete");
    }

    public static function addWebRoutes()
    {
        $controller = WebController::class;
        Route::get('/', "$controller@index")->name('orders');
        Route::get('/orders/new', "$controller@create")->name('order.new');
        Route::get('/orders/{order}', "$controller@edit")->name('order.edit');
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
