<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionTypes>
 */
class QuestionTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'questionCategory' => $this->faker->word(),  // Véletlenszerű kérdéskategória generálása (pl. "multiple_choice", "true_false")
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}