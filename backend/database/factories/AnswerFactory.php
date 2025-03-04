<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answers>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'answer' => $this->faker->text(200),  // Véletlenszerű válasz szöveg
            'questionId' => Question::factory(),  // Hozzárendeljük a generált kérdést
            'rightAnswer' => $this->faker->boolean(),  // Véletlenszerűen generálunk helyes (1) vagy hibás (0) választ
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}