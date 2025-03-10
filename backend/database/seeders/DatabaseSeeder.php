<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void {
        DB::statement('DELETE FROM roles');
        DB::statement('DELETE FROM users');
        DB::statement('DELETE FROM categories');
        DB::statement('DELETE FROM sources');
        DB::statement('DELETE FROM question_types');
        DB::statement('DELETE FROM questions');
        DB::statement('DELETE FROM answers');
        DB::statement('DELETE FROM user_tests');
        DB::statement('DELETE FROM test_questions');

        

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            SourceSeeder::class,
            QuestionTypeSeeder::class,
            QuestionSeeder::class,
            AnswerSeeder::class,
            UserTestSeeder::class,
            TestQuestionSeeder::class,
        ]);
    }
}