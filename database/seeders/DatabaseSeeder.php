<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Key;
use App\Models\Manufacturer;
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
        $keys = Key::factory()->count(2)->create();

        foreach($makerModels as $k => $models) {
            $maker = Manufacturer::firstOrCreate(['name' => $k]);
            foreach($models as $m) {
                Vehicle::factory()->create(['manufacturer_id' => $maker->id, 'name' => $m]);
            }
        }
    }
}
