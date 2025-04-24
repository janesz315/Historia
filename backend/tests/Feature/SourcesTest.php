<?php

namespace Tests\Feature;

use App\Models\Source;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SourcesTest extends TestCase
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
    public function test_the_get_sources_table_all_record_example(): void
    {
        $row = Source::create([
            'categoryId' => 22,
            'sourceLink' => 'https://mek.oszk.hu/hu/',
            'note' => 'Ezen a linken van.'
        ]);

        $response = $this->get('/api/sources');
        //A táblába bekerült a rekord
        // $response -> assertSee('https://mek.oszk.hu/hu/');
        $response->assertSee('https:\/\/mek.oszk.hu\/hu\/');
        $response->assertSee('Ezen a linken van.');
        $response->assertStatus(200);


        $this->assertDatabaseHas('sources', [
            'sourceLink' => 'https://mek.oszk.hu/hu/',
            'note' => 'Ezen a linken van.'
        ]);
    }
}
