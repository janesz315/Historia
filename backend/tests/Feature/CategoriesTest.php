<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoriesTest extends TestCase
{

    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
    }
    public function test_the_get_categories_table_all_record_example(): void
    {
        $row = Category::create([
            'category' => 'xxx',
            'level' => 'emelt',
            'text' => ''
        ]);

        $response = $this->get('/api/categories');
        //A táblába bekerült a rekord
        $response->assertSee('xxx');
        $response->assertSee('emelt');
        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'category' => 'xxx',
            'level' => 'emelt'
        ]);
    }
}
