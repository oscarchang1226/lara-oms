<?php

namespace App\Http\Controllers;

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
        $orders = Order::query();
        if (request()->filled('vehicle')) {
            $orders->whereHas('vehicle', function ($q) {
                $q->where(function ($q) {
                    $term = '%' . request('vehicle') . '%';
                    $q->whereHas('manufacturer', function ($q) use ($term) {
                        $q->where('manufacturers.name', 'like', $term);
                    })
                    ->orWhere('name', 'like', $term)
                    ->orWhere('vin', 'like', $term);
                });
            });
        }
        return $orders->paginate(10);
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
