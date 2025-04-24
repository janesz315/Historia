<?php

namespace Tests\Feature;

use App\Models\QuestionType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuestionTypesTest extends TestCase
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
    public function test_the_get_question_types_table_all_record_example(): void
    {
        $row = QuestionType::create([
            'questionCategory' => 'Szöveges'
        ]);

        $response = $this->get('/api/questionTypes');
        //A táblába bekerült a rekord
        // $response -> assertSee('https://mek.oszk.hu/hu/');
        $response->assertSee('Szöveges');
        $response->assertStatus(200);


        $this->assertDatabaseHas('question_types', ['questionCategory' => 'Szöveges']);
    }
}
