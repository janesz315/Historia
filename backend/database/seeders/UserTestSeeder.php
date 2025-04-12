<?php

namespace Database\Seeders;

use App\Models\UserTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Az admin felhasználó id-ja
        $adminUserId = 1;

        // Hozzáadunk néhány tesztet az admin felhasználóhoz
        UserTest::create([
            'userId'    => $adminUserId, // Az admin felhasználó id-ja
            'testName'  => 'Teszt 1', // Teszt neve
            'score'     => 85.5, // Teszt eredménye
        ]);

        UserTest::create([
            'userId'    => $adminUserId, // Az admin felhasználó id-ja
            'testName'  => 'Teszt 2', // Teszt neve
            'score'     => 92.3, // Teszt eredménye
        ]);
    }
}
