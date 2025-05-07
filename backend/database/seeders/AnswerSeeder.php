<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Megnézzük, hány témakör van a categories táblában
        $questions = Question::all();

        // Ha nincsenek témakör, akkor nem csinálunk semmit
        

        // Minden kérdéshez hozzárendelünk válaszokat
        foreach ($questions as $question) {
            // Mivel minden kérdéshez több válasz is tartozik, generálunk véletlenszerű számú választ
            $numberOfAnswers = rand(2, 4); // 2-4 válaszlehetőség kérdésenként

            // Véletlenszerűen elhelyezzük a helyes választ
            $correctAnswerPosition = rand(0, $numberOfAnswers - 1); // Véletlenszerű helyre kerül a helyes válasz

            for ($i = 0; $i < $numberOfAnswers; $i++) {
                // A válasz rögzítése, a helyes válasz csak egy válaszhoz tartozik
                Answer::create([
                    'answer' => $faker->sentence(), // Véletlenszerű válasz
                    'questionId' => $question->id, // Kapcsolódás a kérdéshez
                    'rightAnswer' => ($i === $correctAnswerPosition) ? 1 : 0, // Ha a helyes válasz pozíciója, akkor 1 (helyes), egyébként 0
                ]);
            }
        }
    }
}

