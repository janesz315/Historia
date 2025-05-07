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
    }
}