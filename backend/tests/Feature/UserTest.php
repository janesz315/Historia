<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     // Kikapcsoljuk az auth middleware-t tesztnél
    //     $this->withoutMiddleware();
    //     // VAGY célzottan csak az auth-ot:
    //     // $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
    //     // $this->withoutMiddleware(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
    // }
    private function login(string $email = "test@example.com", string $password = "123")
    {
        //Bejelentkezés
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->postJson('/api/users/login', [
                'email' => $email,
                'password' => $password
            ]);

        $userData = $response->json('user');
        $token = $userData['token'] ?? null;
        // dd($token);
        return $token;
    }


    public function test_login()
    {

        //Csinálok egy user-t
        $name = 'test99';
        $email = 'test99@example.com';
        $roleId = '1';
        $password = '123';

        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'roleId' => $roleId,
            'password' => $password,
        ]);

        //Loginolok a user-el
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->postJson('/api/users/login', [
                'email' => $email,
                'password' => $password
            ]);

        //Lekérdezem, hogy a válasz státusza 200-e    
        $response->assertStatus(200);
        //Kiolvasom a válaszból a tokent
        $userData = $response->json('user');
        $token = $userData['token'] ?? null;
        // dd($token);
        //Ha van token, az jó
        $this->assertNotNull($token);


        //Egy védett útvonalra küldünk egy kérést
        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->get('/api/users');

        // Ellenőrizzük, hogy a kérés sikeres volt-e
        $response->assertStatus(200);
    }

    public function test_create_user(): void
    {

        $token = $this->login();
        // dd($token);

        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'roleId' => '1',
            'password' => 'password123',
        ];

        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->postJson('/api/users', $data);
        // dd($response);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);

        $user = User::where('email', 'johndoe@example.com')->first();
        $this->assertNotNull($user);
    }
}
