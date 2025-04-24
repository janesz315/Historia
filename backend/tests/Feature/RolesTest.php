<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RolesTest extends TestCase
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
    public function test_the_get_roles_table_all_record_example(): void
    {
        $row = Role::create([
            'role' => 'creator'
        ]);

        $response = $this->get('/api/roles');
        //A táblába bekerült a rekord
        $response->assertSee('creator');
        $response->assertStatus(200);


        $this->assertDatabaseHas('roles', ['role' => 'creator']);
    }
}
