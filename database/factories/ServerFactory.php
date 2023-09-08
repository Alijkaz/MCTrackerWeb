<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word,
            'description' => fake()->text,
            'address' => fake()->unique()->domainName,
            'ip' => fake()->unique()->ipv4,
            'country_code' => fake()->countryCode,
            'region' => fake()->country,
            'gamemodes' => '[]',
            'up_from' => fake()->randomElement([-time(), time()]),
        ];
    }

    /**
     * Indicate that server is online
     */
    public function votifier(): static
    {
        return $this->state(fn (array $attributes) => [
            'votifier_token' => uniqid(),
            'votifier_ip' => fake()->ipv4,
            'votifier_port' => fake()->numberBetween(1, 65535),
        ]);
    }

    /**
     * Indicate that server is online
     */
    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'up_from' => time(),
        ]);
    }

    /**
     * Indicate that server is offline
     */
    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'up_from' => -time(),
        ]);
    }

    /**
     * Indicate that server is vip
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_vip' => true,
        ]);
    }

    /**
     * Indicate that server is deactivated
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
