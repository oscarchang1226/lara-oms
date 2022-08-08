<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Key;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\Technician;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $path = "$path/{$order->id}";
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
}
