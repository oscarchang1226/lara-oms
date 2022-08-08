<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'vin' => fake()->unique()->regexify('[A-Z0-9]{17}'),
            'name' => fake()->randomElement(['Model K', 'Model A', 'Model R'])
        ];
    }
}
