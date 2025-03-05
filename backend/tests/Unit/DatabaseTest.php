<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Role;
use App\Models\Source;
use App\Models\TestQuestion;
use App\Models\User;
use App\Models\UserTest;
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

        // A users tábla kapcsolatai
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

    // A sources tábla kapcsolatai
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

        // Ellenőrizzük, hogy a COLUMN_NAME valóban categoryId
        $this->assertTrue(isset($rows[0]->COLUMN_NAME));
        $this->assertEquals('categoryId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
        $this->assertEquals('categories', $rows[0]->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
    } else {
        $this->fail('Nincs találat az idegen kulcsokra.');
    }

    // Készítünk egy kategóriát
    $dataCategory = [
        'category' => 'Asszíria',
        'level' => 'emelt',
        'text' => ''
    ];
    $category = Category::factory()->create($dataCategory);

    // Az új kategóriával készítek egy source-t
    $dataSource = [
        'categoryId' => $category->id,
        'sourceLink' => 'https://mek.oszk.hu',
        'note' => "68-71. oldal",
    ];
    $source = Source::factory()->create($dataSource);

    // Visszakeressük a source-t
    $source = DB::table('sources')
        ->where('id', $source->id)
        ->first();

    // A megtalált category sourceId-je megegyezik a új categoryId-jével        
    $this->assertEquals($category->id, $source->categoryId);
}

public function test_questiontypes_categories_questions_relationships(){  

    // A questions tábla kapcsolatai
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

    // Az answers tábla kapcsolatai
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

        // Ellenőrizzük, hogy a COLUMN_NAME valóban questionId
        $this->assertTrue(isset($rows[0]->COLUMN_NAME));
        $this->assertEquals('questionId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
        $this->assertEquals('questions', $rows[0]->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
    } else {
        $this->fail('Nincs találat az idegen kulcsokra.');
    }

    // Készítünk egy questiont
    $dataQuestion = [
        'question' => 'Mikor volt a Bastille ostroma?',
        'questionTypeId' => 2,
        'categoryId' => 12
    ];
    $question = Question::factory()->create($dataQuestion);

    // Az új question-val készítek egy answert
    $dataAnswer = [
        'questionId' => $question->id,
        'answer' => '1789. július 14.',
        'rightAnswer' => 1,
    ];
    $answer = Answer::factory()->create($dataAnswer);

    // Visszakeressük az answert
    $answer = DB::table('answers')
        ->where('id', $answer->id)
        ->first();

    // A megtalált answer questionId-je megegyezik a új questionId-jével        
    $this->assertEquals($question->id, $answer->questionId);
}

public function test_questions_answers_userTests_testQuestions_relationships(){  

    // A test_questions tábla kapcsolatai
    $databaseName = env('DB_DATABASE');
    $tableName = "test_questions";

    // Lekérdezzük mindhárom idegen kulcsot (questionId, answerId, userTestId)
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
            AND COLUMN_NAME IN ('questionId','answerId','userTestId')";

    $rows = DB::select($query, [$tableName, $databaseName]);

    // Ellenőrizzük, hogy van találat
    if (count($rows) > 0) {
        $columnNames = array_column($rows, 'COLUMN_NAME');
        
        // Ellenőrizzük, hogy mindhárom idegen kulcs szerepel
        $this->assertTrue(in_array('questionId', $columnNames));
        $this->assertTrue(in_array('answerId', $columnNames));
        $this->assertTrue(in_array('userTestId', $columnNames));

        // Ellenőrizzük a 'questionId' kapcsolatot
        $questionIdRelation = collect($rows)->firstWhere('COLUMN_NAME', 'questionId');
        $this->assertEquals('questions', $questionIdRelation->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $questionIdRelation->REFERENCED_COLUMN_NAME);

        // Ellenőrizzük a 'answerId' kapcsolatot
        $answerIdRelation = collect($rows)->firstWhere('COLUMN_NAME', 'answerId');
        $this->assertEquals('answers', $answerIdRelation->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $answerIdRelation->REFERENCED_COLUMN_NAME);

        // Ellenőrizzük a 'userTestId' kapcsolatot
        $userTestIdRelation = collect($rows)->firstWhere('COLUMN_NAME', 'userTestId');
        $this->assertEquals('user_tests', $userTestIdRelation->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $userTestIdRelation->REFERENCED_COLUMN_NAME);
    } else {
        $this->fail('Nincs találat az idegen kulcsokra.');
    }

    // Készítünk egy kérdés rekordot
    $dataQuestion = [
        'question' => 'Mikor volt Buda visszafoglalása?'
    ];
    $question = Question::factory()->create($dataQuestion);

    // Készítünk egy válasz rekordot
    $dataAnswer = [
        'answer' => '1602'
    ];
    $answer = Answer::factory()->create($dataAnswer);

    // Készítünk egy felhasználói teszt rekordot
    $dataUserTest = [
        'testName' => 'teszt310'
    ];
    $userTest = UserTest::factory()->create($dataUserTest);

    // Az új kérdéstípus és kategóriával készítek egy kérdést
    $dataTestQuestion = [
        'questionId' => $question->id,
        'answerId' => $answer->id,
        'userTestId' => $userTest->id
    ];
    $testQuestion = TestQuestion::factory()->create($dataTestQuestion);

    // Visszakeressük a kérdést és ellenőrizzük mindhárom kapcsolatot
    $testQuestionData = DB::table('test_questions')
        ->where('id', $testQuestion->id)
        ->first();

    // Ellenőrizzük, hogy a questionId kapcsolódik a megfelelő kérdéshez
    $this->assertEquals($question->id, $testQuestionData->questionId);
    
    // Ellenőrizzük, hogy az answerId kapcsolódik a megfelelő válaszhoz
    $this->assertEquals($answer->id, $testQuestionData->answerId);

    // Ellenőrizzük, hogy a userTestId kapcsolódik a megfelelő felhasználói teszthez
    $this->assertEquals($userTest->id, $testQuestionData->userTestId);
}

public function test_users_userTests_relationships(){  

    // Az user_tests tábla kapcsolatai
    $databaseName = env('DB_DATABASE');
    $tableName = "user_tests";
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

        // Ellenőrizzük, hogy a COLUMN_NAME valóban userId
        $this->assertTrue(isset($rows[0]->COLUMN_NAME));
        $this->assertEquals('userId', trim($rows[0]->COLUMN_NAME)); // A trim() még mindig hasznos lehet
        $this->assertEquals('users', $rows[0]->REFERENCED_TABLE_NAME);
        $this->assertEquals('id', $rows[0]->REFERENCED_COLUMN_NAME);
    } else {
        $this->fail('Nincs találat az idegen kulcsokra.');
    }

    // Készítünk egy usert
    $dataUser = [
        'roleId' => 2,
        'name' => 'Rudi',
        'email' => "test2@example.com",
    ];
    $user = User::factory()->create($dataUser);

    // Az új user-rel készítek egy user tesztet
    $dataUserTest = [
        'userId' => $user->id,
        'testName' => 'teszt1201',
        'score' => 93.1
    ];
    $userTest = UserTest::factory()->create($dataUserTest);

    // Visszakeressük az user Tesztet
    $userTest = DB::table('user_tests')
        ->where('id', $userTest->id)
        ->first();

    // A megtalált user teszt userId-je megegyezik a új userId-jével        
    $this->assertEquals($user->id, $userTest->userId);
}

}    


    


