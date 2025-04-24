# A feladat leírása

Egy olyan webalkalmazás létrehozása, amely egyrészt, a közép és az emelt szintű érettségire való felkészülést támogatja kidolgozott szóbeli tételekkel és témakörökkel, másrészt a tanulónak lehetősége van tesztelni tudását az érettségire feleletválasztós kérdések segítségével történelem tantárgyból.
Funkciók:
- Kiválaszthatja, hogy milyen szintről szeretne kérdéseket kapni. Ezután választhat a témakörök vagy a tételek közül attól függően, hogy melyiket választotta. Ha a felhasználó szeretné tesztelni teljeskörűen a tudását, akkor megjelölheti azt, hogy az összes témakörből kapjon kérdést. Miután végzett a teszttel, akkor megkapja a pontszámot és az értékelést is. 
- Szerepkörök:
    - A weboldal használata regiszrációhoz kötött. 
    - admin: Ő tölti fel a tartalmakat.
    - user: A felhasználó nem hozhat létre új tartalmakat, böngészhet és a tesztek segítségével tanulhat. Az általa készült tesztek tárolódnak az adatbázisban. 

- Technológiák:
    - Adatbázis: MySQL
    - Backend: Laravel
    - Frontend: Vue + Vite
    - Csoportmunka: Git, GitHub
    - Kommunikáció: Teams
    - Ütemterv: GitHub projekttervező rendszere

- A feladat csoportmunkában készült Jáger Kristóf és Kovács János (csoportvezető) részvételével.    

# Az adatbázis:

## Az adatbázis diagramja

![Diagram](/backend/img/diagram.png)

## Táblák részletes leírása

 - categories (témakörök):
    - id: a témakör azonosítója, int(10), autoIncrement
    - category (témakör): a témakör neve, string(60), notNull
    - level (szint): közép vagy emelt szint, string(15), notNull
    - text (szöveg): maga a témakör tartalma, text, nullable

- sources (források):
    - id: a forrás azonosítója, int(10), autoIncrement
    - categoryId (témakörId): itt kapcsolódunk a témakörhöz, ez adja meg, melyik témakörbe kell,  int(10), autoIncrement
    - sourceLink (forrásLink): maga a hivatkozás, string(255), nullable
    - note (megjegyzés): ha a forrás egy pdf-ben van, akkor itt adjuk meg az oldalszámot például, string(255), nullable

- questions (kérdések):
    - id: a kérdés azonosítója, int(10), autoIncrement
    - question (kérdés): itt találjuk meg a kérdést, text, notNull
    - questionTypeId (kérdésTípusId): ez határozza meg a kérdés típusát, int(10), autoIncrement, notNull
    - categoryId: (témakörId): itt adjuk meg azt, hogy melyik témakörhöz tartozik az azonosító segítségével, int(10), autoIncrement

- answers (válaszok):
    - id: a válasz azonosítója, int(10), autoIncrement
    - answer (válasz): a válasz tartalma, string(255), notNull
    - QuestionsId (KérdésekId): itt adjuk meg a hozzátartozó kérdést az azonosító segítségével, int(10), autoIncrement
    - RightAnswer (helyesVálasz): itt döntjük el a válasz helyességét: 1 - helyes, 0 - helytelen,  tinyInt(1), notNull

- questionTypes (kérdésTípusok):
    - id: a kérdéstípus azonosítója, int(10), autoIncrement
    - questionCategory (kérdésKategória): Ez tartalmazza azt, hogy ez milyen fajta kérdés, string(30), notNull

- users (felhasználók):
    - id: a felhasználó azonosítója, int(10), autoIncrement
    - username (felhasználónév): itt van a felhasználónév, string(25), notNull
    - roleId (szerepkörId): Itt adjuk meg azt, hogy a felhasználó milyen szerepkörben van az azonosító segítségével, int(10), autoIncrement

- roles (szerepkörök):
    - id: a szerepkör azonosítója, int(10), autoIncrement
    - role (szerepkör): itt vannak a szerepkörök, string(10), notNull

- userTests (userTesztek):
    - id: a felhasználói tesztek azonosítója, int(10), autoIncrement
    - userId (felhasználóId): itt határozzuk meg, hogy a teszt melyik felhasználóhoz tartozik az azonosító segítségével, int(10), autoIncrement
    - testName (tesztNév): itt adjuk meg a teszt nevét, string(30), notNull
    - score (eredmény): Itt adjuk meg a teszt eredményét, double(5,2), nullable

- testQuestions (tesztKérdések):
    - id: a tesztkérdés azonosítója, int(10), autoIncrement
    - QuestionsId (KérdésekId): a tesztben megtalálható véletlenszerűen generált kérdés azonosítója, int(10), autoIncrement
    - answerId (válaszId): Itt adjuk meg a tesztben található kérdésnek a helyes válaszát az azonosító segítségével, int(10), autoIncrement
    - userTestId (userTesztId): Itt adjuk meg, hogy a tesztkérdés melyik felhasználói tesztben jelenik meg az azonosító segítségével, int(10), autoIncrement



# A backend oldal:

Ebben fog megtörténni a szerveroldal fejlesztésének ismertetése

## A migráció:
- A migráció azért felel, hogy a létrehozott táblához hozzáadjuk a mezőket, annak típusát és egyéb jellemzőjét. Ezen felül a meglévő táblákkal kapcsolatot is létre tudunk hozni.

![migrációs fájlok](/backend/img/migrations.jpg)

```php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->text('question');
            $table->integer('questionTypeId');
            $table->integer('categoryId');

            $table->foreign('questionTypeId')->references('id')->on('question_types');
            $table->foreign('categoryId')->references('id')->on('categories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
```
- Ez itt a questions tábla létrehozására összpontosít. Az up() függvény hozza létre a táblát és a benne szereplő mezőket. Ezt a Schema::create() függvény hozza létre. Itt 4 darab mező van, egy elsődleges kulcs (id), egy érték mező (question), és két idegen kulcs (a questionTypeId és a categoryId). Ezután létrehozzuk az összeköttetést a két tábla között. A drop függvény pedig a "php artisan migrate:rollback" parancs kiadásakor törli, azaz visszavonja a tábla létrejöttét.

## A modellek:
- A Laravel az Eloquent ORM-et (Object-Relational Mapping) használja. Ez azt jelenti, hogy a modellek PHP osztályok, amik egy-egy adatbázistáblát képviselnek.
- Ezeket is ugyanúgy, minden táblához el kell készíteni.

```php
class Answer extends Model
{
    use HasFactory;
    protected $fillable = ['answer', 'questionId', 'rightAnswer'];
    public function question()
    {
        return $this->belongsTo(Question::class, 'questionId');
    }

    protected function casts(): array
    {
        return [
            'rightAnswer' => 'boolean'
                                
        ];
    }
}

```

- $fillable: Ez egy tömeges hozzárendelés (mass assignment) védelmet biztosító lista. Meghatározza, mely mezők tölthetők ki create() vagy update() során.

- question() kapcsolat: Ez a függvény azt mondja: egy válasz egy kérdéshez tartozik.

- casts() metódus: Ebben az esetben ez a rész azt mondja meg a Laravelnek: a rightAnswer mezőt logikai értékként (true/false) kezelje, ne csak sima numerikus (0/1) vagy szöveg ('true', 'false') formában.

- A jelszó titkosítása bár mehetne casts metódussal is, de itt egy másik formátumot használtunk.

```php
// A jelszó titkosítása a mentés előtt
     protected static function booted()
     {
         static::creating(function ($user) {
             if (!empty($user->password)) {
                 $user->password = Hash::make($user->password);
             }
         });
     
         static::updating(function ($user) {
             // Csak akkor hash-eljük újra a jelszót, ha tényleg megváltozott
             if ($user->isDirty('password') && !empty($user->password)) {
                 $user->password = Hash::make($user->password);
             }
         });
     }
```
- Ez egy modell esemény figyelő, amely a User modellbe van beépítve. A célja, hogy automatikusan titkosítsa a jelszót, amikor:

    1. Új felhasználót hozunk létre.

    2. Meglévő felhasználót frissítünk, és közben megváltozik a jelszó.

- creating esemény – új felhasználó mentése előtt: Ez azt mondja: amikor egy új User példányt hozunk létre, akkor hash-eljük (titkosítjuk) a jelszót, ha az nincs üresen.

- updating esemény – meglévő felhasználó frissítésekor: Ez azt figyeli, hogy változott-e a jelszó:

    - isDirty('password') ➜ igaz, ha a jelszó mező módosult

    - Ha igen, akkor újra titkosítja a jelszót

- Ez azért fontos, hogy ne hash-elje újra az amúgy már hash-elt jelszót, ha nem változott.

## A seeder-ek:
- Az alábbi seeder fájlok célja az adatbázis inicializálása fejlesztési környezetben. A seederek Faker könyvtárat, statikus adatokat és fájlimportot egyaránt alkalmaznak. A sorrendiség és az adatok közötti kapcsolat biztosítása érdekében a DatabaseSeeder gondoskodik a megfelelő hívási sorrendről.


 - AnswerSeeder.php:

    - Minden kérdéshez véletlenszerűen 2–4 válasz generálódik.

    - A válaszok közül mindig pontosan egy kerül kijelölésre helyesként (rightAnswer = 1).

    - A válaszok szövege a Faker könyvtár segítségével véletlenszerűen kerül előállításra.

    - A questionId mező biztosítja az idegen kulcs kapcsolatot a questions táblával.

- CategorySeeder.php

    - A kategóriák külső .csv fájlból (temakorok.csv) kerülnek beolvasásra.

    - A fájl elérési útvonala: database/txt/temakorok.csv.

    - A sorokat szétválasztó karakter: ;.

    - Csak akkor történik feltöltés, ha a categories tábla még üres.

    - A kategóriákhoz tartozó szöveg (text) mező kezdetben üres.

- DatabaseSeeder.php

    - Ez a fájl felelős az összes többi seeder meghívásáért.

    - A seeder futása előtt törli a főbb táblák tartalmát (DELETE FROM ...), ezzel biztosítva a friss állapotot.

    - A call() metódus segítségével meghatározott sorrendben hívja meg a seeder fájlokat – ez kulcsfontosságú az idegen kulcs kapcsolatok miatt.

- QuestionSeeder.php

    - Három minta kérdést szúr be a questions táblába.

    - Minden kérdéshez meg van határozva a típus (questionTypeId) és a témakör (categoryId).

    - A kérdések statikus, kézzel megadott értékekkel kerülnek feltöltésre.

- QuestionTypeSeeder.php

    - A question_types tábla feltöltését végzi.

    - Három fő kategóriát ad hozzá: Évszámok, Fogalmak, Személyek.

    - Ezek a kategóriák segítik a kérdések típus szerinti szűrését.

- RoleSeeder.php

    - Két alapértelmezett szerepkört hoz létre:

        1. admin (ID: 1)

        2. user (ID: 2)

    - Ezek a szerepek lehetővé teszik a jogosultsági szintek kezelését az alkalmazásban.

- SourceSeeder.php

    - Különböző kategóriákhoz kapcsolódó forrásanyagokat generál.

    - A Faker könyvtár véletlenszerű URL-eket és megjegyzéseket hoz létre.

    - Minden Source rekord kapcsolódik egy categoryId értékhez.

    - Alapértelmezetten 50 forrás jön létre.

- TestQuestionSeeder.php

    - Teszt-kérdés-válasz kapcsolatok feltöltését végzi.

    - Az első user_test rekordhoz rendel kérdéseket és véletlenszerű válaszokat.

    - Feltételezi, hogy a questions és answers táblák már feltöltésre kerültek.

- UserSeeder.php

    - Egy admin felhasználót hoz létre, ha még nincs adat a users táblában.

    - A jelszót titkosítja a User modell (implicit hash-elés).

    - Létrejövő felhasználó:

        - Email: test@example.com

        - Jelszó: 123

        - Szerepkör: admin (ID: 1)

- UserTestSeeder.php

    - Két mintatesztet hoz létre a test nevű admin felhasználóhoz.

    - Az eredmények (score) lebegőpontos értékek: pl. 85.5, 92.3.

    - A rekordok a user_tests táblába kerülnek.

## A controller-ek:
- A Controller-ek (vezérlők) a Laravelben az MVC (Model–View–Controller) architektúra "C" komponensét jelentik. Feladatuk, hogy közvetítsenek a felhasználói kérések (requestek) és az alkalmazás logikája, illetve az adatbázis (modellek) között.


- A controller fogadja az útvonalakon (routes) keresztül érkező HTTP-kéréseket (pl. GET, POST, PUT, DELETE), és meghatározza, hogy milyen művelet történjen. A controller általában meghívja a modelleket, hogy adatot kérjen le, módosítson, hozzon létre vagy töröljön.

- Példának a QuestionControllert fogjuk megnézni, hiszen nincsen nagy különbség a táblák Controller-jei között.

```php
public function index()
    {
        $rows = Question::all();
        // $rows = Diak::orderBy('nev', 'asc')->get();
        $data = [
            'message' => 'ok',
            'data' => $rows
        ];
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
        $rows = Question::create(attributes: $request->all());
        return response()->json(['rows' => $rows], options:JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $row = Question::find($id);
        if ($row) {
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, $id)
    {
        $row = Question::find($id);
        if ($row) {

            try {
                $row->update($request->all());
                $data = [
                    'message' => 'ok',
                    'data' => $row
                ];
            } catch (\Illuminate\Database\QueryException $e) {
                $data = [
                    'message' => 'The patch failed',
                    'data' => $request->all()
                ];
            }

        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $row = Question::find($id);
        if ($row) {
            $row->delete();
            $data = [
                'message' => 'ok',
                'data' => [
                    'id' => $id
                ]
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }
```
- Ha egy tábláhot készítünk Controller-t, akkor általában 5 függvény lesz. index(): GET, store(): POST, show(id): GET BY ID, update(id): PATCH/PUT, destroy(id): DELETE.

    - index(): Minden kérdést visszaad. A válasz formázott JSON, amely "message" és "data" kulcsokat tartalmaz.

    - store(StoreQuestionRequest $request): Új kérdés létrehozása validált adatok alapján. A StoreQuestionRequest automatikusan elvégzi az adatellenőrzést. Sikeres mentés után visszaadja az új rekordot.

    - show(int $id): Egy kérdés lekérdezése az ID alapján. Ha nem található, "Not found" üzenettel tér vissza.

    - update(UpdateQuestionRequest $request, $id): Frissíti az adott kérdést validált adatokkal. Ha nincs ilyen rekord, "Not found" választ ad. Ha SQL-hiba történik, "The patch failed" üzenetet ad vissza.

    - destroy(int $id): Törli a kérdést az ID alapján. Ha sikeres, "ok" üzenettel tér vissza, különben "Not found".

### A login és a logout:

- A UserController-ben találhatjuk meg a fentebb megjegyzetteken felül a login és a logout függvényeket, amelyek a bejelentkezéshez és a kijelentkezéshez használunk.
```php
    public function login(Request $request)
    {
        //beolvassuk az adatokat
        $email = $request->input('email');
        $password = $request->input('password');

        //megkeressük a usert
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $password ? $user->password : "")) {
            return response()->json([
                'message' => 'invalid email or password'
            ], 200);
        }

        // Token készítése roleId alapján
        $abilities = match ($user->roleId) {
            1 => ['*'], // Admin: mindenhez hozzáférés
            2 => ['categories:view', 'sources:view', 'users:view', 'userTests:view', 'testQuestions:view', "answers:view"], // Korlátozott felhasználó
            default => [], // Alapértelmezett: semmihez nincs joga
        };

        $user->token = $user->createToken('access-token', $abilities)->plainTextToken;
        
        return response()->json(['user' => $user], options: JSON_UNESCAPED_UNICODE);
        
    }
```
- A login(Request $request):  
    1. Először is a beérkező HTTP kérésből kinyerjük az e-mailt és a jelszót.
    2. Lekérjük az első olyan User rekordot, ahol az e-mail egyezik. Ha nincs találat, $user értéke null lesz.
    3.  Feltétel: Ha nem található felhasználó, vagy a jelszó nem egyezik a titkosított jelszóval (Hash::check() ellenőrzi), akkor hibás belépés.
    4. Jogosultságok (abilities) kiosztása: Az abilities tömb azt határozza meg, hogy a generált token mire lesz jogosult.
        - 1 → admin jog: ['*'] - minden
        - 2 → korlátozott jogkörök
        - minden más: semmire nincs joga
    5. Ha a jelszó stimmel: Készít egy új API tokent az adott képességekkel. Hozzárendeli a token mezőhöz, amit így visszaadhat. A createToken() metódus a Laravel Sanctum csomag része. 
        - A plainTextToken az, amit a frontendnek vissza kell küldeni, és amit majd a Bearer token formában elment.
    6. Mi jön vissza? A teljes felhasználói adat + a token JSON válaszban. A magyar karakterek is kezelve vannak (JSON_UNESCAPED_UNICODE).




```php
public function logout(Request $request){
        $token = $request->bearerToken();
        $personalAccessToken = PersonalAccessToken::findToken($token);
        if ($personalAccessToken) {
            $personalAccessToken->delete();
            return response()->json(['message' => 'Successfully logged out'], options:JSON_UNESCAPED_UNICODE);
            
        } else {
            return response()->json(['message' => 'Token not found'], options:JSON_UNESCAPED_UNICODE);
        }
        
    }
```

- A logout(Request $request):
    1. Lekéri az Authorization headerből a Bearer {token} értéket. 
    2.  A token alapján megkeresi az adatbázisban a hozzá tartozó personal_access_tokens rekordot.
    3. Ha megtalálja, törli – így a token többé nem lesz használható. Ha nem található token, hibát ad vissza – de ez nem kritikus.
    4.  Visszajelzés a sikeres kijelentkezésről.


- Meg kell tennünk még egy apró megjegyzést. A User tébla nem teljesen ugyanúgy frissül, ahogy a többi, úgyhogy kitérnénk még erre is. Fontos még az is, hogy elkerüljük ezeket: az ismeretlen ID-k frissítését, az e-mail duplikációt, a felesleges adatbázis-módosítást.

    
```php
public function update(UpdateUserRequest $request, int $id)
{
    $row = User::find($id);

    if (!$row) {
        return response()->json([
            'message' => 'Not found',
            'id' => $id
        ], 404);
    }

    // Ha van e-mail a kérésben ÉS az eltér a meglévőtől, akkor ellenőrizzük az egyediséget
    if ($request->has('email') && $request->email !== $row->email) {
        $emailExists = User::where('email', $request->email)->exists();
        if ($emailExists) {
            return response()->json([
                'message' => 'This email already exists',
                'email' => $request->email
            ], 400);
        }
    }

    // Csak azokat az adatokat frissítjük, amik valóban változtak
    $row->update($request->only(['name', 'email', 'password']));
    
    return response()->json(['row' => $row], 200);
}
```

1. Megkeresi az adott id-hoz tartozó felhasználót.
    2.  Ha nem található ilyen felhasználó, 404-es státusszal tér vissza.
    3. Ha a kérésben van email mező és az eltér a jelenlegi e-mailtől, akkor: Ellenőrizzük, hogy létezik-e már más felhasználónál ugyanez az e-mail. Ezzel megelőzzük a duplikált e-mail címek mentését.
    4. Ha az e-mail már létezik, 400-as hibával tér vissza.
    5.  A User rekord frissítése csak azokra a mezőkre korlátozva, amelyek engedélyezettek (név, e-mail, jelszó).
    6. Ezután visszaadjuk a frissített Usert.

### A QueryController
- A fejlesztés közben felmerült az, hogy a question_types, a questions és az answers táblát összevonjuk egy több táblás lekérdezéssel. Ahogy az látható, csakis GET műveletet tud.

    1. SQL lekérdezés LEFT JOIN-okkal, hogy minden kérdés megjelenjen, függetlenül attól, van-e válasz.
    DB::select() – nyers SQL lekérdezés Laravel Query Builder helyett, nagyobb kontrollt biztosítva.
    2. Csoportosítás: Minden kérdést egyszer veszünk fel egy groupedQuestions tömbbe, egy questionMap segítségével, ami gyors elérést biztosít.
    3. Válaszok hozzáadása: Ha a sor tartalmaz answerId-t, akkor hozzáadja az aktuális kérdéshez a rightAnswert Boolean típusra alakítva. 
    4. array_values(): újraindexálja a tömböt (0, 1, 2...), hogy ne legyenek „lyukas” indexek.

```php
    public function index()
{
    $query = "SELECT questions.id AS questionId, questions.question, questions.categoryId, question_types.questionCategory, questions.questionTypeId AS questionTypeId, answers.id AS answerId, answers.answer, answers.rightAnswer
              FROM questions
              LEFT JOIN question_types ON questions.questionTypeId = question_types.id
              LEFT JOIN answers ON questions.id = answers.questionId"; // Használj LEFT JOIN-ot, hogy akkor is jöjjenek vissza a kérdések, ha nincs válasz

    $rows = DB::select($query);

    $groupedQuestions = [];
    $questionMap = [];

    foreach ($rows as $row) {
        // Ha még nem találkoztunk a kérdéssel, hozzuk létre
        if (!isset($questionMap[$row->questionId])) {
            $questionMap[$row->questionId] = count($groupedQuestions);
            $groupedQuestions[] = [
                'questionId' => $row->questionId,
                'question' => $row->question,
                'categoryId' => $row->categoryId,
                'questionCategory' => $row->questionCategory,
                'questionTypeId' => $row->questionTypeId,
                'answers' => [], // Üres válaszok tömb, ha nincs válasz
            ];
        }

        // Ha van válasz, hozzáadjuk és beillesztjük a questionId-t
        if ($row->answerId) {
            $groupedQuestions[$questionMap[$row->questionId]]['answers'][] = [
                'answerId' => $row->answerId,
                'answer' => $row->answer,
                'rightAnswer' => $row->rightAnswer == 1 ? true : false,
                'questionId' => $row->questionId, // Hozzáadjuk a questionId-t
            ];
        }
    }

    // Még akkor is visszaküldjük a kérdéseket, ha nincs válaszuk
    $data = [
        'message' => 'ok',
        'data' => array_values($groupedQuestions), // A tömb indexeinek visszaállítása
    ];

    return response()->json($data, options: JSON_UNESCAPED_UNICODE);
}
```

- A másik függvény igazából csak id alapján adja vissza az adott kérdést a válaszokkal együtt, még akkor is, ha nincs válasz. Az SQL lekérdezésben a különbség az, hogy van WHERE questions.id = :id megszorítás. 

```php
if (empty($groupedQuestions)) {
    return response()->json(['message' => 'No data found'], 404);
}
```
- És ha nem találja meg a kérdést az adott ID-vel, akkor 404-es hiba lép fel.


### UserRoleController
- Ez a controller a felhasználók és szerepköreik kezelését végzik el egy Laravel API-ban.

```php
public function index()
    {
        return response()->json(User::with('role')->get());
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'roleId' => 'required|integer|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->roleId = $request->roleId;
        $user->save();

        return response()->json(['message' => 'Role updated successfully']);
    }
```
- index(): Az összes felhasználó listázása a hozzájuk tartozó szerepkörrel együtt. Eager loading-ot használ. Betölti az összes users rekordot és a hozzájuk tartozó role modellt (feltételezve, hogy User modellben van egy role() kapcsolat). Így kéri le az adatokat.

- updateRole(): 
    1. Validálás: Kötelező, egész szám, Léteznie kell az roles táblában az adott id-nak.
    2. Felhasználó lekérése: Ha a felhasználó nem létezik, automatikusan 404 válasz keletkezik.
    3. Szerepkör módosítása és mentése.




## A validáció:
- A validáció akkor lép életbe, amikor új rekordot viszünk fel vagy amikor egy már meglévő rekordot szeretnénk módosítani. Validálni tudunk a backend-ben és a frontend-ben is.
- Hogy is néz ki ez a backend-ben?
    - Két külön fájlban kell meghatározni egy táblához a Store és az Update miatt. StoreModelRequest.php és UpdateModelRequest. Felépítésük teljesen ugyanaz. Felhasználásuk is csak attól függ, hogy melyik függvény paraméteréhez írjuk. 
    - authorize(): Ha true értékkel jön vissza, akkor engedélyezi a felhasználónak ezt a műveletet.
    - rules(): itt határozzuk meg, hogy a rekord hozzáadásának pillanatában milyen szabványoknak kell megfelelnie. Egy példa:

```php
public function rules(): array
    {
        return [
            'userId' => 'required|exists:users,id', //kötelező és léteznie kell annak az ID-nek, amit átadunk.
            'testName' => 'required|string', // kötelező és szöveg formátumúnak kell lennie
            'score' => 'nullable|numeric' //nem kötelező, egész vagy tört szám is lehet
        ];
    }
```


## A REST API kérések:
- A REST (Representational State Transfer) egy szabványos architektúra, amely meghatározza, hogyan épüljenek fel az API kérések HTTP-n keresztül.

- Ha működésre szeretnénk késztetni a végpontunkat, akkor a routes/api.php fájlban ilyen szintaktikával fel kell jegyezni: Először a művelet, utána a végpont elérési pontja, a kód helye (melyik osztályban), és a függvény neve. Ezután még hozzáírhatjuk azt is, hogy ez védett tartalom-e vagy nem a Middleware() segítségével és hogy milyen tokennel rendelkező felhasználók tudnak hozzáférni az adatokhoz.
```php
Route::post('/users/login', [UserController::class, 'login']);
    Route::post('/users/logout', [UserController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
    Route::get('/users/{id}', [UserController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':users:view');
    Route::post('/users', [UserController::class, 'store']);
    // ->middleware('auth:sanctum');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])
        ->middleware(CheckAbilities::class.':users:view');
    Route::patch('/users/{id}', [UserController::class, 'update'])
    ->middleware(CheckAbilities::class.':users:view');
```

- Ezt a request.rest-ben kezeljük. 

```r
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

- Middleware: A middleware egy olyan réteg, amely a kliens kérése és a Laravel alkalmazás belső működése között helyezkedik el. Célja lehet az autentikáció ellenőrzése. Laravelben minden kérés middleware-eken megy keresztül, mielőtt eljutna a controllerhez vagy route-hoz.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAbilities
{
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        // Ellenőrizzük, hogy van-e bejelentkezett user (Sanctum authentikáció)
        $user = $request->user();

        if (!$user || !$request->user()->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Megkapjuk a tokenhez tartozó képességeket (abilities)
        $tokenAbilities = $request->user()->currentAccessToken()->abilities;

        // Ellenőrizzük, hogy legalább egy szükséges képesség megvan
        foreach ($abilities as $ability) {
            if (in_array($ability, $tokenAbilities) || in_array('*', $tokenAbilities)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized.'], 403);
    }
}
```
- A handle metódus a kötelező belépési pont, ide jön minden HTTP-kérés.

- A $next egy callback, amit akkor hívunk meg, ha át akarjuk engedni a kérést a következő rétegnek (pl. controllernek).

- A ...$abilities egy variadic argumentum, tehát több jogosultságot is megadhatunk.

    1. Autentikációs ellenőrzés: Lekérjük a bejelentkezett felhasználót.
        - Ha nincs felhasználó vagy a token hiányzik → 401 Unauthenticated választ küldünk vissza.
    2. Token jogosultságok (abilities) ellenőrzése: Ez a sor kiolvassa a tokenhez tartozó jogosultságokat.
    3. Jogosultságvizsgálat: A middleware megnézi, hogy a token tartalmazza-e bármelyik megadott képességet.
        - Ha igen (vagy * van, ami mindenre jó) → átengedjük a kérést.
        - Ha egyik sincs → megyünk tovább a kódban.
    4. Jogosultság hiánya esetén válasz: Ha nincs meg a szükséges jogosultság, akkor 403-as hibát küldünk vissza, jelezve, hogy a felhasználó nem jogosult a kérés teljesítésére.

## A tesztelés:
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

- az "env name="APP_ENV" value="testing"/" sor biztosítja azt, hogy a tesztelési környezet a .env.testing fájlból fusson, ahol a teszt adatbázis van beépítve. A teszt adatbázis feltöltése úgy zajlik, hogy a .env-ben ideiglenesen átírjuk a DB_DATABASE változót a teszt adatbázis nevére.


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

        // További ellenőrzés, például az HTTP válasz ellenőrzése
        // $response = $this->actingAs($user)->get('/some-test-route');
        // $response->assertStatus(200);
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
    4. Adatbázisellenőrzés: Ez azt nézi meg, hogy a categories tábla tartalmaz-e egy olyan sort, ahol a témakör neve "xxx" és a szintje emelt.


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
        // VAGY célzottan csak az auth-ot:
        // $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        // $this->withoutMiddleware(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
    }
    public function test_the_querygetQuestionsWithTypesAndAnswers_response_time(): void
    {
        $this->startTime = microtime(true);
        $response = $this->get('/api/getQuestionsWithTypesAndAnswers');
        // $response = $this->get('/api/queryOsztalytasrsak/Balla Albert');
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
        // VAGY célzottan csak az auth-ot:
        // $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        // $this->withoutMiddleware(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
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


# A frontend oldal: