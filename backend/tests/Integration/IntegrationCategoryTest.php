<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class IntegrationCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_categories_http(): void
    {
        //Ez szimulál egy klienst, ami ajax kérést képes küldeni egy endpointra
        $httpClient = new Client();
        $response = $httpClient->get('http://localhost:8000/api/categories');
        //A json választ dekódolja php tömbbé
        $data = json_decode($response->getBody()->getContents(), true);

        $statusCode = $response->getStatusCode();
        $message = $data['message'];
        $data = $data['data'];
        $this->assertEquals(200, $statusCode);
        $this->assertEquals('ok', $message);
        $this->assertGreaterThan(0, count($data));
        // dd($data);

    }
}