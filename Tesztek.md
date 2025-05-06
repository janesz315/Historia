## Kézi tesztelés (request.rest):

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



## A backend tesztelés:
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


### Unit tesztek:
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

### Feature tesztek
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

### Integrációs tesztek
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