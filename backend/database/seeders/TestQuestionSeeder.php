<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\TestQuestion;
use App\Models\UserTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Az user_tests tábla id-ját használjuk, ami már létezik
        $userTest = UserTest::first();  // Ha van legalább egy rekord a user_tests táblában

        // Ellenőrizd, hogy van-e legalább egy teszt
        if ($userTest) {
            // Itt lekérdezünk néhány kérdést és választ, amelyek már léteznek a questions és answers táblákban
            $questions = Question::all();  // Minden kérdés
            $answers = Answer::all();      // Minden válasz

            // Feltételezzük, hogy a kérdések és válaszok már léteznek az adatbázisban

            foreach ($questions as $question) {
                // Véletlenszerű válasz kiválasztása (vagy valami logika szerint)
                $randomAnswer = $answers->random();

                // Feltöltjük a test_questions táblát
                TestQuestion::create([
                    'questionId'   => $question->id,   // Kérdés id-ja
                    'answerId'     => $randomAnswer->id, // Véletlenszerű válasz id-ja
                    'userTestId'   => $userTest->id,   // A teszt id-ja, ami a user_tests táblában van
                ]);
            }
        }
    }
}
