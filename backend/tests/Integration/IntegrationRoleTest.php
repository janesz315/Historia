<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class IntegrationRoleTest extends TestCase
{
    /**
     * A basic feature test example.
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
    public function test_roles_http(): void
    {

        $user = \App\Models\User::factory()->create([
            'roleId' => 1
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/roles');
    
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);
   
       //  dd($response);
        //Ez szimulál egy klienst, ami ajax kérést képes küldeni egy endpointra
        // $httpClient = new Client();
        // $response = $httpClient->get('http://localhost:8000/api/roles');
        // //A json választ dekódolja php tömbbé
        // $data = json_decode($response->getBody()->getContents(), true);

        // $statusCode = $response->getStatusCode();
        // $message = $data['message'];
        // $data = $data['data'];
        // $this->assertEquals(200, $statusCode);
        // $this->assertEquals('ok', $message);
        // $this->assertGreaterThan(0, count($data));
        // dd($data);

    }


    // public function test_check_if_mocking()
    // {
    //     $httpMock = Mockery::mock(['alias:' => \Illuminate\Support\Facades\Http::class]);
    //     $httpMock->shouldReceive('get')
    //         ->once()
    //         ->with('http://localhost:8000/api/roles/1')
    //         ->andReturn(
    //             [
    //                 [
    //                     "id" => 1,
    //                     "role" => "admin"
    //                 ]
    //             ]
    //         );

    //     $response= $httpMock->get('http://localhost:8000/api/roles/1');
    //     // dd($response);

    // }
}