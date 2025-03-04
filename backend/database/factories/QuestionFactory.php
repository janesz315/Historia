<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\QuestionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence(),  // Véletlenszerű kérdés szöveg
            'questionTypeId' => QuestionType::factory(),  // Véletlenszerű kérdéstípus generálása
            'categoryId' => Category::factory(),  // Véletlenszerű kategória generálása
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}