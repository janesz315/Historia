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