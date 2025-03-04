<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTest>
 */
class UserTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'userId' => User::factory(),  // Véletlenszerűen generált felhasználó
            'testName' => $this->faker->word(),  // Véletlenszerű tesztnév (pl. "math_test")
            'score' => $this->faker->randomFloat(2, 0, 100),  // Véletlenszerű pontszám 0 és 100 között
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}