<?php

namespace Tests\Feature;

use App\Models\TestQuestion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TestQuestionsTest extends TestCase
{

    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
    }
    public function test_the_get_test_questions_table_all_record_example(): void
    {
        $row = TestQuestion::create([
            'questionId' => 1,
            'answerId' => 2,
            'userTestId' => 1
        ]);

        $response = $this->get('/api/testQuestions');
        //A táblába bekerült a rekord
        $response->assertSee('2');
        $response->assertStatus(200);


        $this->assertDatabaseHas('test_questions', [
            'questionId' => 1,
            'answerId' => 2,
            'userTestId' => 1
        ]);
    }
}
