<?php

namespace Tests\Unit;


use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    //Adatbázis és tábláinak ellenőrzése
    public function test_database_creation_and_tables_exists()
    {
        $databaseNameConn = DB::connection()->getDatabaseName();
        // dd($databaseNameConn);
        $databaseNameEnv = env('DB_DATABASE');
        //dd($databaseNameConn == $databaseNameEnv);
        //Az adatbázis ellenőrzése
        $this->assertEquals($databaseNameConn, $databaseNameEnv);
        //Táblák létezésének 
        $this->assertDatabaseHas('users');
        $this->assertDatabaseHas('roles');
        $this->assertDatabaseHas('categories');
        $this->assertDatabaseHas('sources');
        $this->assertDatabaseHas('question_types');
        $this->assertDatabaseHas('questions');
        $this->assertDatabaseHas('answers');
        $this->assertDatabaseHas('test_questions');
        $this->assertDatabaseHas('user_tests');
        echo PHP_EOL."\tAdatbázis -> DB_DATABASE={$databaseNameEnv} | adatbázis: {$databaseNameConn}";
    }
}
