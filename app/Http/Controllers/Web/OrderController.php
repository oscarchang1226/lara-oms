<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Key;
use App\Models\Order;
use App\Models\Technician;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index() 
    {
        return Inertia::render('Orders', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'orderApi' => route('order.api')
        ]);
    }

    public function create()
    {
        return $this->edit(new Order);
    }

    public function edit(Order $order)
    {
        $vehicleOptions = Vehicle::with('manufacturer')
            ->get()
            ->map(function ($vehicle) {
                return [
                    'label' => "{$vehicle->manufacturer->name} - $vehicle->name ($vehicle->vin)",
                    'value' => $vehicle->id
                ];
            })
            ->sortBy('label')
            ->values()
            ->toArray();
        $keyOptions = Key::orderBy('name')
            ->get()
            ->map(function($key) {
                return ['label' => $key->name, 'value' => $key->id];
            })
            ->toArray();
        $technicianOptions = Technician::get()
            ->map(function ($technician) {
                return ['label' => $technician->full_name, 'value' => $technician->id];
            })
            ->sortBy('label')
            ->values()
            ->toArray();
        return Inertia::render('OrderForm', [
            'apiUrl' => $order->id ? route('order.api.update', ['order' => $order]) : route('order.api.store'),
            'method' => $order->id ? 'PATCH' : 'POST',
            'vehicleOptions' => $vehicleOptions,
            'keyOptions' => $keyOptions,
            'technicianOptions' => $technicianOptions,
            'formValues' => [
                'id' => $order->id, 
                'technician' => $order->technician_id,
                'key' => $order->key_id,
                'vehicle' => $order->vehicle_id]
        ]);
    }
}
