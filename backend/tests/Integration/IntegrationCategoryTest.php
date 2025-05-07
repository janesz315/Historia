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

 // Itt jön a setup metódus
 protected function setUp(): void
 {
     parent::setUp();

     // Kikapcsoljuk az auth middleware-t tesztnél
     $this->withoutMiddleware();
 }


 public function test_categories_http(): void
 {
     $user = User::factory()->create([
         'roleId' => 1
     ]);
 
     $this->actingAs($user);
 
     $response = $this->getJson('/api/categories');
 
     $response->assertStatus(200);
     $response->assertJsonStructure([
         'message',
         'data'
     ]);
    //  dd($response);
 }
}