<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Roles>
 */
class RolesFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'role' => $this->faker->word,
        ];
    }
}