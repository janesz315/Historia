<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('question_types')->insert([
            ['questionCategory' => 'Évszámok'],
            ['questionCategory' => 'Fogalmak'],
            ['questionCategory' => 'Személyek'],
        ]);
    }
}
