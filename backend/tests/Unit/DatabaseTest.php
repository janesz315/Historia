<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase; // Használj Laravel saját TestCase osztályát
use Illuminate\Support\Facades\Schema;

class DatabaseTest extends TestCase
{
     use DatabaseTransactions; // Ha szeretnéd, hogy minden teszt előtt új adatbázist építsen

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

    public function test_roles_users_relationships(){  

        // A diák tábla kapcsolatai
        $databaseName = env('DB_DATABASE');
        $tableName = "users";
        $constraint_name = "PRIMARY";
    
        $query = "
            SELECT 
                TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM 
                information_schema.KEY_COLUMN_USAGE
            WHERE
                TABLE_NAME = ? and CONSTRAINT_SCHEMA = ? and REFERENCED_TABLE_NAME IS NOT NULL";
    
        $rows = DB::select($query, [$tableName, $databaseName]);
    
        // Ellenőrizzük, hogy van találat
        if (count($rows) > 0) {
            // Debugging: nyomtatás, hogy megnézd mi van a $rows-ban
            // dd($rows);
    
            // Ellenőrizzük, hogy a COLUMN_NAME valóban roleId
            $this->assertTrue(isset($rows[0]->COLUMN_NAME));
            $this->assertEquals('roleId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
            $this->assertEquals('roles', $rows[0]->REFERENCED_TABLE_NAME);
            $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
        } else {
            $this->fail('Nincs találat az idegen kulcsokra.');
        }
    
        // Készítünk egy rolet
        $dataRole = [
            'role' => 'creator'
        ];
        $role = Role::factory()->create($dataRole);
    
        // Az új role-val készítek egy usert
        $dataUser = [
            'roleId' => $role->id,
            'name' => 'Rudi',
            'email' => "test2@example.com",
        ];
        $user = User::factory()->create($dataUser);
    
        // Visszakeressük a usert
        $user = DB::table('users')
            ->where('id', $user->id)
            ->first();
    
        // A megtalált user roleId-je megegyezik a új roleId-jével        
        $this->assertEquals($role->id, $user->roleId);
    }
   


public function test_categories_sources_relationships(){  

    // A diák tábla kapcsolatai
    $databaseName = env('DB_DATABASE');
    $tableName = "sources";
    $constraint_name = "PRIMARY";

    $query = "
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            information_schema.KEY_COLUMN_USAGE
        WHERE
            TABLE_NAME = ? and CONSTRAINT_SCHEMA = ? and REFERENCED_TABLE_NAME IS NOT NULL";

    $rows = DB::select($query, [$tableName, $databaseName]);

    // Ellenőrizzük, hogy van találat
    if (count($rows) > 0) {
        // Debugging: nyomtatás, hogy megnézd mi van a $rows-ban
        // dd($rows);

        // Ellenőrizzük, hogy a COLUMN_NAME valóban roleId
        $this->assertTrue(isset($rows[0]->COLUMN_NAME));
        $this->assertEquals('categoryId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
        $this->assertEquals('categories', $rows[0]->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
    } else {
        $this->fail('Nincs találat az idegen kulcsokra.');
    }

    // Készítünk egy rolet
    $dataCategory = [
        'category' => 'Asszíria',
        'level' => 'emelt',
        'text' => ''
    ];
    $category = Category::factory()->create($dataCategory);

    // Az új role-val készítek egy usert
    $dataSource = [
        'categoryId' => $category->id,
        'sourceLink' => 'https://mek.oszk.hu',
        'note' => "68-71. oldal",
    ];
    $source = Source::factory()->create($dataSource);

    // Visszakeressük a usert
    $source = DB::table('sources')
        ->where('id', $source->id)
        ->first();

    // A megtalált user roleId-je megegyezik a új roleId-jével        
    $this->assertEquals($category->id, $source->categoryId);
}



// public function test_questiontypes_questions_relationships(){  

//     // A diák tábla kapcsolatai
//     $databaseName = env('DB_DATABASE');
//     $tableName = "questions";
//     $constraint_name = "PRIMARY";

//     $query = "
//         SELECT 
//             TABLE_NAME,
//             COLUMN_NAME,
//             CONSTRAINT_NAME,
//             REFERENCED_TABLE_NAME,
//             REFERENCED_COLUMN_NAME
//         FROM 
//             information_schema.KEY_COLUMN_USAGE
//         WHERE
//             TABLE_NAME = ? and CONSTRAINT_SCHEMA = ? and REFERENCED_TABLE_NAME IS NOT NULL";

//     $rows = DB::select($query, [$tableName, $databaseName]);

//     // Ellenőrizzük, hogy van találat
//     if (count($rows) > 0) {
//         // Debugging: nyomtatás, hogy megnézd mi van a $rows-ban
//         // dd($rows);

//         // Ellenőrizzük, hogy a COLUMN_NAME valóban roleId
//         $this->assertTrue(isset($rows[0]->COLUMN_NAME));
//         $this->assertEquals('questionTypeId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
//         $this->assertEquals('question_types', $rows[0]->REFERENCED_TABLE_NAME);
//         $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
//     } else {
//         $this->fail('Nincs találat az idegen kulcsokra.');
//     }

//     // Készítünk egy rolet
//     $dataQuestionType = [
//         'questionCategory' => 'Találós kérdés'
//     ];
//     $questionType = QuestionType::factory()->create($dataQuestionType);

//     // Az új role-val készítek egy usert
//     $dataQuestion = [
//         'questionTypeId' => $questionType->id,
//         'question' => 'Mikor volt a gyulai csata?',
//         'categoryId' => 2,
//     ];
//     $question = Question::factory()->create($dataQuestion);

//     // Visszakeressük a usert
//     $question = DB::table('users')
//         ->where('id', $question->id)
//         ->first();

//     // A megtalált user roleId-je megegyezik a új roleId-jével        
//     $this->assertEquals($questionType->id, $question->questionTypeId);
// }

// public function test_questiontypes_questions_relationships(){  

//     // A diák tábla kapcsolatai
//     $databaseName = env('DB_DATABASE');
//     $tableName = "questions";
//     $constraint_name = "PRIMARY";

//     // Lekérdezzük a `questionTypeId` idegen kulcsot
//     $query = "
//         SELECT 
//             TABLE_NAME,
//             COLUMN_NAME,
//             CONSTRAINT_NAME,
//             REFERENCED_TABLE_NAME,
//             REFERENCED_COLUMN_NAME
//         FROM 
//             information_schema.KEY_COLUMN_USAGE
//         WHERE
//             TABLE_NAME = ? and CONSTRAINT_SCHEMA = ? and COLUMN_NAME = 'questionTypeId' and REFERENCED_TABLE_NAME IS NOT NULL";

//     $rows = DB::select($query, [$tableName, $databaseName]);

//     // Ellenőrizzük, hogy van találat
//     if (count($rows) > 0) {
//         // Debugging: nyomtatás, hogy megnézd mi van a $rows-ban
//         // dd($rows);

//         // Ellenőrizzük, hogy a COLUMN_NAME valóban questionTypeId
//         $this->assertTrue(isset($rows[0]->COLUMN_NAME));
//         $this->assertEquals('questionTypeId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
//         $this->assertEquals('question_types', $rows[0]->REFERENCED_TABLE_NAME);
//         $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
//     } else {
//         $this->fail('Nincs találat az idegen kulcsokra.');
//     }

//     // Készítünk egy kérdéstípus rekordot
//     $dataQuestionType = [
//         'questionCategory' => 'Találós kérdés'
//     ];
//     $questionType = QuestionType::factory()->create($dataQuestionType);

//     // Az új kérdéstípus-val készítek egy kérdést
//     $dataQuestion = [
//         'questionTypeId' => $questionType->id,
//         'question' => 'Mikor volt a gyulai csata?',
//         'categoryId' => 2,
//     ];
//     $question = Question::factory()->create($dataQuestion);

//     // Visszakeressük a kérdést és ellenőrizzük a kérdéstípus kapcsolatot
//     $question = DB::table('questions')
//         ->where('id', $question->id)
//         ->first();

//     // A megtalált kérdés questionTypeId-je megegyezik az új kérdéstípus id-jével        
//     $this->assertEquals($questionType->id, $question->questionTypeId);
// }

public function test_questiontypes_questions_categories_relationships(){  

    // A diák tábla kapcsolatai
    $databaseName = env('DB_DATABASE');
    $tableName = "questions";

    // Lekérdezzük mindkét idegen kulcsot (questionTypeId és categoryId)
    $query = "
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            information_schema.KEY_COLUMN_USAGE
        WHERE
            TABLE_NAME = ? 
            AND CONSTRAINT_SCHEMA = ? 
            AND REFERENCED_TABLE_NAME IS NOT NULL
            AND COLUMN_NAME IN ('questionTypeId', 'categoryId')";

    $rows = DB::select($query, [$tableName, $databaseName]);

    // Ellenőrizzük, hogy van találat
    if (count($rows) > 0) {
        $columnNames = array_column($rows, 'COLUMN_NAME');
        
        // Ellenőrizzük, hogy mindkét idegen kulcs szerepel
        $this->assertTrue(in_array('questionTypeId', $columnNames));
        $this->assertTrue(in_array('categoryId', $columnNames));

        // Ellenőrizzük a 'questionTypeId' kapcsolatot
        $questionTypeIdRelation = collect($rows)->firstWhere('COLUMN_NAME', 'questionTypeId');
        $this->assertEquals('question_types', $questionTypeIdRelation->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $questionTypeIdRelation->REFERENCED_COLUMN_NAME);

        // Ellenőrizzük a 'categoryId' kapcsolatot
        $categoryIdRelation = collect($rows)->firstWhere('COLUMN_NAME', 'categoryId');
        $this->assertEquals('categories', $categoryIdRelation->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $categoryIdRelation->REFERENCED_COLUMN_NAME);
    } else {
        $this->fail('Nincs találat az idegen kulcsokra.');
    }

    // Készítünk egy kérdéstípus rekordot
    $dataQuestionType = [
        'questionCategory' => 'Találós kérdés'
    ];
    $questionType = QuestionType::factory()->create($dataQuestionType);

    // Készítünk egy kategória rekordot
    $dataCategory = [
        'category' => 'Történelem'
    ];
    $category = Category::factory()->create($dataCategory);

    // Az új kérdéstípus és kategóriával készítek egy kérdést
    $dataQuestion = [
        'questionTypeId' => $questionType->id,
        'categoryId' => $category->id,
        'question' => 'Mikor volt a gyulai csata?',
    ];
    $question = Question::factory()->create($dataQuestion);

    // Visszakeressük a kérdést és ellenőrizzük mindkét kapcsolatot
    $question = DB::table('questions')
        ->where('id', $question->id)
        ->first();

    // Ellenőrizzük, hogy a questionTypeId kapcsolódik a megfelelő kérdéstípushoz
    $this->assertEquals($questionType->id, $question->questionTypeId);
    
    // Ellenőrizzük, hogy a categoryId kapcsolódik a megfelelő kategóriához
    $this->assertEquals($category->id, $question->categoryId);
}


public function test_questions_answers_relationships(){  

    // A diák tábla kapcsolatai
    $databaseName = env('DB_DATABASE');
    $tableName = "answers";
    $constraint_name = "PRIMARY";

    $query = "
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            information_schema.KEY_COLUMN_USAGE
        WHERE
            TABLE_NAME = ? and CONSTRAINT_SCHEMA = ? and REFERENCED_TABLE_NAME IS NOT NULL";

    $rows = DB::select($query, [$tableName, $databaseName]);

    // Ellenőrizzük, hogy van találat
    if (count($rows) > 0) {
        // Debugging: nyomtatás, hogy megnézd mi van a $rows-ban
        // dd($rows);

        // Ellenőrizzük, hogy a COLUMN_NAME valóban roleId
        $this->assertTrue(isset($rows[0]->COLUMN_NAME));
        $this->assertEquals('questionId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
        $this->assertEquals('questions', $rows[0]->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
    } else {
        $this->fail('Nincs találat az idegen kulcsokra.');
    }

    // Készítünk egy rolet
    $dataQuestion = [
        'question' => 'Mikor volt a Bastille ostroma?',
        'questionTypeId' => 2,
        'categoryId' => 12
    ];
    $question = Question::factory()->create($dataQuestion);

    // Az új role-val készítek egy usert
    $dataAnswer = [
        'questionId' => $question->id,
        'answer' => '1789. július 14.',
        'rightAnswer' => 1,
    ];
    $answer = Answer::factory()->create($dataAnswer);

    // Visszakeressük a usert
    $answer = DB::table('answers')
        ->where('id', $answer->id)
        ->first();

    // A megtalált user roleId-je megegyezik a új roleId-jével        
    $this->assertEquals($question->id, $answer->questionId);
}

}    


    

//     public function test_roles_users_relationships(){  

//         //A diák tábla kapcsolatai
//         $databaseName = env('DB_DATABASE');
//         $tableName = "users";
//         $contstraint_name = "PRIMARY";

//         $query = "
//             SELECT 
//                 TABLE_NAME,
//                 COLUMN_NAME,
//                 CONSTRAINT_NAME,
//                 REFERENCED_TABLE_NAME,
//                 REFERENCED_COLUMN_NAME
//             FROM 
//                 information_schema.KEY_COLUMN_USAGE
//             WHERE
//                 TABLE_NAME = ? and CONSTRAINT_SCHEMA = ? and CONSTRAINT_NAME <> ?";

//                 $rows= DB::select($query, [$tableName, $databaseName, $contstraint_name]);
//                 // dd($rows);
//         //Idegen kulcs neve
//         $this->assertEquals('roleId', $rows[0]->COLUMN_NAME);
//         //Referencia tábla neve
//         $this->assertEquals('roles', $rows[0]->REFERENCED_TABLE_NAME);
//         //Referencia oszlop neve
//         $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);


//         //Készítünk egy rolet
//         $dataRole = 
//         [
//             'role' => 'creator'
//         ];
//         $role = Role::factory()->create($dataRole);

//         //Az új role-val készítek egy usert
//         $dataUser = 
//             [
//             'roleId' => $role->id, 
//             'name' => 'Rudi', 
//             'email' => "test2@example.com", 
            
//         ];
//         $user = User::factory()->create($dataUser);

//         //visszakeressük a usert
//         $user = DB::table('users')
//         ->where('id', $user->id)
//         ->first();

//         //A megtalált user roleId-je megegyezik a új roleIid-jével        
//         $this->assertEquals($role->id, $user->roleId);
//         // dd($diak);

//     }
// }
