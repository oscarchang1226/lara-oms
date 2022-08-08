<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Key;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\Technician;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private function assertOrderRequest($method, $path, $body, $validationErrors)
    {
        $response = $this->json($method, $path, $body);
        if (count($validationErrors) > 0) {
            $response->assertJsonValidationErrors($validationErrors);
            $response->assertStatus(422);
        } else if ($method == 'patch') { 
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'id',
                'vehicle_id',
                'key_id',
                'technician_id'
            ]);
        } else {
            $response->assertStatus(201);
            $response->assertJsonStructure([
                'id',
                'vehicle_id',
                'key_id',
                'technician_id'
            ]);
        }
    }

    private function assertOrderRelation($order, $vehicle, $key, $technician)
    {
        $this->assertEquals($vehicle->vin, $order->vehicle->vin);
        $this->assertEquals($key->name, $order->key->name);
        $this->assertEquals($technician->full_name, $technician->full_name);
    }

    public function test_order_creation_and_update()
    {
        $body = [];
        $path = route('order.api.store');
        $validationErrors = [
            'vehicle_id',
            'key_id',
            'technician_id'
        ];
        $this->assertOrderRequest('post', $path, $body, $validationErrors);

        // Test Vehicle
        $body['vehicle_id'] = 1;
        $this->assertOrderRequest('post', $path, $body, $validationErrors);
        $manufacturer = Manufacturer::factory()->create();
        $vehicle = Vehicle::factory()->for($manufacturer)->create();
        $body['vehicle_id'] = $vehicle->id;
        array_shift($validationErrors);
        $this->assertOrderRequest('post', $path, $body, $validationErrors);

        // Test Key
        $body['key_id'] = 1;
        $this->assertOrderRequest('post', $path, $body, $validationErrors);
        $key = Key::factory()->create();
        $body['key_id'] = $key->id;
        array_shift($validationErrors);
        $this->assertOrderRequest('post', $path, $body, $validationErrors);

        // Test Technician
        $body['technician_id'] = 1;
        $this->assertOrderRequest('post', $path, $body, $validationErrors);
        $technician = Technician::factory()->create();
        $body['technician_id'] = $technician->id;
        array_shift($validationErrors);
        $this->assertOrderRequest('post', $path, $body, $validationErrors);

        $order = Order::find(Order::max('id'));

        // Test Created Order Relation
        $this->assertOrderRelation($order, $vehicle, $key, $technician);

        // Test Order
        $path = route('order.api.update', ['order' => $order]);
        $newVehicle = Vehicle::factory()->for($manufacturer)->create();
        $body['vehicle_id'] = $newVehicle->id;
        $newKey = Key::factory()->create();
        $body['key_id'] = $newKey->id;
        $newTechnician = Technician::factory()->create();
        $body['technician_id'] = $newTechnician->id;
        $this->assertOrderRequest('patch', $path, $body, $validationErrors);

        // Test Order Updated Values
        $order = Order::find($order->id);
        $this->assertOrderRelation($order, $newVehicle, $newKey, $newTechnician);
    }

    public function test_order_deletion()
    {
        $vehicle = Vehicle::factory()->forManufacturer()->create();
        $key = Key::factory()->create();
        $technician = Technician::factory()->create();
        $order = Order::factory()->for($vehicle)->for($key)->for($technician)->create();
        $response = $this->json('delete', route('order.api.delete', ['order' => $order]));
        $response->assertStatus(200);
        $this->assertNull(Order::find($order->id));
    }

    private function queryOrderApi($count, $ids, $query = [])
    {
        $response = $this->json('get', route('order.api', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'total']);
        $response->assertJsonFragment(['total' => $count]);
        $orderIds = Arr::pluck($response->json('data'), 'id');
        $this->assertEquals($ids, $orderIds);
    }

    private function createVehicles($manufacturer, $state, $count)
    {
        return Vehicle::factory()
            ->count($count)
            ->for($manufacturer)
            ->state($state)
            ->create();
    }

    private function createOrders($vehicles, $key, $technician)
    {
        return $vehicles->map(function ($v) use ($key, $technician) {
            return Order::factory()->for($v)->for($key)->for($technician)->create();
        });
    }

    public function test_order_query()
    {
        $manufacturer = Manufacturer::factory()->create();
        $modelKs = $this->createVehicles($manufacturer, ['name' => 'Model K'], 2);
        $modelAs = $this->createVehicles($manufacturer, ['name' => 'Model A'], 2);
        $modelRs = $this->createVehicles($manufacturer, ['name' => 'Model R'], 2);
        $keys = Key::factory()->count(3)->create();
        $technicians = Technician::factory()->count(2)->create();
        $orderBatch1 = $this->createOrders($modelKs, $keys[0], $technicians[0]);
        $orderBatch2 = $this->createOrders($modelAs, $keys[1], $technicians[0]);
        $orderBatch3 = $this->createOrders($modelRs, $keys[2], $technicians[1]);
        $orders = $orderBatch1->concat($orderBatch2)->concat($orderBatch3);

        // Test all
        $this->queryOrderApi(6, $orders->pluck('id')->toArray(), []);

        // Test by vehicle manufacturer
        $query = ['vehicle' => $manufacturer->name];
        $this->queryOrderApi(6, $orders->pluck('id')->toArray(), $query);
        
        // Test by vehicle model name
        $query = ['vehicle' => 'Model K'];
        $this->queryOrderApi(2, $orderBatch1->pluck('id')->toArray(), $query);

        // Test by vehicle vin number
        $vin = $modelAs[0]->vin;
        $query = ['vehicle' => substr($vin, count($vin) / 2, count($vin))];
        $this->queryOrderApi(1, [$orderBatch2[0]->id], $query);
    }
}
