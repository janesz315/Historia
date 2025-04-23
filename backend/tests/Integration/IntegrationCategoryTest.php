<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
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

//     public function test_categories_http(): void
// {
//     // 1. Létrehozunk egy felhasználót
//     $user = User::factory()->create();

//     // 2. Token generálása (Sanctum)
//     $token = $user->createToken('access-token', ['*'])->plainTextToken;

//     // 3. Guzzle kliens fejlécbe beállítjuk a tokent
//     $httpClient = new Client([
//         'base_uri' => 'http://localhost:8000',
//         'headers' => [
//             'Accept' => 'application/json',
//             'Authorization' => 'Bearer ' . $token,
//         ]
//     ]);

//     // 4. Küldjük a kérést
//     $response = $httpClient->get('/api/categories');

//     $data = json_decode($response->getBody()->getContents(), true);

//     $this->assertEquals(200, $response->getStatusCode());
//     $this->assertEquals('ok', $data['message']);
//     $this->assertGreaterThan(0, count($data['data']));
// }
}