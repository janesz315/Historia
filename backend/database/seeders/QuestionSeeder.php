<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
            ['question' => 'Mikor volt a Rozgonyi csata?', 'questionTypeId' => 1, 'categoryId' => 1],
            ['question' => 'Mit jelent az asszimiláció', 'questionTypeId' => 2, 'categoryId' => 2],
            ['question' => 'Ki volt aki feltalálta a telefont?', 'questionTypeId' => 3, 'categoryId' => 1],
        ]);
    }
}
