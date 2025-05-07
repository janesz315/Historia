# Kézi tesztelés (request.rest):

```js
@protocol = http://
@hostname = localhost
@port = 8000
@host = {{protocol}}{{hostname}}:{{port}}


 ----------------- login -------------------
### login
# @name login
POST {{host}}/api/users/login 
Accept: application/json
Content-Type: application/json

{
    "email": "test@example.com",
    "password": "123"
}

###
@token = {{login.response.body.user.token}}

### logout
POST {{host}}/api/users/logout 
Accept: application/json
Content-Type: application/json
Authorization: Bearer {{token}}

### get users
get {{host}}/api/users
Accept: application/json
Authorization: Bearer {{token}}

### get user by Id
get {{host}}/api/users/1
Accept: application/json
Authorization: Bearer {{token}}

### post user
post {{host}}/api/users
Accept: application/json
Content-Type: application/json
Authorization: Bearer {{token}}

{
    "name": "test6",
    "roleId": 2,
    "email": "test6@example.com",
    "password": "123"
}

### delete user by id
delete {{host}}/api/users/3
Accept: application/json
Authorization: Bearer {{token}}

### patch user by id
patch {{host}}/api/users/10
Accept: application/json
Content-Type: application/json
Authorization: Bearer {{token}}

{
    "email": "kaaaaab@gmail.com",
    "password" : "12345"
}

# ----------------- login -------------------
```

- Login: POST metódus → bejelentkezés
    - A {{host}} változó a szerver URL-jét tartalmazza (http://localhost:8000)
    - JSON-ként küldi az emailt és a jelszót
    - A @name login címke később hivatkozási pont lesz
- Token mentése: 
    - Ez egy változó-definíció: Elmenti a login válaszából a token értéket. A {{token}} változó későbbi kérésekben Authorization fejlécbe kerül, így nem kell minden kérésnél manuálisan megadni.


- Logout: A felhasználó kijelentkeztetése
    - A Bearer token hitelesítést használja a fejlécben
    - A szerver törli az adott hozzáférési tokent
- Felhasználók lekérdezése: Lekérdezi az összes felhasználót
    - Védett tartalom
-  Felhasználó lekérése ID alapján: Lekér egy konkrét felhasználót ID alapján (id=1); Védett tartalom
- Új felhasználó létrehozása: A roleId meghatározza, milyen szerepkört kap, JSON formában küldi a felhasználó adatait. Védett tartalom.
-  Felhasználó törlése ID alapján: Törli a beírt ID-jű Usert. Védett tartalom.
- Felhasználó frissítése (részlegesen): A PATCH csak részlegesen frissít. Védett tartalom.



# A backend tesztelés:
- A teszteléskor egy különálló, erre a célra létrehozott adatbázisban dolgoztunk. Ehhez a phpunit.xml-ben kellett módosításokat eszközölni.
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
        <directory>tests/Feature</directory>
    </testsuite>
    <testsuite name="Integration">
        <directory>tests/Integration</directory>
    </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_MAINTENANCE_DRIVER" value="file"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_STORE" value="array"/>
        <!-- <env name="DB_CONNECTION" value="sqlite"/> -->
        <!-- <env name="DB_DATABASE" value=":memory:"/> -->
        <env name="MAIL_MAILER" value="array"/>
        <env name="PULSE_ENABLED" value="false"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

- az `env name="APP_ENV" value="testing"/` sor biztosítja azt, hogy a tesztelési környezet a .env.testing fájlból fusson, ahol a teszt adatbázis van beépítve. A teszt adatbázis feltöltése úgy zajlik, hogy a .env-ben ideiglenesen átírjuk a DB_DATABASE változót a teszt adatbázis nevére.


## Unit tesztek:
- A unit teszt célja, hogy egyetlen funkciót, osztályt vagy metódust elszigetelten teszteljen — azaz függetlenül más moduloktól vagy adatbázistól (bár Laravelben sokszor keverednek integrációs elemekkel is).
```php
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
```
- test_database_creation_and_tables_exists(): Ellenőrzi, hogy az adatbázis és a szükséges táblák léteznek-e.
    1. Ellenőrzi, hogy a tényleges adatbázis kapcsolatban szereplő név megegyezik a .env fájlban megadottal.
    2. Schema::hasTable() segítségével ellenőrzi, hogy a tábla valóban létezik-e az adatbázisban.

```php
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
```
- test_questions_table_structure(): Ellenőrzi a questions tábla szerkezetét (oszlopnevek és típusok), valamint hogy van-e elsődleges kulcs.
    1. A mezők típusát ellenőrizzük. Itt a kulcs az elvárt mezőnév, az érték pedig a mező típusa.
    2. Ezután egy ciklusban ellenőrzi, hogy létezik-e a mező, és megfelelő-e a típusa (Schema::getColumnType()).
    3. SQL-lekérdezéssel ellenőrzi, hogy van-e elsődleges kulcs az id mezőn.

```php
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
```

- test_questiontypes_categories_questions_relationships(): Ellenőrzi, hogy a questions tábla idegen kulcsai valóban kapcsolódnak a question_types és categories táblákhoz. Ezen kívül készít egy kérdést, hogy megnézze, működik-e a kapcsolat.
    1. Idegen kulcsok lekérdezése information_schema.KEY_COLUMN_USAGE-ból: SQL-lekérdezéssel ellenőrzi, hogy az questionTypeId és categoryId valóban idegen kulcsok, és hogy hova mutatnak.
    2. Factory (a tábla alapértelmezett állapota) segítségével létrehoz egy QuestionType és Category rekordot.
    3. Létrehoz egy kérdést az előzőekre hivatkozva.
    4. Ellenőrzi, hogy a kapcsolatok megfelelően jöttek-e létre

- Tehát láthattunk példát a táblák és adatbázis meglétének ellenőrzésére, a tábla mezőinek tulajdonságainak helyességére és két vagy több tábla közötti kapcsolatra is.

- A felhasználóval kapcsolatos teszteket a UserTest.php-ben írjuk le.

- Mi a DatabaseTransactions? A DatabaseTransactions trait minden teszt esetén tranzakcióba zárja az adatbázisműveleteket, és automatikusan rollbackeli őket a teszt végén. Ez biztosítja, hogy az adatbázis minden teszt után tiszta állapotban maradjon.

```php
public function test_check_if_user_insert_into_db(): void
    {
        $userResponse = [
            'name' => 'roger',
            'value' => 1,
        ];
        $this->assertEquals(1, $userResponse['value']);
        $this->assertArrayHasKey('name', $userResponse);
    }

```
- test_check_if_user_insert_into_db(): Ez egy alap logikai teszt, ami nem tesz valódi adatbázis műveletet. Inkább arra jó, hogy kipróbáljuk, hogy a tesztelés működik-e. A value mező értékét, és a kulcsok meglétét ellenőrzi.

```php
public function test_check_if_users_getting_fetched_with_id(): void
    {
        $response = DB::table("users")->find(1);
        // $response = User::find(3);
        // dd($response);
        // dd($response->id);
        //Adott mező értékének ellenőrzése
        $this->assertEquals(1, $response->id);
        
        //A rekordok számának ellenőrzése
        $response = DB::table("users")->get();
        
        // dd($response);
        $this->assertCount(1, $response);
        // dd($response);

        //A rekordok száma > mint 0
        $this->assertGreaterThan(0, count($response));
    }

```
- test_check_if_users_getting_fetched_with_id: Ellenőrzi, hogy az id mező valóban 1, illetve hogy az adatbázisban van legalább egy rekord.

```php
 public function test_the_presence_of_the_given_user_in_the_database()
    {
        $this->assertTrue(DB::table('users')->where('name', 'test')->exists());
    }

```
- test_the_presence_of_the_given_user_in_the_database: Ellenőrzi, hogy van-e egy test nevű user az adatbázisban. Ez is egy külső függőségű teszt, hiszen test nevű usernek már léteznie kell.

```php
public function test_does_the_user_table_contain_all_fields()
    {
        //mezők megvannak-e
        $columns = ['id', 'name', 'roleId', 'email', 'password'];
        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('users', $column), "A '$column' oszlop nem található a 'users' táblában.");
        }
    }
```

- test_does_the_user_table_contain_all_fields: Ez egy séma validáló teszt, ami ellenőrzi, hogy a users táblában jelen vannak-e a várt mezők.

## Feature tesztek
- A feature teszt (vagy „funkcionális teszt”) azt vizsgálja, hogy egy adott funkció hogyan működik a rendszer egészén belül – az adatbázistól a middleware-eken át a view-ig.

```php
 public function test_user_can_take_a_quiz()
    {
        // Hozzáadunk egy kérdést
        $question = Question::factory()->create();

        // Ellenőrizzük, hogy a kérdés sikeresen bekerült az adatbázisba
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question' => $question->question,  // Ellenőrizzük, hogy a kérdés szövege megegyezik
        ]);

        // Hozzáadunk egy választ a kérdéshez
        $answer = Answer::factory()->create([
            'questionId' => $question->id,  // Kérdéshez kapcsoljuk
            'rightAnswer' => 1,  // Helyes válasz
        ]);

        // Ellenőrizzük, hogy a válasz sikeresen bekerült az adatbázisba
        $this->assertDatabaseHas('answers', [
            'questionId' => $question->id,
            'rightAnswer' => 1,
        ]);

        // Létrehozzuk a szerepkört és a felhasználót
        $role = Role::factory()->create(['role' => 'guest']);
        $user = User::factory()->create(['roleId' => $role->id]);

        // Létrehozzuk a user_test rekordot
        $userTest = UserTest::factory()->create([
            'userId' => $user->id,  // A felhasználó azonosítója
        ]);

        // Ellenőrizzük, hogy a teszt rekordja sikeresen létrejött az adatbázisban
        $this->assertDatabaseHas('user_tests', [
            'userId' => $user->id,
            'testName' => $userTest->testName,
            'score' => $userTest->score,
        ]);
    }

```
- test_user_can_take_a_quiz: Ez a teszt gyakorlatilag lépésről lépésre felépíti a kvíz kitöltéséhez szükséges alapokat, és minden ponton ellenőrzést is végez.
    1. Kérdés létrehozása és ellenőrzése: Létrehoz egy kérdést a factory segítségével, majd ellenőrzi, hogy az bekerült-e az adatbázisba.
    2.  Válasz létrehozása és ellenőrzése: Létrehoz egy helyes választ, majd ellenőrzi, hogy az kapcsolódik a kérdéshez, és mentésre került.
    3. Felhasználó és szerepkör létrehozása: Létrehoz egy „vendég” szerepkört, majd egy hozzá tartozó felhasználót.
    4.  UserTest létrehozása és validálása: Létrehoz egy user_tests rekordot, amely elvileg egy konkrét tesztkísérletet reprezentál a felhasználó részéről.

- Minden táblához készítettünk egy olyan tesztet, amely teszteli azt, hogy az új rekord, amit létrehozunk, megjelenik-e az összes rekord között.
```php

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

```
- test_the_get_categories_table_all_record_example: teszteli, hogy az összes kategória lekérésekor visszajön-e az új rekord
    1. Tesztadat létrehozása: Ez egy valódi rekordot hoz létre az adatbázisban.
    2.  Lekérés az API-n keresztül: Ez elküld egy HTTP GET kérést az /api/categories végpontra.
    3.  Ellenőrzések (Assertions): Ez azt vizsgálja, hogy a visszakapott válasz tartalmazza-e a megadott szövegeket.
        - Státuszkód ellenőrzése: Ez biztosítja, hogy a kérés sikeres volt, tehát nem 404 vagy 500 stb.
    4. Adatbázisellenőrzés: Ez azt nézi meg, hogy a categories tábla tartalmaz-e egy olyan sort, ahol a témakör neve `xxx` és a szintje emelt.


``` php
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
```

- login() privát segédfüggvény: Központosított bejelentkezési logika. Egyetlen hívással kaphatunk egy Sanctum token-t vissza a megadott user hitelesítésével.
    - Küld egy POST kérést a /api/users/login végpontra.
    - Kéri a token-t a válaszból.
    - Visszaadja a token-t a teszteléshez.

- test_login: Teszteli, hogy egy újonnan létrehozott felhasználó sikeresen be tud-e jelentkezni, és érvényes tokennel eléri-e a védett végpontot.
    1. User létrehozása
    2. Bejelentkezés API-n keresztül
    3. Státuszkód és token ellenőrzés
    4. Védett API végpont elérése
    5. Sikeres válasz ellenőrzése

- test_create_user: Azt ellenőrzi, hogy admin tokennel új felhasználót lehessen létrehozni az API-n keresztül, és az valóban bekerül-e az adatbázisba.
    1. Login segédfüggvénnyel: Ez bejelentkezteti az alapértelmezett test@example.com usert.
    2. POST kérés az /api/users végpontra
    3. Sikeres válasz ellenőrzése
    4. Adatbázis ellenőrzés
    5. Biztonság kedvéért lekérdezés is

```php
<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryQuestionsWithTypesAndAnswersTimeTest extends TestCase
{
    private $startTime;
    private $endTime;
    private $responseTime;

    /**
     * A basic test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
    }
    public function test_the_querygetQuestionsWithTypesAndAnswers_response_time(): void
    {
        $this->startTime = microtime(true);
        $response = $this->get('/api/getQuestionsWithTypesAndAnswers');
        $this->endTime = microtime(true);

        $response->assertStatus(200);

        $this->responseTime = round(($this->endTime - $this->startTime) * 1000, 2);
        $this->assertLessThan(200, $this->responseTime);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        echo PHP_EOL . "\tA válaszidő (getQuestionsWithTypesAndAnswers): {$this->responseTime} ms";
    }
}


```
- Ez egy speciális típusú feature teszt: teljesítményteszt (response time mérés) egy API végpontra.

- Privát property-k: Ezek a változók tárolják majd a teszt futása alatti időmérést.

- test_the_querygetQuestionsWithTypesAndAnswers_response_time: Ellenőrzi, hogy a /api/getQuestionsWithTypesAndAnswers végpont válasza 200ms alatt érkezik meg.
    1. Start idő rögzítése: Indít egy stopperórát (microtime(true))
    2. API hívás: Küld egy GET kérést az API-hoz.
    3. End idő rögzítése: Leállítja az órát
    4. HTTP státusz ellenőrzése: 200-nak kell lennie
    5. Válaszidő kiszámítása: 
        - Átváltás milliszekundumba (ezért szorzunk 1000-del). 
        - Kerekítés 2 tizedesjegyre.
    6. Teljesítmény ellenőrzés: A teszt csak akkor sikeres, ha a válaszidő kevesebb mint 200ms.

- teardown: Minden teszt után lefut és kiírja konzolra a lemért válaszidőt.

## Integrációs tesztek
- Az integrációs tesztek célja, hogy ellenőrizzék:
     különböző komponensek (adatbázis, backend, API, külső szolgáltatások, stb.) helyesen működnek együtt.
- Jöjjön egy példa rá:
```php
protected function setUp(): void
    {
        parent::setUp();
   
        // Kikapcsoljuk az auth middleware-t tesztnél
        $this->withoutMiddleware();
    }
    public function test_questions_http(): void
    {

        $user = \App\Models\User::factory()->create([
            'roleId' => 1
        ]);
    
        $this->actingAs($user);
    
        $response = $this->getJson('/api/questions');
    
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);
   

    }
```
- setUp: Ez automatikusan lefut minden teszt előtt.
    - A parent::setUp() meghívása biztosítja, hogy az Illuminate\Foundation\Testing\TestCase alapbeállításai érvényesüljenek.
    - A withoutMiddleware() metódus kikapcsolja az összes middleware-t – így nem kell tényleges hitelesítést használni a teszt során.

- test_questions_http: 
    1.  Felhasználó létrehozása (Factory használatával)
        - A User::factory()->create() létrehoz egy új felhasználót az adatbázisban.
    2. Felhasználó bejelentkeztetése (actingAs):
        - Laravel módja annak, hogy szimuláljuk a bejelentkezett állapotot.
        - Ezáltal a kérések úgy futnak, mintha a megadott felhasználó lenne bejelentkezve – anélkül, hogy valódi auth tokenre lenne szükség.
    3. API-hívás elküldése: GET kérés JSON választ várva.
    4.  Válasz ellenőrzése: A válaszkód legyen 200, azaz sikeres.
        - A válasz tartalmazza a message és data mezőket.
        - A data mezőben általában a kérdések listája szerepel.

- És íme az eredmény, amit a 'php artisan test' kiadásakor kapunk:

```cmd
$ php artisan test

        Adatbázis -> DB_DATABASE=testing-historia | adatbázis: testing-historia
   PASS  Tests\Unit\DatabaseTest
  ✓ example                                                                                                                                                                   0.21s  
  ✓ database creation and tables exists                                                                                                                                       0.03s  
  ✓ roles table structure                                                                                                                                                     0.06s  
  ✓ categories table structure                                                                                                                                                0.06s  
  ✓ sources table structure                                                                                                                                                   0.10s  
  ✓ question types table structure                                                                                                                                            0.05s  
  ✓ questions table structure                                                                                                                                                 0.07s  
  ✓ answers table structure                                                                                                                                                   0.08s  
  ✓ test questions table structure                                                                                                                                            0.08s  
  ✓ user tests table structure                                                                                                                                                0.08s  
  ✓ roles users relationships                                                                                                                                                 0.09s  
  ✓ categories sources relationships                                                                                                                                          0.03s  
  ✓ questiontypes categories questions relationships                                                                                                                          0.03s  
  ✓ questions answers relationships                                                                                                                                           0.03s  
  ✓ questions answers user tests test questions relationships                                                                                                                 0.05s  
  ✓ users user tests relationships                                                                                                                                            0.03s  

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                                                                                                                         0.02s  

   PASS  Tests\Unit\UserTest
  ✓ check if user insert into db                                                                                                                                              0.07s  
  ✓ check if users getting fetched with id                                                                                                                                    0.07s  
  ✓ the presence of the given user in the database                                                                                                                            0.03s  
  ✓ does the user table contain all fields                                                                                                                                    0.04s  

   PASS  Tests\Feature\AnswersTest
  ✓ the get answers table all record example                                                                                                                                  0.09s  

   PASS  Tests\Feature\CategoriesTest
  ✓ the get categories table all record example                                                                                                                               0.05s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                                                                             0.06s  
  ✓ the api returns a successful response                                                                                                                                     0.03s  

        A válaszidő (getQuestionsWithTypesAndAnswers): 3.93 ms
   PASS  Tests\Feature\QueryQuestionsWithTypesAndAnswersTimeTest
  ✓ the queryget questions with types and answers response time                                                                                                               0.04s  

   PASS  Tests\Feature\QuestionTypesTest
  ✓ the get question types table all record example                                                                                                                           0.03s  

   PASS  Tests\Feature\QuestionsTest
  ✓ the get questions table all record example                                                                                                                                0.03s  

   PASS  Tests\Feature\QuizFeatureTest
  ✓ user can take a quiz                                                                                                                                                      0.04s  

   PASS  Tests\Feature\RolesTest
  ✓ the get roles table all record example                                                                                                                                    0.05s  

   PASS  Tests\Feature\SourcesTest
  ✓ the get sources table all record example                                                                                                                                  0.06s  

   PASS  Tests\Feature\TestQuestionsTest
  ✓ the get test questions table all record example                                                                                                                           0.04s  

   PASS  Tests\Feature\UserTest
  ✓ login                                                                                                                                                                     0.22s  
  ✓ create user                                                                                                                                                               0.47s  

   PASS  Tests\Feature\UserTestsTest
  ✓ the get user tests table all record example                                                                                                                               0.04s  

   PASS  Tests\Feature\IntegrationAnswerTest
  ✓ answers http                                                                                                                                                              0.05s  

   PASS  Tests\Feature\IntegrationCategoryTest
  ✓ categories http                                                                                                                                                           0.07s  

   PASS  Tests\Feature\IntegrationQuestionTest
  ✓ questions http                                                                                                                                                            0.05s  

   PASS  Tests\Feature\IntegrationQuestionTypeTest
  ✓ questiontypes http                                                                                                                                                        0.04s  

   PASS  Tests\Feature\IntegrationRoleTest
  ✓ roles http                                                                                                                                                                0.05s  

   PASS  Tests\Feature\IntegrationSourceTest
  ✓ sources http                                                                                                                                                              0.06s  

   PASS  Tests\Feature\IntegrationTestQuestionTest
  ✓ testquestions http                                                                                                                                                        0.04s      

   PASS  Tests\Feature\IntegrationUserTestTest
  ✓ usertests http                                                                                                                                                            0.04s      

  Tests:    43 passed (199 assertions)
  Duration: 3.35s
```

# A frontend tesztelése

## Playwright E2E Tesztek Elemzése

Ez a kód Playwright end-to-end (E2E) teszteket tartalmaz és a frontend/e2e/vue.spec.js-ben találhatóak. A tesztek különböző felhasználói interakciókat szimulálnak, ellenőrizve az alkalmazás funkcionalitását.

### Importok

* `import { test, expect } from '@playwright/test';`: Importálja a `test` és `expect` függvényeket a Playwright tesztelő keretrendszerből. A `test` függvény definiál egy tesztesetet, az `expect` pedig állítások (assertions) megfogalmazására szolgál.

### Tesztesetek

1.  **`test('visits the app root url', async ({ page }) => { ... });`**:
    * Ez a teszt ellenőrzi, hogy az alkalmazás főoldala (`/`) sikeresen betöltődik-e.
    * `await page.goto('/');`: Navigál a megadott URL-re.
    * `await expect(page.locator('h1')).toHaveText('Kezdőlap');`: Ellenőrzi, hogy a főoldalon egy `h1` elem található-e, melynek szövege "Kezdőlap".

2.  **`test('Homepage has title', async ({ page }) => { ... });`**:
    * Ez a teszt ellenőrzi a főoldal `<title>` tagjának tartalmát.
    * `await expect(page).toHaveTitle(/Historia - Kezdőlap/);`: Ellenőrzi, hogy az oldal címe tartalmazza-e a "Historia - Kezdőlap" szöveget (reguláris kifejezéssel).

3.  **`test('Vue application loads the homepage', async ({ page }) => { ... });`**:
    * Ez a teszt specifikusan azt ellenőrzi, hogy a Vue alkalmazás betölti-e a főoldalt.
    * `await page.innerText('h1');`: Lekéri a `h1` elem belső szövegét.
    * `expect(title).toBe('Kezdőlap');`: Ellenőrzi, hogy a lekérdezett szöveg "Kezdőlap"-e.

4.  **`test('Login with Admin', async ({ page }) => { ... });`**:
    * Ez a teszt egy admin felhasználó bejelentkezését szimulálja.
    * `await page.goto('/bejelentkezes');`: Navigál a bejelentkezési oldalra.
    * `await page.fill('input#email', 'test@example.com');`: Kitölti az e-mail mezőt.
    * `await page.fill('input#password', '123');`: Kitölti a jelszó mezőt.
    * `await page.click('button:has-text("Bejelentkezés")');`: Rákattint a "Bejelentkezés" gombra.
    * `await expect(page).toHaveURL('/');`: Ellenőrzi, hogy a sikeres bejelentkezés után a felhasználót a főoldalra irányították-e át.
    * `await expect(page.locator('h1')).toHaveText('Kezdőlap');`: Ellenőrzi, hogy a főoldal betöltődött-e.

5.  **`test('Admin categories from the homepage is opening', async ({ page }) => { ... });`**:
    * Ez a teszt ellenőrzi, hogy a főoldalról elérhető admin témakörök oldal megnyílik-e.
    * Az első része megegyezik a bejelentkezési teszttel.
    * `await page.waitForSelector('a#categoriesAdmin', { state: 'visible' });`: Vár addig, amíg az `a` tag, melynek ID-ja "categoriesAdmin", láthatóvá válik.
    * `await page.click('a#categoriesAdmin');`: Rákattint a fenti elemre (feltételezve, hogy ez a témakörök admin oldalra vezető link).
    * `await expect(page).toHaveURL('/temakorokAdmin');`: Ellenőrzi, hogy az URL megváltozott-e a témakörök admin oldalára.
    * `await expect(page.innerText('h1')).toBe('Témakörök kezelése');`: Ellenőrzi, hogy a témakörök admin oldal `h1` elemének szövege "Témakörök kezelése".

6.  **`test('Admin categories from the navigation bar is opening', async ({ page }) => { ... });`**:
    * Ez a teszt hasonló az előzőhöz, de feltételezi, hogy a témakörök admin oldal a navigációs bárból is elérhető.
    * A bejelentkezési lépések megegyeznek.
    * `await page.waitForSelector('a.nav-link#categoriesAdmin', { state: 'visible' });`: Vár egy `a` tagre, melynek CSS osztálya "nav-link" és ID-ja "categoriesAdmin".
    * A kattintás és az ellenőrzések megegyeznek az előző teszttel.

7.  **`test('Create a question', async ({ page }) => { ... });`**:
    * Ez a teszt egy új kérdés létrehozását szimulálja.
    * A bejelentkezési lépések megegyeznek.
    * Navigál a kérdésbank oldalra (`/kerdesek`).
    * Ellenőrzi, hogy a "Kérdések kezelése" és "Témakörök" címek megtalálhatók-e az oldalon.
    * Kattint egy adott témakörre ("A középkori város").
    * Kattint a "Új kérdés létrehozása" gombra.
    * Kitölti a kérdés szövegét ("Mikor volt az Aranybulla?").
    * Kiválasztja a "Évszám" opciót a kérdéstípus legördülő menüből.
    * Rákattint a "Mentés" gombra.
    * Ellenőrzi, hogy a létrehozott kérdés szövege megjelenik-e a táblázatban.

8.  **`test(' Update a question', async ({ page }) => { ... });`**:
    * Ez a teszt egy meglévő kérdés frissítését szimulálja.
    * A bejelentkezési és a kérdésbankra navigálási lépések megegyeznek.
    * Megkeresi a "Mikor volt az Aranybulla?" kérdést tartalmazó sort.
    * Rákattint a sorban lévő "Módosítás" gombra.
    * Vár a módosító modális ablak megjelenésére.
    * Módosítja a kérdés szövegét "Mikor adták ki az Aranybullát?"-ra.
    * Rákattint a modális ablakon belüli "Frissítés" gombra.
    * Ellenőrzi, hogy a kérdés szövege megváltozott-e a táblázatban.

9.  **`test('Create and delete a question', async ({ page }) => { ... });`**:
    * Ez a teszt egy kérdés létrehozását és azonnali törlését szimulálja.
    * A létrehozási lépések megegyeznek a "Create a question" teszttel.
    * Megkeresi a létrehozott kérdést tartalmazó sort.
    * Rákattint a sorban lévő "Törlés" gombra.
    * Vár a törlő modális ablak megjelenésére.
    * Rákattint a modális ablakon belüli "Igen" gombra a törlés megerősítéséhez.
    * Ellenőrzi, hogy a törölt kérdés nem látható a listában.

10. **`test('Go to Profile', async ({ page }) => { ... });`**:
    * Ez a teszt a felhasználói profil oldal megnyitását ellenőrzi.
    * A bejelentkezési lépések megegyeznek.
    * Rákattint a felhasználói menüt megnyitó elemre ("Iskola" feliratú, `userDropdown` ID-jú `a` tag).
    * Vár a legördülő menü láthatóságára.
    * Rákattint a "Profil" menüpontra.
    * Ellenőrzi, hogy a profil oldal `h1` elemének szövege "Felhasználói Profil".

11. **`test('Login and logout', async ({ page }) => { ... });`**:
    * Ez a teszt a bejelentkezést és kijelentkezést ellenőrzi.
    * A bejelentkezési lépések megegyeznek.
    * Rákattint a felhasználói menüt megnyitó elemre.
    * Vár a legördülő menü láthatóságára.
    * Rákattint a "Kijelentkezés" menüpontra.
    * Ellenőrzi, hogy a kijelentkezés után egy adott szöveg ("Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!") jelenik meg.

12. **`test('Register and Login', async ({ page }) => { ... });`**:
    * Ez a teszt egy új felhasználó regisztrációját és az ezt követő bejelentkezést ellenőrzi.
    * Navigál a regisztrációs oldalra (`/regisztracio`).
    * Kitölti a regisztrációs űrlapot felhasználónévvel, e-mail címmel és jelszóval.
    * Rákattint a "Regisztrálás" gombra.
    * Ellenőrzi, hogy a sikeres regisztráció után a bejelentkezési oldalra irányították-e át a felhasználót.
    * Kitölti a bejelentkezési űrlapot az újonnan regisztrált adatokkal.
    * Rákattint a "Bejelentkezés" gombra.
    * Ellenőrzi, hogy a sikeres bejelentkezés után a felhasználót a főoldalra irányították-e át.

13. **`test('Register and Login and delete the account', async ({ page }) => { ... });`**:
    * Ez a teszt egy új felhasználó regisztrációját, bejelentkezését és a fiók törlését ellenőrzi.
    * A regisztrációs és bejelentkezési lépések megegyeznek az előző teszttel.
    * Navigál a profil oldalra.
    * Figyeli a "dialog" eseményt (ami a fiók törlésének megerősítő ablakát jelzi).
    * Rákattint a "Fiók törlése" gombra, és elfogadja a megerősítő ablakot.
    * Ellenőrzi, hogy a fiók törlése után a regisztrációs oldalra irányították-e át a felhasználót.

14. **`test('Go to Profile and change the username', async ({ page }) => { ... });`**:
    * Ez a teszt a felhasználói profil oldalon a felhasználónév módosítását ellenőrzi.
    * A bejelentkezési és a profil oldalra navigálási lépések megegyeznek.
    * Rákattint a felhasználónév melletti "Módosítás" gombra.
    * Vár a felhasználónév szerkesztésére szolgáló input mező láthatóságára.
    * Kitörli a jelenlegi felhasználónevet és beír egy újat ("test3").
    * Rákattint a "Mentés" gombra.
    * Ellenőrzi, hogy az új felhasználónév megjelenik-e a profil oldalon.

### Tesztek Elemzése (Vitest és Vue Test Utils)

Ez a dokumentum a megadott JavaScript fájlokban található Vitest egységteszteket elemzi, melyek Vue.js komponensek működését hivatottak ellenőrizni a Vue Test Utils könyvtár segítségével.

---

### `Login.spec.js` elemzése

Ez a fájl a `Login.vue` nevű Vue.js komponens egységtesztjeit tartalmazza. A bejelentkezési funkcionalitást vizsgálja.

**Importok:**

* `import { describe, it, expect, vi } from 'vitest';`: Importálja a Vitest tesztelő keretrendszer alapvető funkcióit (`describe` a tesztcsoportokhoz, `it` az egyes tesztekhez, `expect` az állításokhoz, `vi` a mockoláshoz).
* `import { mount } from '@vue/test-utils';`: Importálja a `mount` függvényt a Vue Test Utils-ból, amely komponensek DOM-ba történő csatlakoztatására szolgál a teszteléshez.
* `import Login from '@/components/Auth/Login.vue';`: Importálja a tesztelni kívánt `Login` komponenst.
* `import { useAuthStore } from '@/stores/useAuthStore';`: Importálja a Vuex vagy Pinia auth store hook-ját.
* `import axios from 'axios';`: Importálja az `axios` HTTP klienst.
* `import { createRouter, createWebHistory } from 'vue-router';`: Importálja a Vue Router funkcióit a navigáció mockolásához.
* `import { BASE_URL } from "@/helpers/baseUrls.js";`: Importál egy konstansot, valószínűleg az API alap URL-jét.

**Mockolások (`vi.mock(...)`):**

* A `axios` modul teljes egészében mockolásra kerül, így a tesztek nem fognak valódi HTTP kéréseket indítani.
* A `@/stores/useAuthStore` modul is mockolásra kerül. A `useAuthStore` hook egy mockolt implementációt ad vissza, amely tartalmazza a `setId`, `setUser`, `setToken` és `setRoleId` mockolt függvényeit. Ez biztosítja, hogy a tesztek ne függjenek a valódi store működésétől.
* A `vue-router` modul is mockolásra kerül.

**Mock Router:**

* Létrehoz egy `mockRouter` objektumot, amelynek van egy `push` nevű mockolt függvénye. Ezt a komponenst csatlakoztató `mount` függvény `global.plugins` opciójában használják, hogy a komponensben használt `$router.push()` hívásokat megfigyelhessék.

**Router Példány:**

* Létrehoz egy Vue Router példányt üres útvonalakkal. Ezt a komponenst csatlakoztató `mount` függvény `global.plugins` opciójában használják.

**Komponens Csatlakoztatása (globális mockokkal):**

* A `wrapper` konstans a `Login` komponens egy csatlakoztatott példányát tárolja, globálisan regisztrálva a `mockRouter` plugint.

**Tesztcsoport (`describe('Login', () => { ... });`)**:

* **`it('should render the component', () => { ... });`**: Ellenőrzi, hogy a komponens sikeresen renderelődik-e, létezik-e a DOM-ban, és hogy a `.login-title` CSS osztályú elem szövege "Bejelentkezés".
* **`it('should initialize with empty email and password', () => { ... });`**: Ellenőrzi, hogy a komponens adatterületei (`user.email`, `user.password`, `errorMessage`, `loading`) a kezdeti állapotukban vannak-e (üres stringek, `null`, `false`).
* **`it('should display error message if email or password is empty on submit', async () => { ... });`**: Szimulálja az űrlap beküldését üres e-mail és jelszó mezőkkel. Ellenőrzi, hogy a `errorMessage` a várt hibaüzenetre változik-e, a `loading` marad-e `false`, és hogy az `axios.post` függvény nem lett-e meghívva.
* **`it('should display "Helytelen bejelentkezési adatok!" message for specific API error', async () => { ... });`**: Mockolja az `axios.post` sikeres válaszát, de úgy, hogy az nem tartalmaz érvényes felhasználói adatokat. Kitölti az e-mail és jelszó mezőket, beküldi az űrlapot, és ellenőrzi, hogy a `errorMessage` a "Helytelen bejelentkezési adatok!" üzenetre változik-e, és a `loading` továbbra is `false`.

---

### `Modal.spec.js` elemzése

Ez a fájl a `Modal.vue` nevű Vue.js komponens egységtesztjeit tartalmazza. Egy általános célú modális ablak komponenst vizsgál.

**Importok:**

* `import { describe, it, expect, vi } from 'vitest';`: Importálja a Vitest tesztelő keretrendszer alapvető funkcióit.
* `import { mount } from '@vue/test-utils';`: Importálja a `mount` függvényt a Vue Test Utils-ból.
* `import Modal from '@/components/Modals/Modal.vue';`: Importálja a tesztelni kívánt `Modal` komponenst.

**Tesztcsoport (`describe('Modal', () => { ... });`)**:

* **`it('should render title correctly', () => { ... });`**: Ellenőrzi, hogy a komponens a `title` propként átadott értéket helyesen rendereli-e a `.modal-title` CSS osztályú elemben.
* **`it('should render yes button and emit yesEvent on click', async () => { ... });`**: Ellenőrzi, hogy a `yes` propként átadott szöveg megjelenik-e a `.btn-primary` CSS osztályú gombon, és hogy a gomb kattintására kibocsátja-e a `yesEvent` eseményt.
* **`it('should render no button when no prop is provided', () => { ... });`**: Ellenőrzi, hogy a `no` propként átadott szöveg megjelenik-e a `.btn-secondary` CSS osztályú gombon.
* **`it('should apply size class based on size prop', () => { ... });`**: Ellenőrzi, hogy a `size` prop alapján a `.modal-dialog` CSS osztályú elem tartalmazza-e a megfelelő `modal-${size}` osztályt.
* **`it('should render slot content', () => { ... });`**: Ellenőrzi, hogy a `<slot>`-ba átadott tartalom megjelenik-e a `.modal-body` CSS osztályú elemben.

---

### `OperationsCrudUserTests.spec.js` elemzése

Ez a fájl az `OperationsCrudUserTests.vue` nevű Vue.js komponenst teszteli, amely valószínűleg felhasználói tesztek létrehozásához, törléséhez és frissítéséhez kapcsolódó gombokat jelenít meg.

**Importok:**

* `import { describe, it, expect, vi } from 'vitest';`: Importálja a Vitest tesztelő keretrendszer alapvető funkcióit.
* `import { mount } from '@vue/test-utils';`: Importálja a `mount` függvényt a Vue Test Utils-ból.
* `import OperationsCrudUserTests from '@/components/Modals/OperationsCrudUserTests.vue';`: Importálja a tesztelni kívánt komponenst.

**Tesztcsoport (`describe('OperationsCrudUserTests', () => { ... });`)**:

* **`it('should render create button when userTest prop is not provided', () => { ... });`**: Ellenőrzi, hogy ha a `userTest` prop nincs megadva, akkor a komponens egy "Új teszt készítése" szöveget tartalmazó gombot renderel.
* **`it('should emit onClickCreateButton event when create button is clicked', async () => { ... });`**: Ellenőrzi, hogy a létrehozás gomb kattintására a komponens kibocsátja-e az `onClickCreateButton` eseményt.
* **`it('should render delete and update buttons when userTest prop is provided', () => { ... });`**: Ellenőrzi, hogy ha a `userTest` prop meg van adva (ami valószínűleg egy felhasználói teszt objektum), akkor a komponens renderel-e egy törlés (`.btn-outline-danger`) és egy frissítés (`.btn-outline-primary`) gombot.
* **`it('should emit onClickDeleteButton event with userTest object when delete button is clicked', async () => { ... });`**: Ellenőrzi, hogy a törlés gomb kattintására a komponens kibocsátja-e az `onClickDeleteButton` eseményt, és hogy az eseményhez tartozó payload a `userTest` objektum.
* **`it('should emit onClickUpdateButton event with userTest object when update button is clicked', async () => { ... });`**: Ellenőrzi, hogy a frissítés gomb kattintására a komponens kibocsátja-e az `onClickUpdateButton` eseményt, és hogy az eseményhez tartozó payload a `userTest` objektum.

---

### `Profile.spec.js` elemzése

Ez a fájl a `Profile.vue` nevű Vue.js komponens egységtesztjeit tartalmazza, amely egy felhasználói profil oldal megjelenítéséért és szerkesztéséért felelős.

**Importok:**

* `import { describe, it, expect, vi } from 'vitest';`: Importálja a Vitest tesztelő keretrendszer alapvető funkcióit.
* `import { mount } from '@vue/test-utils';`: Importálja a `mount` függvényt a Vue Test Utils-ból.
* `import Profile from '@/components/Auth/Profile.vue';`: Importálja a tesztelni kívánt `Profile` komponenst.
* `import { useAuthStore } from '@/stores/useAuthStore';`: Importálja az auth store hook-ját.
* `import { BASE_URL } from "@/helpers/baseUrls.js";`: Importálja az API alap URL-jét.
* `import axios from 'axios';`: Importálja az `axios` HTTP klienst.
* `import { createRouter, createWebHistory } from 'vue-router';`: Importálja a Vue Router funkcióit.

**Mockolások (`vi.mock(...)`):**

* Az `axios` modul mockolásra kerül a HTTP kérések elkerülése érdekében.
* A `@/stores/useAuthStore` modul is mockolásra kerül. A `useAuthStore` hook egy mockolt implementációt ad vissza, amely tartalmaz egy `id` és `token` tulajdonságot, valamint egy `clearStoredData` mockolt függvényt.

**Mock Router:**

* Létrehoz egy Vue Router példányt üres útvonalakkal.

**Tesztcsoport (`describe('Profile', () => { ... });`)**:

* **`it('should initialize with an empty user object', () => { ... });`**: Ellenőrzi, hogy a komponens kezdetben egy üres objektumot (`{}`) állít-e be a `user` adatterületre.
* **`it('should fetch user data on created hook and update user object', async () => { ... });`**: Mockolja az `axios.get` sikeres válaszát egy felhasználói adatokkal. Csatlakoztatja a komponenst, és a `$nextTick()` segítségével vár az aszinkron hívás befejezésére. Ellenőrzi, hogy az `axios.get` a várt URL-lel és authorization headerrel lett-e meghívva, és hogy a `user` adatterület a mockolt felhasználói adatokkal frissült-e.
* **`it('should log error if fetching user data fails', async () => { ... });`**: Mockolja az `axios.get` sikertelen válaszát egy hibával. Csatlakoztatja a komponenst, és egy kis várakozással biztosítja a hiba kezelését. Ellenőrzi, hogy a `console.error` függvény a várt hibaüzenettel és a hibával együtt lett-e meghívva. Visszaállítja a `console.error` eredeti implementációját a `mockRestore()` segítségével.
* **`it('should start editing a field when "Módosítás" button is clicked', async () => { ... });`**: Mockolja a felhasználói adatok lekérését. Csatlakoztatja a komponenst a routerrel. Megkeresi a "Módosítás" szöveget tartalmazó gombot, rákattint, és ellenőrzi, hogy az `isEditingField` adatterület a "name" értékre változott-e, a `updatedField.name` tartalmazza-e a jelenlegi felhasználónevet, megjelenik-e egy szövegbeviteli mező, valamint a "Mentés" és "Mégse" gombok. A felhasználónév a bekezdésben ideiglenesen eltűnik.
* **`it('should cancel editing and reset state when "Mégse" button is clicked', async () => { ... });`**: Hasonló az előző teszthez, de a "Mégse" gomb kattintását szimulálja. Ellenőrzi, hogy az `isEditingField` `null`-ra áll vissza, a `updatedField` kiürül, eltűnik a beviteli mező és a "Mentés"/"Mégse" gombok, újra megjelenik a "Módosítás" gomb, és a felhasználónév ismét látható a bekezdésben.

---

### `UserTestForm.spec.js` elemzése

Ez a fájl a `UserTestForm.vue` nevű Vue.js komponenst teszteli, amely valószínűleg egy űrlapot jelenít meg felhasználói tesztek létrehozásához vagy szerkesztéséhez.

**Importok:**

* `import { describe, it, expect } from 'vitest';`: Importálja a Vitest tesztelő keretrendszer alapvető funkcióit.
* `import { mount } from '@vue/test-utils';`: Importálja a `mount` függvényt a Vue Test Utils-ból.
* `import UserTestForm from '@/components/Forms/UserTestForm.vue';`: Importálja a tesztelni kívánt komponenst.

**Tesztcsoport (`describe('UserTestForm', () => { ... });`)**:

* **`it('should render the input field with the correct label', () => { ... });`**: Csatlakoztatja a komponenst egy üres `itemForm` prop-pal. Ellenőrzi, hogy a "Mi legyen a neve?" címkéjű beviteli mező létezik-e.
* **`it('should bind input value to itemForm.testName', async () => { ... });`**: Csatlakoztatja a komponenst egy kezdeti `itemForm` prop-pal. Megkeresi a beviteli mezőt, beállít egy új értéket, és ellenőrzi, hogy ez az érték kötődik-e a komponens `itemForm.testName` adatterületéhez.
* **`it('should emit saveItem event with itemForm on submit', async () => { ... });`**: Csatlakoztatja a komponenst egy kezdeti `itemForm` prop-pal. Szimulálja az űrlap beküldését. Ellenőrzi, hogy a komponens kibocsátja-e a `saveItem` eseményt, és hogy az eseményhez tartozó payload a `itemForm` objektum.


## A unit teszt futtatásának eredménye:

 DEV  v3.0.7 F:/Informatika/Vizsgaremek/Historia/frontend

stderr | src/components/__tests__/Login.spec.js
[Vue warn]: A plugin must either be a function or an object with an "install" function.

stderr | src/components/__tests__/Profile.spec.js > Profile > should initialize with an empty user object
Error fetching user profile: TypeError: Cannot read properties of undefined (reading 'data')
    at Proxy.created (F:\Informatika\Vizsgaremek\Historia\frontend\src\components\Auth\Profile.vue:122:28)
    at processTicksAndRejections (node:internal/process/task_queues:95:5)

stderr | src/components/__tests__/Login.spec.js > Login > should render the component
[Vue warn]: A plugin must either be a function or an object with an "install" function.

stderr | src/components/__tests__/Profile.spec.js > Profile > should log error if fetching user data fails
Error fetching user profile: Error: Failed to fetch user
    at F:\Informatika\Vizsgaremek\Historia\frontend\src\components\__tests__\Profile.spec.js:44:23
    at file:///F:/Informatika/Vizsgaremek/Historia/frontend/node_modules/@vitest/runner/dist/index.js:174:14
    at file:///F:/Informatika/Vizsgaremek/Historia/frontend/node_modules/@vitest/runner/dist/index.js:558:28
    at file:///F:/Informatika/Vizsgaremek/Historia/frontend/node_modules/@vitest/runner/dist/index.js:61:24
    at new Promise (<anonymous>)
    at runWithTimeout (file:///F:/Informatika/Vizsgaremek/Historia/frontend/node_modules/@vitest/runner/dist/index.js:41:12)
    at runTest (file:///F:/Informatika/Vizsgaremek/Historia/frontend/node_modules/@vitest/runner/dist/index.js:1137:17)
    at processTicksAndRejections (node:internal/process/task_queues:95:5)
    at runSuite (file:///F:/Informatika/Vizsgaremek/Historia/frontend/node_modules/@vitest/runner/dist/index.js:1291:15)
    at runSuite (file:///F:/Informatika/Vizsgaremek/Historia/frontend/node_modules/@vitest/runner/dist/index.js:1291:15)

stderr | src/components/__tests__/Login.spec.js > Login > should initialize with empty email and password
[Vue warn]: A plugin must either be a function or an object with an "install" function.

stderr | src/components/__tests__/Profile.spec.js > Profile > should start editing a field when "Módosítás" button is clicked
[Vue Router warn]: No match found for location with path "/"

stderr | src/components/__tests__/Login.spec.js > Login > should display error message if email or password is empty on submit
[Vue warn]: A plugin must either be a function or an object with an "install" function.

stderr | src/components/__tests__/Login.spec.js > Login > should display "Helytelen bejelentkezési adatok!" message for specific API error
[Vue warn]: A plugin must either be a function or an object with an "install" function.

 ✓ src/components/__tests__/Login.spec.js (4 tests) 67ms
 ✓ src/components/__tests__/Profile.spec.js (5 tests) 143ms
 ✓ src/components/__tests__/OperationsCrudUserTests.spec.js (5 tests) 90ms
 ✓ src/components/__tests__/Modal.spec.js (5 tests) 105ms
 ✓ src/components/__tests__/UserTestForm.spec.js (3 tests) 72ms

 Test Files  5 passed (5)
      Tests  22 passed (22)
   Start at  21:51:37
   Duration  7.10s (transform 728ms, setup 0ms, collect 1.96s, tests 476ms, environment 7.81s, prepare 927ms)


## A playwright teszt futtatása:


$ npx playwright test

Running 42 tests using 2 workers
[chromium] › e2e\vue.spec.js:293:1 › Register and Login and delete the account
Alert üzenet: Biztosan le akarod törölni a fiókodat?
  1) [firefox] › e2e\vue.spec.js:293:1 › Register and Login and delete the account ─────────────────

    Test timeout of 30000ms exceeded.

    Error: page.click: Test timeout of 30000ms exceeded.
    Call log:
      - waiting for locator('button:has-text("Regisztrálás")')
        - locator resolved to <button type="submit" data-v-cb33a4e4="" class="register-button">…</button>
      - attempting click action
        - waiting for element to be visible, enabled and stable
        - element is visible, enabled and stable
        - scrolling into view if needed
        - done scrolling
        - performing click action


      300 |   await page.fill('input[placeholder="Jelszó még egyszer*"]', 'heslo123');
      301 |   // Kattints a bejelentkezés gombra
    > 302 |   await page.click('button:has-text("Regisztrálás")');
          |              ^
      303 |
      304 |   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
      305 |   await expect(page).toHaveURL('/bejelentkezes');
        at F:\Informatika\Vizsgaremek\Historia\frontend\e2e\vue.spec.js:302:14

[webkit] › e2e\vue.spec.js:293:1 › Register and Login and delete the account
Alert üzenet: Biztosan le akarod törölni a fiókodat?
  Slow test file: [webkit] › e2e\vue.spec.js (1.2m)  
  Slow test file: [chromium] › e2e\vue.spec.js (1.1m)
  Consider running tests from slow files in parallel, see https://playwright.dev/docs/test-parallel.
  1 failed
    [firefox] › e2e\vue.spec.js:293:1 › Register and Login and delete the account ──────────────────
  41 passed (2.3m)