<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryQuestionsWithTypesAndAnswersTimeTest extends TestCase
{
    private $startTime;
    private $endTime;
    private $responseTime;

    /**
     * A basic test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
        // VAGY célzottan csak az auth-ot:
        // $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        // $this->withoutMiddleware(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
    }
    public function test_the_querygetQuestionsWithTypesAndAnswers_response_time(): void
    {
        $this->startTime = microtime(true);
        $response = $this->get('/api/getQuestionsWithTypesAndAnswers');
        // $response = $this->get('/api/queryOsztalytasrsak/Balla Albert');
        $this->endTime = microtime(true);

        $response->assertStatus(200);

        $this->responseTime = round(($this->endTime - $this->startTime) * 1000, 2);
        $this->assertLessThan(200, $this->responseTime);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        echo PHP_EOL . "\tA válaszidő (getQuestionsWithTypesAndAnswers): {$this->responseTime} ms";
    }
}
