<?php

namespace Tests\Feature;

use App\Models\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuestionsTest extends TestCase
{

    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
    }
    public function test_the_get_questions_table_all_record_example(): void
    {
        $row = Question::create([
            'question' => 'Mikor halt meg Sztálin?',
            'questionTypeId' => 2,
            'categoryId' => 14
        ]);

        $response = $this->get('/api/questions');
        //A táblába bekerült a rekord
        $response->assertSee('Mikor halt meg Sztálin?');
        $response->assertStatus(200);


        $this->assertDatabaseHas('questions', ['question' => 'Mikor halt meg Sztálin?']); // Ellenőrizzük, hogy az adatbázisban is létezik az első adat
    }
}
