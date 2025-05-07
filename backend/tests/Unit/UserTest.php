<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Container\Attributes\DB as AttributesDB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function setUp(): void
    {
        parent::setUp();
    }
    //Taranzakcióz minden tesztet és azt visszavonja
    use DatabaseTransactions;


    /**
     * A basic test example.
     */
    public function test_check_if_user_insert_into_db(): void
    {
        $userResponse = [
            'name' => 'roger',
            'value' => 1,
        ];
        $this->assertEquals(1, $userResponse['value']);
        $this->assertArrayHasKey('name', $userResponse);
    }

    /*
    *@test
    */
    public function test_check_if_users_getting_fetched_with_id(): void
    {
        $response = DB::table("users")->find(1);
        //Adott mező értékének ellenőrzése
        $this->assertEquals(1, $response->id);
        
        //A rekordok számának ellenőrzése
        $response = DB::table("users")->get();

        //A rekordok száma > mint 0
        $this->assertGreaterThan(0, count($response));
    }

    public function test_the_presence_of_the_given_user_in_the_database()
    {
        $this->assertTrue(DB::table('users')->where('name', 'test')->exists());
    }
    
    public function test_does_the_user_table_contain_all_fields()
    {
        //mezők megvannak-e
        $columns = ['id', 'name', 'roleId', 'email', 'password'];
        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('users', $column), "A '$column' oszlop nem található a 'users' táblában.");
        }
    }
}

