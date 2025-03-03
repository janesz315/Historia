<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase; // Használj Laravel saját TestCase osztályát
use Illuminate\Support\Facades\Schema;

class DatabaseTest extends TestCase
{
    use RefreshDatabase; // Ha szeretnéd, hogy minden teszt előtt új adatbázist építsen

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    // Adatbázis és tábláinak ellenőrzése
    

public function test_database_creation_and_tables_exists()
{
    $databaseNameConn = DB::connection()->getDatabaseName();
    $databaseNameEnv = env('DB_DATABASE');
    $this->assertEquals($databaseNameConn, $databaseNameEnv);

    // Táblák létezésének ellenőrzése a Schema facáddal
    $this->assertTrue(Schema::hasTable('users'));
    $this->assertTrue(Schema::hasTable('roles'));
    $this->assertTrue(Schema::hasTable('categories'));
    $this->assertTrue(Schema::hasTable('sources'));
    $this->assertTrue(Schema::hasTable('question_types'));
    $this->assertTrue(Schema::hasTable('questions'));
    $this->assertTrue(Schema::hasTable('answers'));
    $this->assertTrue(Schema::hasTable('test_questions'));
    $this->assertTrue(Schema::hasTable('user_tests'));

    echo PHP_EOL . "\tAdatbázis -> DB_DATABASE={$databaseNameEnv} | adatbázis: {$databaseNameConn}";
}

// public function test_roles_table_structure()
//     {
//         // Ellenőrizzük, hogy a tábla létezik
//         $this->assertTrue(Schema::hasTable('roles'));

//         // Ellenőrizzük a mezőket és azok típusait
//         $this->assertTrue(Schema::hasColumn('roles', 'id'));
//         $this->assertTrue(Schema::hasColumn('roles', 'role'));
//         $this->assertEquals('int', Schema::getColumnType('roles', 'id'));
//         //dd(Schema::getColumnType('sports', 'sportNev'));
//         $this->assertEquals('varchar', Schema::getColumnType('roles', 'role'));

//         $this->assertTrue(Schema::hasColumn('roles', 'id'));

//         //Elsődleges kulcs
//         $indexes = DB::select('SHOW INDEX FROM roles');
//         $primaryIndex = collect($indexes)->firstWhere('Key_name', 'PRIMARY');
//         $this->assertNotNull($primaryIndex);

//     }

public function test_roles_table_structure()
{
    // Ellenőrizzük, hogy a tábla létezik
    $this->assertTrue(Schema::hasTable('roles'));

    // Ellenőrizzük a mezőket és azok típusait
    $columns = [
        'id' => 'int',
        'role' => 'varchar',
    ];

    foreach ($columns as $column => $type) {
        $this->assertTrue(Schema::hasColumn('roles', $column));  // Ellenőrizzük, hogy a mező létezik
        $this->assertEquals($type, Schema::getColumnType('roles', $column));  // Ellenőrizzük a típusát
    }

    // Elsődleges kulcs ellenőrzése
    $primaryIndex = collect(DB::select('SHOW INDEX FROM roles'))->firstWhere('Key_name', 'PRIMARY');
    $this->assertNotNull($primaryIndex);
}


    public function test_categories_table_structure()
    {
        // Ellenőrizzük, hogy a tábla létezik
        $this->assertTrue(Schema::hasTable('categories'));

        // Ellenőrizzük a mezőket és azok típusait
        $this->assertTrue(Schema::hasColumn('categories', 'id'));
        $this->assertTrue(Schema::hasColumn('categories', 'category'));
        $this->assertTrue(Schema::hasColumn('categories', 'level'));
        $this->assertTrue(Schema::hasColumn('categories', 'text'));
        $this->assertEquals('int', Schema::getColumnType('categories', 'id'));
        //dd(Schema::getColumnType('sports', 'sportNev'));
        $this->assertEquals('varchar', Schema::getColumnType('categories', 'category'));
        $this->assertEquals('varchar', Schema::getColumnType('categories', 'level'));
        $this->assertEquals('text', Schema::getColumnType('categories', 'text'));

        $this->assertTrue(Schema::hasColumn('categories', 'id'));

        //Elsődleges kulcs
        $indexes = DB::select('SHOW INDEX FROM categories');
        $primaryIndex = collect($indexes)->firstWhere('Key_name', 'PRIMARY');
        $this->assertNotNull($primaryIndex);

    }

    public function test_sources_table_structure()
    {
        // Ellenőrizzük, hogy a tábla létezik
        $this->assertTrue(Schema::hasTable('sources'));

        // Ellenőrizzük a mezőket és azok típusait
        $columns = [
            'id' => 'int',
            'categoryId' => 'int',
            'sourceLink' => 'varchar',
            'note' => 'varchar',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('sources', $column));  // Ellenőrizzük, hogy a mező létezik
            $this->assertEquals($type, Schema::getColumnType('sources', $column));  // Ellenőrizzük a típusát
        }

        // Elsődleges kulcs ellenőrzése
        $primaryIndex = collect(DB::select('SHOW INDEX FROM sources'))->firstWhere('Key_name', 'PRIMARY');
        $this->assertNotNull($primaryIndex);
    }

    public function test_questionTypes_table_structure()
    {
        // Ellenőrizzük, hogy a tábla létezik
        $this->assertTrue(Schema::hasTable('question_types'));

        // Ellenőrizzük a mezőket és azok típusait
        $columns = [
            'id' => 'int',
            'questionCategory' => 'varchar',
            
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('question_types', $column));  // Ellenőrizzük, hogy a mező létezik
            $this->assertEquals($type, Schema::getColumnType('question_types', $column));  // Ellenőrizzük a típusát
        }

        // Elsődleges kulcs ellenőrzése
        $primaryIndex = collect(DB::select('SHOW INDEX FROM question_types'))->firstWhere('Key_name', 'PRIMARY');
        $this->assertNotNull($primaryIndex);
    }

    public function test_questions_table_structure()
    {
        // Ellenőrizzük, hogy a tábla létezik
        $this->assertTrue(Schema::hasTable('questions'));

        // Ellenőrizzük a mezőket és azok típusait
        $columns = [
            'id' => 'int',
            'question' => 'text',
            'questionTypeId' => 'int',
            'categoryId' => 'int',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('questions', $column));  // Ellenőrizzük, hogy a mező létezik
            $this->assertEquals($type, Schema::getColumnType('questions', $column));  // Ellenőrizzük a típusát
        }

        // Elsődleges kulcs ellenőrzése
        $primaryIndex = collect(DB::select('SHOW INDEX FROM questions'))->firstWhere('Key_name', 'PRIMARY');
        $this->assertNotNull($primaryIndex);
    }

    public function test_answers_table_structure()
    {
        // Ellenőrizzük, hogy a tábla létezik
        $this->assertTrue(Schema::hasTable('answers'));

        // Ellenőrizzük a mezőket és azok típusait
        $columns = [
            'id' => 'int',
            'answer' => 'text',
            'questionId' => 'int',
            'rightAnswer' => 'tinyint',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('answers', $column));  // Ellenőrizzük, hogy a mező létezik
            $this->assertEquals($type, Schema::getColumnType('answers', $column));  // Ellenőrizzük a típusát
        }

        // Elsődleges kulcs ellenőrzése
        $primaryIndex = collect(DB::select('SHOW INDEX FROM answers'))->firstWhere('Key_name', 'PRIMARY');
        $this->assertNotNull($primaryIndex);
    }

    public function test_testQuestions_table_structure()
    {
        // Ellenőrizzük, hogy a tábla létezik
        $this->assertTrue(Schema::hasTable('test_questions'));

        // Ellenőrizzük a mezőket és azok típusait
        $columns = [
            'id' => 'int',
            'questionId' => 'int',
            'answerId' => 'int',
            'userTestId' => 'int',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('test_questions', $column));  // Ellenőrizzük, hogy a mező létezik
            $this->assertEquals($type, Schema::getColumnType('test_questions', $column));  // Ellenőrizzük a típusát
        }

        // Elsődleges kulcs ellenőrzése
        $primaryIndex = collect(DB::select('SHOW INDEX FROM test_questions'))->firstWhere('Key_name', 'PRIMARY');
        $this->assertNotNull($primaryIndex);
    }

    public function test_userTests_table_structure()
    {
        // Ellenőrizzük, hogy a tábla létezik
        $this->assertTrue(Schema::hasTable('user_tests'));

        // Ellenőrizzük a mezőket és azok típusait
        $columns = [
            'id' => 'int',
            'userId' => 'int',
            'testName' => 'varchar',
            'score' => 'double',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('user_tests', $column));  // Ellenőrizzük, hogy a mező létezik
            $this->assertEquals($type, Schema::getColumnType('user_tests', $column));  // Ellenőrizzük a típusát
        }

        // Elsődleges kulcs ellenőrzése
        $primaryIndex = collect(DB::select('SHOW INDEX FROM user_tests'))->firstWhere('Key_name', 'PRIMARY');
        $this->assertNotNull($primaryIndex);
    }
}
