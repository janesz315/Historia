<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\UserTest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestQuestion>
 */
class TestQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'questionId' => Question::factory(),  // Véletlenszerű kérdés generálása
            'answerId' => Answer::factory(),      // Véletlenszerű válasz generálása
            'userTestId' => UserTest::factory(),  // Véletlenszerű felhasználói teszt generálása
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


}