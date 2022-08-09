<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with(['vehicle.manufacturer', 'key', 'technician']);
        if (request()->filled('vehicle')) {
            $term = '%' . request('vehicle') . '%';
            $orders->whereHas('vehicle', function ($q) use ($term) {
                $q->where(function ($q) use ($term) {
                    $q->whereHas('manufacturer', function ($q) use ($term) {
                        $q->whereRaw('name like ?', [$term]);
                    })
                    ->orWhereRaw('name like ?', [$term])
                    ->orWhereRaw('vin like ?', [$term]);
                });
            });
        }
        if (request()->filled('key')) {
            $term = '%' . request('key') . '%';
            $orders->whereHas('key', function ($q) use ($term) {
                $q->whereRaw('name like ?', [$term]);
            });
        }
        if (request()->filled('technician')) {
            $term = '%' . request('technician') . '%';
            $orders->whereHas('technician', function ($q) use ($term) {
                $q->where(function ($q) use ($term) {
                    $q->whereRaw('concat(first_name, concat(" ", concat(last_name))) like ?', [$term])
                        ->orWhereRaw('concat(last_name, concat(", ", concat(first_name))) like ?', [$term]);
                });
            });
        }
        $paginate = $orders->paginate(10);
        $paginate->getCollection()->transform(function ($i) {
            return [
                'id' => $i->id,
                'vehicle' => "{$i->vehicle->manufacturer->name} - {$i->vehicle->name} ({$i->vehicle->vin})",
                'key' => $i->key->name,
                'technician' => $i->technician->full_name
            ];
        });
        return $paginate;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order();
        return $this->update($request, $order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $this->validate($request, [
            'vehicle_id' => 'required|exists:App\Models\Vehicle,id',
            'key_id' => 'required|exists:App\Models\Key,id',
            'technician_id' => 'required|exists:App\Models\Technician,id',
        ]);
        $order->vehicle_id = $request->get('vehicle_id');
        $order->key_id = $request->get('key_id');
        $order->technician_id = $request->get('technician_id');
        $order->save();
        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        return $order->delete();
    }
}
