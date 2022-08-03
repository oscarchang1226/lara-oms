<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Maker;
use App\Models\MakerModel;
use App\Models\Vehicle;
use App\Models\Key;
use App\Models\VehicleKey;
use App\Models\Technician;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $makerModels = [
            "Honda" => [
                "Civic",
                "CRV",
                "Accord"
            ],
            "Toyota" => [
                "Corolla",
                "RAV4",
                "Camry"
            ],
        ];

        $technicians = Technician::factory()->count(5)->create();

        foreach($makerModels as $k => $models) {
            $maker = Maker::firstOrCreate(['name' => $k]);
            foreach($models as $m) {
                $m = MakerModel::firstOrCreate(['maker_id' => $maker->id, 'name' => $m, 'year' => 2022]);
                $key = Key::factory()->create();
                $vehicle = Vehicle::factory()->create(['maker_model_id' => $m->id]);
                $vehicleKey = VehicleKey::firstOrCreate(['vehicle_id' => $vehicle->id, 'key_id' => $key->id]);
                Order::firstOrCreate(['vehicle_key_id' => $vehicleKey->id, 'technician_id' => $technicians->random()->id]);
            }
        }
    }
}
