<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Domain;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Component>
 */
class ComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'domain_id' => Domain::factory(),
            'type' => $this->faker->randomElement(['text', 'button', 'image', 'link', 'divider']),
            'properties' => [],
            'order' => $this->faker->numberBetween(0, 10),
            'is_published' => $this->faker->boolean,
        ];
    }
}