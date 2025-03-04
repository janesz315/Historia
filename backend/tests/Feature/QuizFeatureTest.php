<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\TestQuestion;
use App\Models\Answer;
use App\Models\Question;
use App\Models\UserTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuizFeatureTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_take_a_quiz()
    {
        // Hozzáadunk egy kérdést
        $question = Question::factory()->create();

        // Ellenőrizzük, hogy a kérdés sikeresen bekerült az adatbázisba
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question' => $question->question,  // Ellenőrizzük, hogy a kérdés szövege megegyezik
        ]);

        // Hozzáadunk egy választ a kérdéshez
        $answer = Answer::factory()->create([
            'questionId' => $question->id,  // Kérdéshez kapcsoljuk
            'rightAnswer' => 1,  // Helyes válasz
        ]);

        // Ellenőrizzük, hogy a válasz sikeresen bekerült az adatbázisba
        $this->assertDatabaseHas('answers', [
            'questionId' => $question->id,
            'rightAnswer' => 1,
        ]);

        // Létrehozzuk a szerepkört és a felhasználót
        $role = Role::factory()->create(['role' => 'guest']);
        $user = User::factory()->create(['roleId' => $role->id]);

        // Létrehozzuk a user_test rekordot
        $userTest = UserTest::factory()->create([
            'userId' => $user->id,  // A felhasználó azonosítója
        ]);

        // Ellenőrizzük, hogy a teszt rekordja sikeresen létrejött az adatbázisban
        $this->assertDatabaseHas('user_tests', [
            'userId' => $user->id,
            'testName' => $userTest->testName,
            'score' => $userTest->score,
        ]);

        // További ellenőrzés, például az HTTP válasz ellenőrzése
        // $response = $this->actingAs($user)->get('/some-test-route');
        // $response->assertStatus(200);
    }
}