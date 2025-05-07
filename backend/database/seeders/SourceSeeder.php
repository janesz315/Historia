<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Faker példányosítása a véletlenszerű adatok generálásához
        $faker = Faker::create();

        // Megnézzük, hány témakör van a categories táblában
        $categories = Category::all();

        // Ha nincsenek témakörök, akkor nem csinálunk semmit
        

        // Adatok feltöltése (például 10 forrás)
        foreach (range(1, 50) as $index) {
            // Véletlenszerű kategória választása
            $categoryId = $categories->random()->id;

            // Source létrehozása
            Source::create([
                'categoryId' => $categoryId,
                'sourceLink' => $faker->url, // Véletlenszerű URL generálása
                'note' => $faker->sentence, // Véletlenszerű szöveg generálása
            ]);
        }
    }
    }

