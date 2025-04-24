<?php

namespace Tests\Feature;

use App\Models\Answer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnswersTest extends TestCase
{

    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
        // VAGY célzottan csak az auth-ot:
        // $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        // $this->withoutMiddleware(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
    }
    public function test_the_get_answers_table_all_record_example(): void
    {
        $row = Answer::create([
            'answer' => '1952',
            'questionId' => 1,
            'rightAnswer' => 0
        ]);

        $response = $this->get('/api/answers');
        //A táblába bekerült a rekord
        $response->assertSee('1952');
        $response->assertStatus(200);


        $this->assertDatabaseHas('answers', ['answer' => '1952']); // Ellenőrizzük, hogy az adatbázisban is létezik az első adat
    }
}
