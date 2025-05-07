<?php

namespace Tests\Feature;

use App\Models\UserTest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTestsTest extends TestCase
{

    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
    }
    public function test_the_get_user_tests_table_all_record_example(): void
    {
        $row = UserTest::create([
            'userId' => 1,
            'testName' => 'Teszt5',
            'score' => 100
        ]);

        $response = $this->get('/api/userTests');
        //A táblába bekerült a rekord
        $response->assertSee('Teszt5');
        $response->assertStatus(200);


        $this->assertDatabaseHas('user_tests', [
            'testName' => 'Teszt5',
            'score' => 100
        ]);
    }
}
