<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Csak akkor hozzuk létre az adatot, ha még nem létezik
        if (User::count() === 0) {
            User::create([
                'id' => 1,
                'name' => 'test',
                'email' => 'test@example.com',
                'password' => '123',
                'roleId' => 1,
            ]);
        }
    }
}
