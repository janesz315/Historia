<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => $this->faker->word(),  // Véletlenszerű kategória név (pl. "Matematika")
            'level' => $this->faker->randomElement(['közép', 'emelt']),  // Véletlenszerű szint: easy, medium vagy hard
            'text' => $this->faker->text(),  // Véletlenszerű leírás
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}