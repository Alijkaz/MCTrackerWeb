<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'players' => fake()->numberBetween(0, 400),
            'max_players' => fake()->numberBetween(400, 500),
            'version' => fake()->randomElement(['Paper 1.20.1', 'Spigot 1.19.2', '1.7-1.19']),
            'latency' => fake()->numberBetween(1, 150)
        ];
    }
}
