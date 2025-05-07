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
  - categoryId (témakörId): itt kapcsolódunk a témakörhöz, ez adja meg, melyik témakörbe kell, int(10), autoIncrement
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
  - RightAnswer (helyesVálasz): itt döntjük el a válasz helyességét: 1 - helyes, 0 - helytelen, tinyInt(1), notNull

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

- Ez itt a questions tábla létrehozására összpontosít. Az up() függvény hozza létre a táblát és a benne szereplő mezőket. Ezt a Schema::create() függvény hozza létre. Itt 4 darab mező van, egy elsődleges kulcs (id), egy érték mező (question), és két idegen kulcs (a questionTypeId és a categoryId). Ezután létrehozzuk az összeköttetést a két tábla között. A drop függvény pedig a `php artisan migrate:rollback` parancs kiadásakor törli, azaz visszavonja a tábla létrejöttét.

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

  - index(): Minden kérdést visszaad. A válasz formázott JSON, amely `message` és `data` kulcsokat tartalmaz.

  - store(StoreQuestionRequest $request): Új kérdés létrehozása validált adatok alapján. A StoreQuestionRequest automatikusan elvégzi az adatellenőrzést. Sikeres mentés után visszaadja az új rekordot.

  - show(int $id): Egy kérdés lekérdezése az ID alapján. Ha nem található, `Not found` üzenettel tér vissza.

  - update(UpdateQuestionRequest $request, $id): Frissíti az adott kérdést validált adatokkal. Ha nincs ilyen rekord, `Not found` választ ad. Ha SQL-hiba történik, `The patch failed` üzenetet ad vissza.

  - destroy(int $id): Törli a kérdést az ID alapján. Ha sikeres, `ok` üzenettel tér vissza, különben `Not found`.

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
  3. Feltétel: Ha nem található felhasználó, vagy a jelszó nem egyezik a titkosított jelszóval (Hash::check() ellenőrzi), akkor hibás belépés.
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
  2. A token alapján megkeresi az adatbázisban a hozzá tartozó personal_access_tokens rekordot.
  3. Ha megtalálja, törli – így a token többé nem lesz használható. Ha nem található token, hibát ad vissza – de ez nem kritikus.
  4. Visszajelzés a sikeres kijelentkezésről.

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

1. Megkeresi az adott id-hoz tartozó felhasználót. 2. Ha nem található ilyen felhasználó, 404-es státusszal tér vissza. 3. Ha a kérésben van email mező és az eltér a jelenlegi e-mailtől, akkor: Ellenőrizzük, hogy létezik-e már más felhasználónál ugyanez az e-mail. Ezzel megelőzzük a duplikált e-mail címek mentését. 4. Ha az e-mail már létezik, 400-as hibával tér vissza. 5. A User rekord frissítése csak azokra a mezőkre korlátozva, amelyek engedélyezettek (név, e-mail, jelszó). 6. Ezután visszaadjuk a frissített Usert.

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
              LEFT JOIN answers ON questions.id = answers.questionId";

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
        ->middleware('auth:sanctum', CheckAbilities::class.':users:view');
    Route::patch('/users/{id}', [UserController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':users:view');
```

- Ezt a request.rest segítségével próbáljuk ki, ami egy kézi tesztelő.

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
- Felhasználó lekérése ID alapján: Lekér egy konkrét felhasználót ID alapján (id=1); Védett tartalom
- Új felhasználó létrehozása: A roleId meghatározza, milyen szerepkört kap, JSON formában küldi a felhasználó adatait. Védett tartalom.
- Felhasználó törlése ID alapján: Törli a beírt ID-jű Usert. Védett tartalom.
- Felhasználó frissítése (részlegesen): A PATCH csak részlegesen frissít. Védett tartalom.

- Middleware: A middleware egy olyan réteg, amely a kliens kérése és a Laravel alkalmazás belső működése között helyezkedik el. Célja lehet az autentikáció ellenőrzése. Laravelben minden kérés middleware-eken megy keresztül, mielőtt eljutna a controllerhez vagy route-hoz. Parancs: `php artisan make:middleware CheckAbilities`

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
     - Ha igen (vagy \* van, ami mindenre jó) → átengedjük a kérést.
     - Ha egyik sincs → megyünk tovább a kódban.
  4. Jogosultság hiánya esetén válasz: Ha nincs meg a szükséges jogosultság, akkor 403-as hibát küldünk vissza, jelezve, hogy a felhasználó nem jogosult a kérés teljesítésére.

# A frontend oldal:

- A használt npm csomagok a projekthez:
  - quill
  - bootstrap
  - bootstrap-icons
  - axios

## A menüszerkezet:

- Ebben a részben mutatnánk be, hogy milyen menüpontok vannak és később kitérünk pontos működésükre.

### Amit a vendég (guest) lát:

- Menü:
  - ![Menü a vendégeknek](/backend/img/guest_menu.jpg)
  - ![Dropdown a vendégeknek](/backend/img/guest_dropdown.jpg)
- Kezdőlap:
  - Itt megszólítjuk a vendéget, hogy jelentkezzen be vagy regisztráljunk. Nem csak a kezdőlapból képes erre, hanem a fenti menüből a profil fül alatt is megtalálja.
    ![Kezdőlap guest](/backend/img/kezdolapGuest.jpg)
- Rólunk:
  - Mivel ez mindenhol egységes, ezért csak egyszer fogjuk tárgyalni.
  - Van egy apró bemutatkozás, amelyet egy `Küldetésünk` rész követ, amelyben leírjuk az oldal célját, és hogy mit szeretnénk elérni.
  - A `Csapatunk` részben láthatjuk az oldal készítőit.
  - A `Kapcsolat` fül pedig az elérhetőségeinket taglalja.  
    ![Rólunk](/backend/img/Rolunk.jpg)
- Regisztráció:
  - Ha a jobb felső sarokban a profil ikonra kattintunk, akkor a kezdőlapon is fellelhető regisztráció gombra kattintva ez jön be:
  - ![Regisztráció](/backend/img/regisztracio.jpg)
  - Itt meg kell adnunk egy felhasználónevet, egy e-mail címet és a használandó jelszót kétszer. Figyeljünk oda a megfelelő hosszúságra!
  - Ha sikeres volt, akkor a bejelentkezés ablakra fog minket bedobni.
- Bejelentkezés:
  - Ha egy meglevő fiókkal szeretnénk bejelentkezni, vagy éppen most regisztráltunk, akkor azt itt lehet megtenni.
  - ![Bejelentkezés](/backend/img/login.jpg)
  - Ha a bejelentkezés nem sikerül, akkor ilyen üzenetet kapunk vissza:
  - ![Sikertelen bejelentkezés](/backend/img/login_sikertelen.jpg)

### Amit a felhasználó (user) lát:

- Menü:

  - ![A felhasználó menüje](/backend/img/user_menu.jpg)
  - A különbség annyi, hogy több menüpont van, a profil ikon mellé a felhasználónevünk is bejön. A lefelé nyíló menüben a bejelentkezés/regisztráció helyett a profil/kijelentkezés jelenik meg:
  - ![A felhasználó dropdown-ja](/backend/img/user_dropdown.jpg)

- Kezdőlap:

  - A sikeres bejelentkezés után egy frissített kezdőlapot kapunk.
  - ![A felhasználó kezdőlapja](/backend/img/kezdolapUser.jpg)

- Témakörök:

  - Ennek a menüpontnak az a lényege, hogy megmutassa a jelenlegi témaköröket, amik fenn vannak és a hozzájuk tartozó források (amik alapján a kidolgozás elkészülhetett).
  - Két féle témakör van, amiket a betűjelekkel különböztetünk meg:
    - A közép:
      - ![Középszintű témakör](/backend/img/temakor_kozep.jpg)
    - Az emelt:
      - ![Emelt szintű témakör](/backend/img/temakor_emelt.jpg)
  - Lehetőségünk van szűrni őket a szint alapján:
    - ![Témakör szűrése](/backend/img/temakor_szures.jpg)
  - Minden témakörhöz tartozik egy leírás:

    - ![Témakör kidolgozása](/backend/img/temakor_kidolgozas.jpg)

  - Minden témakörhöz létezik forrás:
    - ![Témakör forrásai](/backend/img/temakor_forrasok.jpg)

- Tesztek:
  - Itt lehetséges tudásunkat tesztelni a témakörök kidolgozásának általi megszerzett tudást. Mindig csak a saját tesztjeink jelennek meg.
  - Alapértelmezetten csak az `Új teszt készítése` gomb jelenik meg:
    - ![Tesztek](/backend/img/tesztek_alap.jpg)
  - Ha rányomunk a fentebb említett gombra, akkor kapunk egy modális ablakot, ahol is megadhatjuk a teszt nevét és a mentés gombra kattintunk, akkor egy új tesztet adunk hozzá, amibe 10 véletlenszerű kérdést generál le.
    - ![Új teszt készítése](/backend/img/tesztek_uj.jpg)
  - Egy kis várakozás után az áhított teszt meg fog jelenni:
    - ![A tesztek állapotai](/backend/img/tesztek_allapotok.jpg)
    - Gyakorlatilag két állapota van a teszteknek:
      - Kitöltetlen: Ekkor a % helyén semmi nincsen és ezért a `Kitöltés` gomb jelenik meg.
      - Kitöltött: Ez a teszt ki lett töltve és ki is lett értékelve. A `Megtekintés` gombra meg lehet tekinteni a teszt részletes elemzését.
  - Ha a kitöltés gombra kattintunk, akkor egy legördülő listában megjelennek a kérdések a válaszlehetőségekkel együtt:
    - ![A teszt kérdései](/backend/img/tesztek_kerdesek.jpg)
    - Amennyiben a teszt beküldésekor nem jelöltünk meg valamelyik kérdésre választ, akkor ez az üzenet fogja ezt átadni:
      - ![Hibaüzenet a meg nem válaszolt kérdésre](/backend/img/tesztek_megoldatlan.jpg)
  - A teszt kiértékelése után megkapjuk az eredményeket (mire adtunk jó választ és ahol meg nem, ott pedig a jó és a rossz választ is megjeleníti):
    - ![A teszt kiértékelése](/backend/img/tesztek_ertekeles.jpg)
  - Azt is megfigyelhetjük, hogy ekkor a teszt eredménye bekerül a táblázatba:
    - ![A tesztek táblázatának módosulása](/backend/img/tesztek_modosulas.jpg)
  - A teszt törlése:
    - A gomb lenyomására megkérdezi, hogy tényleg szeretnénk-e törölni az adott tesztet:
      - ![A teszt törlése](/backend/img/tesztek_torles.jpg)
  - A teszt módosítása:
    - Ha rányomunk a ceruza ikonra, akkor lehetőségünk lesz módosítani a tesztünk nevét:
      - ![A teszt módosítása](/backend/img/tesztek_modositas.jpg)
  - A teszt megtekintése:
    - Ha esetleg újra meg szeretnénk tekinteni a tesztet, akkor a `Megtekintés` gombra kell rányomni és egy olyan nézettel találhatjuk szembe magunkat, amellyel a teszt kiértékelése után is találkozhattunk.
- Profil:
  - Ez az a pont, ahol meg tudjuk jeleníteni és változtatni a felhasználói fiókunk adatait, netán törölni az egész fiókot:
    - ![A profil](/backend/img/profil.jpg)
  - Ha rákattintunk a módosítás gombra, akkor ilyen megjelenést kapunk:
    - ![A profil adatának módosítása](/backend/img/profil_modositas.jpg)
      - Ha az e-mailt vagy a jelszót módosítjuk, akkor újra be kell jelentkeznünk. A felhasználónév esetében a jobb felső sorban a következő bejelentkezés során következik be a módosítás.

### Amit az admin lát:

- Amikor bejelentkezünk, akkor ez a kép fogad bennünket:
  - ![Az admin menü](/backend/img/admin_menu.jpg)
- Kezdőlap
  - ![Az admin kezdőlapja](/backend/img/kezdolapAdmin.jpg)
- Témakörök:
  - Felépítése teljesen hasonló ahhoz, amit a felhasználó lát, annyi különbséggel, hogy a CRUD műveleteket is el tudja végezni:
  - ![CRUD műveletek elvégzésének lehetősége a témaköröknél](/backend/img/temakor_crud.jpg)
    - create (a kidolgozott leírás az null):
      - ![Hozzáadás lehetősége a témaköröknél](/backend/img/temakor_crud_create.jpg)
    - update (csak a témakör és a szint):
      - ![Módosítás lehetősége a témaköröknél](/backend/img/temakor_crud_update.jpg)
    - update (csak a szöveget):
      - A gomb: ![A szöveg módosításának lehetősége a témaköröknél](/backend/img/temakor_crud_update_szoveg_gomb.jpg)
      - A WYSIWYG szerkesztő: ![A szöveg módosításának lehetősége a témaköröknél](/backend/img/temakor_crud_update_szoveg.jpg)
    - delete:
      - ![Törlés lehetősége a témaköröknél](/backend/img/temakor_crud_delete.jpg)
  - Szinte pontosan ugyanez a forrásoknál is, ezt nem fogjuk bemutatni, mivel szinte hasonlóan működik a témakörökhöz képest:
    - ![Témakör forrásai CRUD](/backend/img/temakor_forrasok_crud.jpg)
- Tesztek (semmi változás)

- Admin nézet (szerepkörök kezelése): Az admin saját szerepkörét nem tudja megváltoztatni:

  - ![Admin nézet](/backend/img/admin_nezet.jpg)
  - ![Szerepkör kiválasztása](/backend/img/admin_szerepkivalasztas.jpg)

- Kérdéstípusok

  - Itt lehet kérdéstípusokat hozzáadni a nemsokára kifejtendő kérdésbankhoz:
    - ![Kérdéstípus](/backend/img/kerdestipusok.jpg)

- Kérdésbank:
  - A kérdésbankban találhatjuk meg a kérdéseket a válaszaikkal együtt.
  - Alapértelmezésben nincsen megjelölve a témakör, ami alapján szűrnénk a kérdéseket, így minden kérdés megtekinthető:
    - ![Kérdésbank](/backend/img/kerdesbank_alap.jpg)
  - Viszont amennyiben rákattintunk valamely témakörre a listában, akkor kiszűri azokat a kérdéseket, amelyek az adott témakörhöz hozzátartoznak:
    - ![Szűrt kérdésbank](/backend/img/kerdesbank_kijelolve.jpg)
  - Ha így kattintunk rá az `Új kérdés létrehozása` gombra, akkor a témakör kiválasztásánál megjelent a szűrt témakör (egyébként a felhasználónak önerejéből kellene kiválasztania a témakört):
    - ![Új kérdés létrehozása](/backend/img/kerdesbank_create_kerdes.jpg)
  - Ha beírtunk minden adatot, akkor lehetőségünk lesz menteni a kérdést a `Mentés` gombbal és ezután a kérdés legalul meg is jelenik.
  - Miután létrehoztuk a kérdéseket és ha módosítani szeretnénk azt, akkor ezt a képet kell kapnunk:
    - ![Meglévő kérdés módosítása](/backend/img/kerdesbank_update.jpg)
  - Ekkor láthatjuk, hogy magát a kérdést is módosíthatjuk, amennyiben nem vagyunk megelégedve a megfogalmazással vagy a témakörrel. De a másik, a fontosabb gomb, az a válaszok hozzáadása. Ha rányomunk a `Válasz hozzáadása` gombra, akkor megjelenik egy `Új válasz` nevű válasz:
    - ![Válasz létrehozása után](/backend/img/kerdesbank_valasz.jpg)
  - Ha rányomunk a ceruza ikonra, akkor megnyílik egy szerkesztő mező, ahová beírhatjuk a válaszlehetőséget, amit jónak látunk.
    - ![Válasz módosítása](/backend/img/kerdesbank_valasz_update.jpg)
  - Ezen kívül azt is megjelölhetjük, hogy a megadott válaszlehetőség helyes-e vagy nem (a válasz előtti jelölődoboz). A válasz jobb oldalán levő gombbal menthetjük, az utána levővel pedig a szerkesztő mező záródik be mentés nélkül.
  - A törlés gombok természetesen törlik a választ. Ha ugyanezt a kérdéseknél tesszük meg, akkor nemcsak a megjelölt kérdés, hanem az ahhoz tartozó válaszok is törlődnek

## Működésük

- Milyen npm csomagokat használtunk a frontend elkészítéséhez:
  - axios: A CRUD műveletek egyszerűbb megvalósításáért
  - bootstrap: A kinézethez
  - bootstrap-icons: Az ikonokhoz
  - quill: A WYSIWYG (what you see is what you get (amit látsz, azt kapod)) szerkesztőhöz

### A route-ok

- A route-okat a router/index.js fájlban definiáljuk. Kétfélét fogunk bemutatni: azt, amihez nem szükségeltetik admin jogosultság, és azt, ahol szükséges.

- router/index.js:
  - Route-ok definiálása:

| Útvonal(ak)                                                | Komponens / Funkció        | Elérés feltétele            |
| ---------------------------------------------------------- | -------------------------- | --------------------------- |
| `/`                                                        | `HomeView`                 | mindenki számára elérhető   |
| `/bejelentkezes`, `/regisztracio`                          | `LoginView` / `SignUpView` | csak vendégek számára       |
| `/profil`, `/temakorok`, `/tesztek`                        | Privát oldalak             | bejelentkezés szükséges     |
| `/admin`, `/kerdesek`, `/kerdestipusok`, `/temakorokadmin` | Admin oldalak              | csak admin (`roleId === 1`) |
|  |

```js
router.beforeEach((to, from, next) => {
  document.title = "Historia - " + to.meta.title(to);
  next();
});
```

- Globális guard – oldal cím beállítása:
  - Minden navigációnál frissíti a dokumentum címét az útvonal meta adatainak megfelelően.

```js
function checkIfNotLogged(to, from, next) {
  const store = useAuthStore();

  if (!store.user) {
    return next("/bejelentkezes");
  }

  if (to.meta.requiresAdmin && store.roleId !== 1) {
    return next("/");
  }

  next();
}
```

- Hozzáférés-ellenőrző függvény:

  - Ez egy beforeEnter guard:
    - Ha nincs bejelentkezve (nincs store.user), akkor irányít a /bejelentkezes oldalra.
    - Ha admin jogosultság kell (to.meta.requiresAdmin === true), és a felhasználó roleId !== 1, akkor visszadobja a kezdőlapra.
    - Ellenkező esetben engedélyezi a navigációt.

- UseAuthStore.js: Pinia Store a hitelesítéshez

```js
import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    id: Number(localStorage.getItem("id")) || null,
    user: localStorage.getItem("user") || null,
    token: localStorage.getItem("currentToken") || null,
    roleId: Number(localStorage.getItem("roleId")) || null, // roleId hozzáadása
  }),
  actions: {
    setId(id) {
      localStorage.setItem("id", id);
      this.id = id;
    },
    setUser(user) {
      localStorage.setItem("user", user);
      this.user = user;
    },
    setToken(token) {
      localStorage.setItem("currentToken", token);
      this.token = token;
    },
    setRoleId(roleId) {
      // Új metódus a roleId beállításához
      localStorage.setItem("roleId", roleId);
      this.roleId = roleId;
    },
    clearStoredData() {
      localStorage.removeItem("currentToken");
      localStorage.removeItem("user");
      localStorage.removeItem("id");
      localStorage.removeItem("token");
      localStorage.removeItem("roleId"); // roleId törlése is
      this.token = null;
      this.user = null;
      this.id = null;
      this.roleId = null;
    },
  },
});
```

- Ez egy defineStore hívás, ahol a felhasználói adatokat tárolod.
  - state: Az adatok localStorage-ból töltődnek be, így a bejelentkezés tartós marad oldalfrissítés után is.
    - A roleId használata az admin jogosultság ellenőrzésére szolgál.
  - actions:
    - Ezek beállítják az adott értékeket és elmentik localStorage-ba is.
  - clearStoredData:
    - Teljes kijelentkezés funkció: törli a localStorage-ot és kiüríti a store állapotát.
- App.vue: Ez az a hely, ahol a menüpontokat valóban meg is jelenítjük. Ez egyben a webalkalmazás fő struktúráját biztosítja.

  - A template részben van a Bootstrap alapú responsive navigáció.
    - A fixed-top osztály rögzíti a tetejére, a navbar-dark és háttérszín (barna) a stílust adja meg.
  - A logó: ` <div class="navbar-brand logo">
      <RouterLink to="/">Historia</RouterLink>
    </div>`
    - A RouterLink komponens Vue Routeres navigációt biztosít (`<a>` helyett).
      Ez a logóra kattintva visszavisz a főoldalra.
  - Ezután a hamburger menüt definiáltuk, amely mobil eszközökön jelenik meg. Ez egy kattintásra lenyíló menü.
  - Navigációs menü:

    - A menüpontok RouterLink-kel navigálnak különböző route-okhoz.

      ```html
      <li class="nav-item">
        <RouterLink to="/" class="nav-link" @click="closeNavbar"
          >Kezdőlap</RouterLink
        >
      </li>
      <li class="nav-item">
        <RouterLink to="/rolunk" class="nav-link" @click="closeNavbar"
          >Rólunk</RouterLink
        >
      </li>

      <!-- Admin -->
      <li v-if="store.user && store.roleId === 1" class="nav-item">
        <RouterLink
          to="/temakorokAdmin"
          class="nav-link"
          @click="closeNavbar"
          id="categoriesAdmin"
          >Témakörök</RouterLink
        >
      </li>
      <li v-if="store.user && store.roleId === 2" class="nav-item">
        <RouterLink to="/temakorok" class="nav-link" @click="closeNavbar"
          >Témakörök</RouterLink
        >
      </li>
      <!-- ... -->
      ```

    - Dinamikus menüpontok szerepkör alapján:
      - `roleId === 1` → Admin (hozzáférés admin funkciókhoz),
      - `roleId === 2` → Átlagos felhasználó (pl. csak témaköröket lát).
    - Bejelentkezés / Regisztráció dropdown:
      ```html
      <li v-if="!store.user" class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle"
          href="#"
          id="userDropdown"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="bi bi-person"></i>
        </a>
        <ul
          class="dropdown-menu dropdown-menu-end"
          aria-labelledby="userDropdown"
        >
          <li>
            <RouterLink
              to="/bejelentkezes"
              class="dropdown-item"
              @click="closeNavbar"
              >Bejelentkezés
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/regisztracio"
              class="dropdown-item"
              @click="closeNavbar"
              >Regisztráció</RouterLink
            >
          </li>
        </ul>
      </li>
      ```
      - Csak kijelentkezett állapotban jelenik meg.
      - Bootstrap dropdown-t használ.
    - Felhasználónév + Kijelentkezés dropdown:
      ```html
      <li v-if="store.user" class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle"
          href="#"
          id="userDropdown"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="bi bi-person"></i> {{ store.user }}
        </a>
        <ul
          class="dropdown-menu dropdown-menu-end"
          aria-labelledby="userDropdown"
        >
          <li>
            <RouterLink class="dropdown-item" to="/profil" @click="closeNavbar"
              >Profil</RouterLink
            >
          </li>
          <li>
            <RouterLink
              class="dropdown-item"
              to="/"
              @click="LogoutAndCloseNavbar()"
              >Kijelentkezés</RouterLink
            >
          </li>
        </ul>
      </li>
      ```
      - Ha be van jelentkezve, megjelenik a felhasználó neve és profil/kijelentkezés opciók.
      - `LogoutAndCloseNavbar()` metódus meghívódik kijelentkezéskor.
    - A closeNavbar metódus:
      ```js
      closeNavbar() {
      const nav = document.querySelector(".navbar-collapse");
      if (nav && nav.classList.contains("show")) {
      nav.classList.remove("show");
          }
      }
      ```
      - Összecsukja a mobilnézetben megjelenő menüt.
    - A Logout metódusról később esik szó.

### A helper

```js
const BASE_URL = "http://localhost:8000/api";

export { BASE_URL };
```

Ez a kód egy mindenhol használható fix értékű változót ad, amennyiben beimportáljuk.

### Amit a vendég (a guest lát)

- Kezdőlap (HomeView.vue):

  - Célja egy dinamikus kezdőlap megjelenítése a felhasználó szerepköre alapján, `Vue 3` `setup()` API-val, és `Pinia` store-ral kombinálva.
  - A kezdőlap háromféle nézetet jelenít meg attól függően, hogy a felhasználó:
    - nem bejelentkezett (`user = null`)
    - tanuló szerepkörű (`roleId = 2`)
    - adminisztrátor (`roleId = 1`)
  - Vendég felhasználó:
    - Akkor jelenik meg, ha nincs bejelentkezett felhasználó.
    - Felkínál két gombot: bejelentkezés és regisztráció.
  - Tanuló (roleId === 2):
    - A tanuló nevét jeleníti meg.
    - 3 gomb: Témakörök, Profil, Tesztek
  - Admin (roleId === 1):
    - Admin nézet (nem jeleníti meg a nevet dinamikusan, csak `admin` felirat van).
    - 5 admin funkció elérhető gombként: témakörök, tesztek, kérdésbank, kérdéstípusok, adminfelület.
  - A script részben történik meg a `Pinia` alapú állapotkezelés

- Rólunk (AboutView.vue):

  - A sablon HTML-struktúrája szekciókra van bontva:
    - div.my-container:
      - Háttérképet alkalmaz a teljes nézetre (`parchment-texture.jpg`), ami a vizuális témához illeszkedik (klasszikus/oktatási hangulat).
    - div.about-container:
      - Az oldal tartalmát középre igazítja egy max 800px széles konténerben, padding-gel és centrális elrendezéssel.
  - Szekciók:

    1. about-section (Rülunk): Általános bemutatkozás.
    2. mission (Küldetés): Leírjuk a projekt célját – oktatásra fókuszálunk, érettségi felkészítőként funkcionálunk.
    3. Team (Csapat):
       ```html
       <section class="team">
         <h2>Csapatunk</h2>
         <div class="team-grid">
           <div
             class="team-member"
             v-for="member in teamMembers"
             :key="member.id"
           >
             <img
               :src="`/images/${member.image}`"
               :alt="member.name"
               class="team-img"
             />
             <h3>{{ member.name }}</h3>
             <p>{{ member.role }}</p>
           </div>
         </div>
       </section>
       ```

    - v-for ciklus segítségével jelenít meg csapattagokat a teamMembers tömbből.
    - Minden tag egy kis kártyaként jelenik meg képpel, névvel és szerepkörrel.

    4. Contact (Elérhetőség)
       - Email-cím szerepel itt egyszerű hivatkozással (mailto:).

  - Script rész (`<script>`):
    - Ez egy egyszerű data() visszatérési érték, amely tartalmazza a csapattagok listáját, egyedi ID-val, névvel, szerepkörrel és képfájllal.
    - A v-for ciklus ezt használja a template-ben.
    - A képek a /images/ mappában vannak.

- Regisztráció (SignUp.vue):

  - Célja, hogy új felhasználók be tudjanak regisztrálni a rendszerbe egyszerű űrlapkitöltéssel.
  - `<template>` szakasz – HTML és dinamikus logika:

    - A fő konténer (register-container) egy teljes képernyős, háttérképes div.
    - Belsejében egy középre igazított „kártya” (register-card) található, ahol a regisztrációs űrlap megjelenik.
    - Űrlapmezők:

      - Mindegyik input mező egy v-model-hez kötött adatmezőt módosít, és valós idejű validációt kap.
      - A hibák v-if-fel jelennek meg mezőnként: pl. ha a jelszó túl rövid vagy a két jelszó nem egyezik.

      | Mező               | Modell            | Validáció                 |
      | ------------------ | ----------------- | ------------------------- |
      | Felhasználónév     | `username`        | minimum 2 karakter        |
      | Email              | `email`           | HTML `type="email"`       |
      | Jelszó             | `password`        | minimum 6 karakter        |
      | Jelszó megerősítés | `confirmPassword` | egyeznie kell a jelszóval |

- Folytatása

  - Gomb – Regisztrálás
    - `@submit.prevent="handleSubmit"`: megakadályozza az alapértelmezett form viselkedést, és saját handleSubmit metódust hív.
    - Gomb disabled, ha a form nem valid vagy épp isLoading.
    - Betöltés közben a gomb szövege "Regisztráció...", egyébként "Regisztrálás".
  - Script:
    - Adatok:
      ```js
      username, email, password, confirmPassword;
      roleId = 2; // alapértelmezett szerepkör: normál felhasználó
      isLoading = false; // betöltés állapota
      errorMessage = null; // megjelenítendő hiba
      ```
    - Computed Property: `isFormInvalid`
      - Automatikusan `true`, ha bármelyik is hibás.
      ```js
      return (
        !username ||
        username.length < 2 ||
        !email ||
        password.length < 6 ||
        password !== confirmPassword
      );
      ```
    - `handleSubmit` metódus:
      1. Ellenőrzi, hogy a form valid-e (isFormInvalid).
      2. Összeállít egy payload-ot az API híváshoz.
      3. POST kérést küld az /users végpontra.
      4. Siker esetén: alert + átirányítás "/bejelentkezes" útvonalra.
      5. Hiba esetén: kiírja a hibát.
      6. Betöltési állapot (isLoading) megakadályozza a dupla küldést.
  - Style:
    - Kinézet:
      - Háttér: textúrázott pergamenhatás (parchment-texture.jpg)
      - register-card: világos háttér (krémszín), árnyékkal, lekerekítéssel
      - register-title: klasszikus betűtípus (Cinzel), stílusban illik a többi oldalhoz
      - input-group: ikon + input mező közös konténerben
      - register-button: barna árnyalatú, hover effektel
    - Hibaüzenetek
      - Piros szöveg
      - Kis méret (0.9rem)
      - Dinamikusan jelennek meg a v-if feltételekkel

- Bejelentkezés (Login.vue):
  - Egy felhasználói bejelentkezési űrlapot valósít meg. felhasználó email-címmel és jelszóval jelentkezik be, és sikeres autentikáció esetén elnavigál a főoldalra (/). A komponens axios-szal küld POST-kérést a backend felé, és egy Pinia store-t (useAuthStore) használ az adatok tárolására (pl. felhasználó azonosítója, név, token, jogosultság).
  - Template (HTML):
    - Két input mező: email és password (v-model kapcsolattal).
    - @submit.prevent="userAuth" eseménykezelővel hívja meg a beléptető függvényt.
    - loading változó alapján váltogatja a gombfeliratot (Bejelentkezés... vs. Bejelentkezés).
    - errorMessage jelenik meg, ha hiba történik.
  - Script:
    - userAuth() metódus:
      - Validálja, hogy mindkét mező ki van-e töltve.
      - Axios POST kérés küldi az adatokat: ${BASE_URL}/users/login.
      - Sikeres válasz esetén:
        - A store-ba elmenti a felhasználói adatokat (setId, setUser, setToken, setRoleId).
        - Beállítja a Authorization fejlécet az axios alapértelmezett beállításaiban.
        - Átirányít a főoldalra.
    - Magasság fixálás (mounted hook):
      - A setDynamicHeight funkció frissíti a --vh CSS változót, hogy elkerülje a mobilos "billentyűzet-felugrás" miatti UI-ugrást.
  - Stílus:
    - login-card középre igazított, árnyékolt dizájn.
    - input-group ikonokkal kombinált mező, letisztult.
    - Hover effekt a bejelentkezés gombon.

### Amit a felhasználó (user) lát:

- Témakörök (CategoriesView.vue):

  - A témakörök fő listanézete:

    - Template:
      - Tartalmaz egy szűrőt a szintek szerint (közép vagy emelt) → ez egy select mező selectedLevel-hez kötve.
      - Egy v-for ciklus fut végig a szűrt kategóriákon (filteredCategories), ahol mindegyikhez renderel egy CategoryCard komponenst.
      - A sources prop objektum alapján minden kategória ID-jéhez tartozó forrásokat is átadja (:sources="sources[category.id] || []").
    - Script:

      - data():

        - categories: a backendről betöltött témakörök listája.
        - sources: egy objektum, ahol a kulcs a categoryId, érték pedig az adott kategóriához tartozó források tömbje.
        - selectedLevel: a szűrő mező által kiválasztott szint.
        - expandedCategories: olyan kategória ID-k tömbje, amelyek éppen ki vannak nyitva (bár a CategoryCard kezeli saját maga az expanziót is).
        - store: a bejelentkezett felhasználó adatai a Pinia useAuthStore-ból.

      - computed: filteredCategories():
        - Ha nincs kiválasztott szint, minden témakör megjelenik.
        - Ha van, akkor csak azok, amelyek szintje (level) megegyezik a kiválasztottal (közép vagy emelt).
      - created():
        - Aszinkron betölti az adatokat a komponens létrejöttekor:
          - fetchCategories(): témakörök lekérése az API-ról.
          - fetchSources(): források lekérése, majd csoportosítása kategória szerint.
      - fetchSources():
        - Egy reduce() segítségével categoryId szerint csoportosítja a forrásokat egy objektumba:

  - CategoryCard.vue – Egy témakört (kategóriát) reprezentáló kártya

    - Template:

      - Felső rész

        - Kategória neve és egy kép (letter-k.svg vagy letter-e.svg), attól függően, hogy közép- vagy emelt szintű.
        - Egy lenyíló gomb (chevron ikon) → megnyitja/bezárja a részleteket (toggleExpand).
        - Ha admin a felhasználó (store.roleId === 1), akkor CRUD műveletek (szerkesztés/törlés) érhetők el egy OperationsCrudCategories komponensen keresztül.

      - Részletek (lenyitható tartalom)
        - Szint kiírása
        - Leírás (category.text), v-html-lel jelenik meg (HTML-t tartalmazhat).
        - Adminnak szerkesztés gomb, ami megnyitja a CategoryEditModal komponenst.
        - Források listázása (link és megjegyzés), adminnak minden forrásnál CRUD lehetőségek külön OperationsCrudSources komponensben.

    - Props:
      ```js
      props: [
        "category",
        "saveCategory",
        "sources",
        "onClickDeleteCategoryButton",
        "onClickUpdateCategoryButton",
        "onClickDeleteSourceButton",
        "onClickUpdateSourceButton",
        "onClickCreateSourceButton",
      ];
      ```
      - Ezek funkcionális kapcsolatot biztosítanak a CRUD műveletekhez – az eseményeket a szülő komponens kezelheti.
    - Funkciók (methods)
      - toggleExpand(): a category.expanded értékét váltogatja, hogy nyitva legyen-e a részlet.
        -openEditModal(): megnyitja a CategoryEditModal-t.

A categoryEditModalról később lesz szó.

- Tesztek (TestsView.vue):

  - Egy felhasználói felületet biztosít a tesztek kezeléséhez és kitöltéséhez.
  - Template:

    - `div.container`: A fő konténer, ami valószínűleg a Bootstrap által biztosított elrendezést használja.

    - OperationsCrudUserTests:

      - Ez a komponens CRUD (Create, Read, Update, Delete) műveletekhez kapcsolódó gombokat vagy funkciókat tartalmaz a felhasználói tesztekhez.
        - A `style="margin-top: 10px"` inline stílus margint ad a tetejéhez.
        - Az `@onClickCreateButton="onClickCreateButton"` eseménykötés azt jelenti, hogy amikor ebben a komponensben egy "létrehozás" gombra kattintanak, az aktuális komponens (TestsView) onClickCreateButton metódusa fog lefutni.

    - `div.row`: Egy Bootstrap sor, ami a tesztek listáját és a teszt kérdéseit tartalmazza egymás mellett (nagyobb képernyőkön). A `style="min-height: 100vh"` biztosítja, hogy a sor legalább a képernyő magasságát elfoglalja.

    - div.col-12 col-md-6 (bal oldali oszlop):

      - h2.title: "Eddigi tesztek" felirat.
      - div.user-table-container table-responsive: Egy konténer a táblázat számára, table-responsive osztály biztosítja a vízszintes görgetést kisebb képernyőkön.
      - table.table table-hover user-table: Bootstrap táblázat alapstílussal és hover (egér fölé vitel) effekttel.
        - thead: A táblázat fejléce ("Név", "%", "+").
        - tbody: A táblázat tartalma, ahol a v-for direktíva iterál a userTests tömb elemein.
          - tr.my-cursor: Minden teszthez egy sor, a my-cursor osztály valószínűleg egyedi kurzor stílust ad.
          - A :key="userTest.id" a Vue.js hatékonyabb DOM frissítéséhez szükséges.
          - A cellák megjelenítik a teszt nevét (userTest.testName) és az elért pontszámot (userTest.score). Ha a score null, akkor "N/A" jelenik meg.
          - Az utolsó cella (td) tartalmazza:
            - Egy másik OperationsCrudUserTests komponens, aminek átadásra kerül az aktuális userTest objektum. Ez a komponens a törlés (@onClickDeleteButton) és a szerkesztés (@onClickUpdateButton) gombokat tartalmazza, amelyek a TestsView komponens megfelelő metódusait hívják meg.
          - Két gomb, amelyek feltételesen jelennek meg a userTest.score alapján:
            - "Kitöltés" gomb (v-if="userTest.score === null"): Ha a teszt még nincs kitöltve (nincs pontszám), ez a gomb a loadTestQuestions(userTest.id) metódust hívja meg a teszt kérdéseinek betöltéséhez.
            - "Megtekintés" gomb (v-else): Ha a teszt már ki van töltve (van pontszám), ez a gomb a loadTest(userTest.id) metódust hívja meg a teszt eredményeinek megtekintéséhez.
          - span: Egy üzenet (v-if="generating"), ami akkor jelenik meg, ha a generating állapot igaz (a teszt generálása folyamatban).

    - div.col-12 col-md-6 (jobb oldali oszlop, v-if="currentUserTestId"):

    - Ez az oszlop csak akkor jelenik meg, ha van kiválasztott teszt (currentUserTestId nem null).
    - div.test-questions-container: Konténer a teszt kérdéseinek megjelenítéséhez.

      - A v-for direktíva iterál a testQuestions tömb elemein.
      - div.test-card: Minden kérdéshez egy kártya stílusú div.
        - h5.question-title: A kérdés szövege, a sorszámmal együtt.
        - div (válaszok): Egy újabb v-for ciklus iterál a testQuestion.answers tömb elemein.
          - input[type="radio"]: Rádiógombok a válaszok kiválasztásához. Az értéke az answer.answerId, a v-model a testQuestion.selectedAnswerId-hez van kötve (kétirányú adatkapcsolat), és a disabled attribútum a submitted állapottól függ.
          - label.answer-label: A válasz szövege. A :class="getAnswerClass(testQuestion, answer)" dinamikusan osztályokat ad hozzá a helyes/helytelen válaszok jelzéséhez a beküldés után.
      - span: Egy üzenet (v-if="rating"), ami akkor jelenik meg, ha a rating állapot igaz (a teszt kiértékelése folyamatban).
      - h3: Az eredmény megjelenítése (v-if="submitted"), a scorePercent értékével.
      - div.button-group: Gombok a teszt beküldéséhez vagy a nézet bezárásához.
        - "Teszt Beküldése" gomb (v-if="!submitted"): A submitTestAnswers metódust hívja meg.
        - "Bezárás" gomb (v-else): A closeTest metódust hívja meg.

    - Modal (egyedi komponens):

      - Ez egy általános célú modális ablak komponens.
      - Különböző propokat kap (title, yes, no, size).
      - Az @yesEvent eseménykötés azt jelenti, hogy amikor a modalban egy "igen" gombra kattintanak, az yesEventHandler metódus fut le.
      - A modális tartalma feltételesen jelenik meg a state változó alapján:
        - Ha state értéke "Delete", akkor egy megerősítő üzenet jelenik meg (messageYesNo).
        - Ha state értéke "Create" vagy "Update", akkor a UserTestForm komponens jelenik meg, aminek átadásra kerül a userTest objektum és a categories tömb, valamint az @saveItem esemény a saveItemHandler metódust hívja meg.

  - Script:

    - class UserTest: Egy egyszerű JavaScript osztály a teszt objektumok létrehozásához.

    - import állítások: Külső modulok és komponensek importálása (axios HTTP kérésekhez, BASE_URL és useAuthStore segédfüggvények/tárolók, UserTestForm és OperationsCrudUserTests komponensek, Bootstrap).

    - export default: A Vue.js komponens definíciója.

      - components: Regisztrálja a használt egyedi komponenseket.
      - data(): A komponens állapotának tárolására szolgáló reaktív adatok:
        - store: Az autentikációs tároló (valószínűleg Pinia vagy Vuex).
        - urlApiUserTest: A felhasználói tesztek API végpontja.
        - userTests: A felhasználó eddigi teszteinek tömbje.
        - selectedRowId: A kiválasztott teszt ID-ja (szerkesztéshez vagy törléshez).
        - state: A komponens aktuális állapota ("Read", "Create", "Update", "Delete").
        - title, yes, no, size: A modális ablak konfigurációs opciói.
        - userTest: Az aktuálisan létrehozott vagy szerkesztett teszt objektuma.
        - currentUserTestId: A jelenleg megtekintett vagy kitöltött teszt ID-ja.
        - testQuestionIds: A jelenlegi teszthez tartozó testQuestions rekordok ID-i.
        - testQuestions: A jelenlegi teszt kérdéseinek tömbje (a válaszokkal együtt).
        - submitted: Logikai érték, ami jelzi, hogy a teszt be lett-e küldve.
        - generating: Logikai érték, ami jelzi, hogy a teszt generálása folyamatban van.
        - rating: Logikai érték, ami jelzi, hogy a teszt kiértékelése folyamatban van.
        - scorePercent: A teszt eredményének százalékos értéke.
      - mounted(): A komponens DOM-ba illesztése után lefutó lifecycle hook. Itt betölti a felhasználói teszteket (fetchUserTests) és inicializál egy Bootstrap modal példányt.
      - methods: A komponens logikáját tartalmazó metódusok:
        - fetchUserTests(): Lekéri a felhasználóhoz tartozó teszteket az API-ból.
        - createUserTest(): Létrehoz egy új tesztet az API-n keresztül, majd meghívja a generateTestQuestions metódust a kérdések generálásához és frissíti a tesztlistát.
        - generateTestQuestions(userTestId): Lekéri az összes kérdést, véletlenszerűen kiválaszt 10-et, és létrehozza a hozzájuk tartozó testQuestions rekordokat az API-ban.
        - getRandomElements(arr, count): Egy segédfüggvény, ami egy tömbből véletlenszerűen kiválasztott elemeket ad vissza.
        - loadTestQuestions(userTestId): Betölti a megadott teszt kérdéseit és válaszait az API-ból a kitöltéshez.
        - loadTest(userTestId): Betölti a már kitöltött teszt kérdéseit és a felhasználó által adott válaszokat a megtekintéshez.
        - submitTestAnswers(): Elküldi a felhasználó válaszait az API-nak, kiszámolja az eredményt, frissíti a teszt pontszámát az API-ban és a helyi userTests tömbben.
        - closeTest(): Visszaállítja a testQuestions tömböt és a currentUserTestId-t.
        - getAnswerClass(question, answer): Visszaad egy CSS osztályt a válasz jelöléséhez (helyes/helytelen) a beküldés után.
        - updateUserTest(): Frissíti a kiválasztott teszt adatait az API-ban.
        - deleteUserTestById(): Törli a kiválasztott tesztet az API-ból.
        - onClickDeleteButton(userTest): Beállítja az állapotot és a modális ablak adatait a törléshez.
        - onClickUpdateButton(userTest): Beállítja az állapotot és a modális ablak adatait a szerkesztéshez.
        - onClickCreateButton(): Beállítja az állapotot és a modális ablak adatait az új teszt létrehozásához.
        - saveItemHandler(): Meghívja a megfelelő mentési metódust (létrehozás vagy frissítés) az aktuális state alapján, majd elrejti a modális ablakot.
        - yesEventHandler(): Meghívja a megfelelő műveletet (jelenleg csak törlés) a modális ablak "igen" gombjának megnyomásakor, majd elrejti a modális ablakot.

  - Style:

    - my-container: Háttérkép beállítása.
    - h2: Középre igazítás.
    - user-table: Táblázat oszlopainak automatikus szélessége.
    - user-table-container: Maximális magasság és függőleges görgetősáv a tesztek táblázatához.
    - test-questions-container: Maximális magasság és függőleges görgetősáv a teszt kérdéseihez.
    - @media (max-width: 768px): Reszponzív stílusok mobil nézetekhez (kisebb maximális magasság a görgethető konténerekhez).
    - test-card: A kérdés kártyájának stílusa (háttérszín, szegély, árnyék, hover effekt).
    - question-title: A kérdés címének stílusa.
    - answer-option: A válasz opciók közötti margó.
    - answer-label: A válasz szövegének alapértelmezett stílusa.
    - answer-label.correct: A helyes válasz szövegének stílusa (zöld és félkövér).
    - answer-label.incorrect: A helytelenül kiválasztott válasz szövegének stílusa (piros és félkövér).
    - result-title: Az eredmény címének stílusa.
    - score: Az elért pontszám stílusa (zöld szín).
    - button-group: A gombok csoportjának margója.
    - button: Az alapértelmezett gomb stílusa.
    - button:focus: A gombok fókuszban lévő stílusa (outline eltávolítása és egyedi box-shadow).

  - OperationsCrudUserTests (de mivel mindegyikben ugyanaz van, ezért csak itt elemezzük)

    - Template:

      - A sablon egyetlen div elemet tartalmaz, ami a gombokat foglalja magában.
      - Első div (v-if="userTest"): Ez a rész akkor jelenik meg, ha a komponens kap egy userTest propot (ami egy teszt objektumot tartalmaz). Ebben az esetben a törlés és a módosítás gombok jelennek meg.
        - `<button>` (Törlés):
          - type="button": Standard gombként definiálja.
          - class="btn btn-outline-danger": Bootstrap stílus a veszélyes (piros) körvonalas gombhoz.
          - data-bs-toggle="modal" és data-bs-target="#modal": Bootstrap modal trigger attribútumok, amelyek a #modal ID-jű modális ablakot nyitják meg.
          - @click="onClickDeleteButton": Amikor a gombra kattintanak, az onClickDeleteButton metódus fut le.
          - `<i class="bi bi-trash3"></i>`: Bootstrap Icons könyvtárból a kuka ikon.
        - `<button>` (Módosítás):
          - Hasonló a törlés gombhoz, de btn-outline-primary stílussal (kék körvonalas) és a ceruza ikont (bi-pencil) jeleníti meg.
          - ms-2: Bootstrap margin-start (bal margó) a gombok közötti térközhöz.
          - @click="onClickUpdateButton": Amikor a gombra kattintanak, az onClickUpdateButton metódus fut le.
      - Második div (v-else): Ez a rész akkor jelenik meg, ha a komponens nem kap userTest propot. Ebben az esetben csak a "Új teszt készítése" gomb jelenik meg.
        - `<button>` (Új teszt készítése):
          - class="btn btn-outline-success": Bootstrap stílus a sikeres (zöld) körvonalas gombhoz.
          - @click="onClickCreateButton": Amikor a gombra kattintanak, az onClickCreateButton metódus fut le.
          - `<i class="bi bi-plus-lg"></i>` Új teszt készítése: Bootstrap Icons plusz ikon és a gomb szövege.

    - script: A komponens logikája.

      - props: { userTest: Object }: Deklarálja a userTest propot, ami egy objektumot vár. Ez a prop opcionális, és a komponens viselkedését befolyásolja.
      - emits: ["onClickDeleteButton", "onClickUpdateButton", "onClickCreateButton"]: Deklarálja a komponens által kibocsátott egyéni eseményeket. Amikor a belső gombokra kattintanak, a komponens ezeket az eseményeket fogja "kibocsátani" (emitálni) a szülő komponens felé.
      - methods:: A komponens metódusai.
        - onClickDeleteButton(): Ezt a metódust a törlés gomb kattintásakor hívja meg. Kibocsát egy "onClickDeleteButton" eseményt a userTest objektummal együtt. A szülő komponens figyelheti ezt az eseményt, és elvégezheti a törlés logikáját.
        - onClickUpdateButton(): Ezt a metódust a módosítás gomb kattintásakor hívja meg. Kibocsát egy "onClickUpdateButton" eseményt a userTest objektummal együtt. A szülő komponens figyelheti ezt az eseményt, és megjelenítheti a szerkesztő űrlapot a kapott userTest adatokkal.
        - onClickCreateButton(): Ezt a metódust az "Új teszt készítése" gomb kattintásakor hívja meg. Kibocsát egy "onClickCreateButton" eseményt (paraméter nélkül). A szülő komponens figyelheti ezt az eseményt, és megjelenítheti az új teszt létrehozására szolgáló űrlapot.

  - Modal.vue (ebből csak egy van):

    - Ez egy általános célú, újrahasználható modális ablak komponens, amely a Bootstrap modal funkcionalitását használja.
    - Template:

      - A fő div (modal fade) a Bootstrap modal alapstruktúráját adja. A fade osztály animációt ad a megjelenítéshez és elrejtéshez. Az id="modal" attribútum teszi lehetővé, hogy más komponensek (pl. az OperationsCrudUserTests) a Bootstrap JavaScript segítségével megnyithassák ezt a modalt.
      - A belső div (modal-dialog modal-dialog-centered) a modális ablak tényleges tartalmának konténere, és a modal-dialog-centered osztály függőlegesen középre igazítja a modalt a képernyőn.
      - A :class binding dinamikusan ad hozzá Bootstrap méretosztályokat (modal-xl, modal-lg, modal-sm) a size prop értéke alapján, lehetővé téve különböző méretű modális ablakok létrehozását.
      - div.modal-content: A modális ablak tartalmának burkolója (fejléc, törzs, lábléc).
        - div.modal-header: A modális ablak fejléc része.
          - h1.modal-title: A modális ablak címe, a title prop értékéből kerül megjelenítésre.
          - `<button class="btn-close">`: A bezárás gomb (a Bootstrap automatikusan kezeli a bezárást a data-bs-dismiss="modal" attribútummal).
        - div.modal-body: A modális ablak fő tartalmi része.
          - `<slot></slot>`: Ez egy Vue.js slot, ami lehetővé teszi, hogy a szülő komponens tetszőleges HTML tartalmat szúrjon be ide. Az TestsView.vue komponensben például a törlés megerősítő üzenete vagy a UserTestForm kerül ide.
        - div.modal-footer: A modális ablak lábléc része (általában gombok).
          - `<button class="btn btn-secondary" v-if="no">`: A "Nem" vagy "Mégsem" gomb, csak akkor jelenik meg, ha a no prop értéke nem null. A data-bs-dismiss="modal" attribútum bezárja a modalt.
          - `<button class="btn btn-primary" v-if="yes" @click="onClickYesButton()" data-bs-dismiss="modal">`: Az "Igen" vagy megerősítő gomb, csak akkor jelenik meg, ha a yes prop értéke nem null. Az @click="onClickYesButton()" meghívja a komponens saját onClickYesButton metódusát, ami kibocsát egy "yesEvent" eseményt a szülő komponens felé, jelezve, hogy a felhasználó megerősítően cselekedett. A data-bs-dismiss="modal" itt is bezárja a modalt a kattintás után.

    - script: A komponens logikája.

      - props: ["title", "yes", "no", "size"]: Deklarálja a komponens által elfogadott propokat:
        - title: A modális ablak címe (string).
        - yes: A megerősítő gomb szövege (string, opcionális).
        - no: A visszautasító gomb szövege (string, opcionális).
        - size: A modális ablak mérete ('xl', 'lg', 'sm', opcionális).
      - emits: ["yesEvent"]: Deklarálja az egyetlen kibocsátott eseményt, a "yesEvent".
      - methods: A komponens metódusai.
        - onClickYesButton(): Ezt a metódust a megerősítő gomb kattintásakor hívja meg. Kibocsát egy "yesEvent" eseményt a szülő komponens felé, anélkül hogy bármilyen adatot mellékelne.

  - UserTestForm.vue:

    - template: Az űrlap HTML struktúrája.

    - `<form @submit.prevent="onClickSubmit" class="row g-3 needs-validation was-validated">`: Egy Bootstrap űrlap.
      - @submit.prevent="onClickSubmit": Megakadályozza az alapértelmezett űrlapküldési viselkedést (oldal újratöltését) és meghívja az onClickSubmit metódust a beküldéskor.
      - class="row g-3 needs-validation was-validated": Bootstrap osztályok a reszponzív elrendezéshez és az űrlap validációhoz (bár a validációs logika nincs implementálva a `<script>` részben).
    - `<div class="mb-3">`: Egy Bootstrap margóval ellátott div a címke és a beviteli mező számára.

      - `<label for="testName" class="form-label">`Mi legyen a neve?`</label>`: A teszt nevének címkéje.
      - `<input type="text" class="form-control" id="testName" required v-model="itemForm.testName" />`: A beviteli mező a teszt nevének megadásához.
        - type="text": Szöveges beviteli mező.
        - class="form-control": Bootstrap stílus a beviteli mezőhöz.
        - id="testName": A címkéhez való kapcsolódáshoz.
        - required: HTML5 validációs attribútum, ami megköveteli a mező kitöltését.
        - v-model="itemForm.testName": Kétirányú adatkapcsolat a itemForm prop testName tulajdonságával. Bármilyen változás a beviteli mezőben automatikusan frissíti a itemForm.testName-t, és fordítva.
      - `<button type="submit" class="btn btn-success">Mentés</button>`: A beküldés gomb Bootstrap zöld stílussal.

    - script: A komponens logikája.

      - props: ["itemForm"]: Deklarálja az itemForm propot. Ez egy objektumot vár, amely valószínűleg a szerkesztendő vagy létrehozandó teszt adatait tartalmazza (testName a jelenlegi formában). A v-model direktíva ezen a prop objektumon keresztül biztosítja a kétirányú adatkapcsolatot az űrlap elemeivel.
      - emits: ["saveItem"]: Deklarálja az egyetlen kibocsátott eseményt, a "saveItem".
      - methods: A komponens metódusai.
        - onClickSubmit(): Ezt a metódust az űrlap beküldésekor hívja meg (a @submit.prevent miatt). Kibocsát egy "saveItem" eseményt, és átadja a itemForm objektumot (ami tartalmazza a felhasználó által beírt adatokat). A szülő komponens figyelheti ezt az eseményt, és elvégezheti a mentési logikát (új teszt létrehozása vagy meglévő frissítése).

    - style: A komponenshez tartozó stílusok, amelyek csak erre a komponensre vonatkoznak a scoped attribútum miatt.
      - .needs-validation: Margó a tetején az űrlapnak.
      - .form-label: Félkövér betűstílus a címkékhez.
      - .mb-3: Alsó margó a div elemekhez.

## `Profile.vue` elemzése

Ez a Vue.js komponens egy felhasználói profil oldal megjelenítésére és szerkesztésére szolgál. Lehetővé teszi a felhasználónév, az e-mail cím és a jelszó módosítását, valamint a felhasználói fiók törlését.

### `<template>` (Sablon)

A sablon határozza meg a komponens HTML struktúráját.

1.  **`<div class="my-container" id="app">`**: A fő konténer, amely a teljes oldalt lefedi. Az `my-container` osztály egyedi stílusokat alkalmaz (háttérkép).

2.  **`<div class="container mt-5">`**: Egy Bootstrap konténer, amely a tartalom középre igazítására és felső margójának beállítására szolgál.

3.  **`<h1>Felhasználói Profil</h1>`**: A profil oldalának fő címe.

4.  **Felhasználónév (`<div class="card mb-3">`)**:

    - Egy Bootstrap kártya a felhasználónév megjelenítéséhez és szerkesztéséhez.
    - **`<div class="card-body d-flex justify-content-between align-items-center">`**: A kártya belső része, amely a felhasználónevet és a szerkesztés/mentés gombokat tartalmazza, flexbox elrendezéssel igazítva.
    - **`<p><strong>Felhasználó:</strong> {{ isEditingField === "name" ? "" : user.name }}</p>`**: Megjeleníti a felhasználónevet, kivéve ha a felhasználónév szerkesztése éppen folyamatban van (`isEditingField === "name"`).
    - **`<div v-if="isEditingField === 'name'" class="d-flex align-items-center">`**: Ez a rész csak akkor jelenik meg, ha a felhasználónév szerkesztése folyamatban van. Tartalmaz egy beviteli mezőt és a mentés/mégse gombokat.
      - `<input type="text" class="form-control me-2" v-model="updatedField.name" placeholder="Enter new username" />`: Szöveges beviteli mező az új felhasználónév megadásához. A `v-model` direktíva kétirányú adatkapcsolatot biztosít a `updatedField.name` adattal.
      - `<button class="btn btn-success me-2" @click="saveField('name')">Mentés</button>`: Mentés gomb, amely a `saveField` metódust hívja meg a 'name' paraméterrel.
      - `<button class="btn btn-secondary" @click="cancelEdit">Mégse</button>`: Mégse gomb, amely a `cancelEdit` metódust hívja meg a szerkesztés megszakításához.
    - **`<button v-else class="btn btn-warning" @click="startEdit('name')">Módosítás</button>`**: Módosítás gomb, amely csak akkor jelenik meg, ha a felhasználónév szerkesztése nincs folyamatban. A `startEdit` metódust hívja meg a 'name' paraméterrel a szerkesztés elindításához.

5.  **Email (`<div class="card mb-3">`)**:

    - Hasonló struktúrával rendelkezik, mint a felhasználónév rész, de az e-mail cím megjelenítésére és szerkesztésére szolgál.
    - A beviteli mező `type="email"` attribútummal rendelkezik.
    - A `startEdit`, `saveField` és `cancelEdit` metódusok az 'email' mezőre vonatkoznak.

6.  **Jelszó (`<div class="card mb-3">`)**:

    - Szintén hasonló struktúrájú, a jelszó módosítására szolgál.
    - A megjelenített jelszó helyén "**\*\***" látható.
    - A beviteli mező `type="password"` attribútummal rendelkezik.
    - A `startEdit`, `saveField` és `cancelEdit` metódusok a 'password' mezőre vonatkoznak.

7.  **Fiók törlése (`<button class="btn btn-danger mt-3" @click="deleteUser">`)**:
    - Egy piros Bootstrap gomb a felhasználói fiók törléséhez.
    - Az `@click` direktíva a `deleteUser` metódust hívja meg a gomb megnyomásakor.

### `<script>` (Szkript)

A szkript rész tartalmazza a komponens logikáját és adatait.

- **`import` állítások**: Importálja az `axios` könyvtárat HTTP kérésekhez, a `BASE_URL` konstanst az API alap URL-jéhez, és a `useAuthStore` tárolót a felhasználói autentikációs adatokhoz.

- **`data()`**: A komponens reaktív állapotának tárolására szolgáló adatok:

  - `user: {}`: Az aktuális felhasználó adatait tárolja.
  - `updatedField: {}`: Az éppen szerkesztett mező értékét tárolja.
  - `isEditingField: null`: Nyilvántartja, hogy melyik mező szerkesztése van folyamatban ('name', 'email', 'password' vagy `null`, ha nincs szerkesztés).
  - `store: useAuthStore()`: Az autentikációs tároló példánya.

- **`async created()`**: Egy lifecycle hook, amely a komponens létrehozásakor fut le.

  - Megpróbálja lekérni az aktuális felhasználó adatait az API-ból a `this.store.id` (felhasználó azonosító) és a `this.store.token` (autentikációs token) segítségével.
  - A sikeres válasz esetén a `this.user` adattal tárolja a felhasználói adatokat.
  - Hiba esetén naplózza a konzolra a hibaüzenetet.

- **`methods`**: A komponens metódusai:
  - **`startEdit(field)`**: Elindítja egy adott mező szerkesztését. Beállítja az `isEditingField` értékét a szerkesztendő mező nevére, és inicializálja az `updatedField` objektumot a jelenlegi mező értékével.
  - **`cancelEdit()`**: Megszakítja a szerkesztést. Visszaállítja az `isEditingField` értékét `null`-ra és kiüríti az `updatedField` objektumot.
  - **`async saveField(field)`**: Elküldi a szerkesztett mező új értékét az API-nak.
    - Létrehoz egy `updatedUser` objektumot, amely tartalmazza a jelenlegi felhasználói adatokat, de a megadott `field` értékét frissíti a `this.updatedField[field]` értékével.
    - HTTP PATCH kérést küld a felhasználó adatainak frissítéséhez az API-n.
    - A sikeres válasz esetén ellenőrzi, hogy a szerver válasza tartalmaz-e hibaüzenetet (pl. "This email already exists"). Ha igen, megjelenít egy hibaüzenetet.
    - Ha a frissítés sikeres, megjelenít egy sikeres üzenetet, frissíti a `this.user` objektumot a szerver válaszával, és megszakítja a szerkesztést a `cancelEdit()` meghívásával.
    - Ha az e-mail címet vagy a jelszót frissítették, figyelmezteti a felhasználót, hogy újra be kell jelentkeznie, törli a tárolt autentikációs adatokat, és átirányítja a bejelentkezési oldalra.
    - Hiba esetén naplózza a konzolra a hibaüzenetet és megjelenít egy általános hibaüzenetet.
  - **`async deleteUser()`**: Megjelenít egy megerősítő párbeszédablakot a felhasználó fiókjának törléséhez.
    - Ha a felhasználó megerősíti a törlést, HTTP DELETE kérést küld a felhasználói fiók törléséhez az API-n.
    - Sikeres törlés esetén megjelenít egy sikeres üzenetet, törli a tárolt autentikációs adatokat, és átirányítja a regisztrációs oldalra.
    - Hiba esetén naplózza a konzolra a hibaüzenetet és megjelenít egy hibaüzenetet.

### `<style scoped>` (Stílusok)

A `scoped` attribútum biztosítja, hogy ezek a stílusok csak erre a komponensre vonatkozzanak.

- **`.container`**: A profil tartalmának stílusozása (maximális szélesség, középre igazítás, padding, háttérszín, lekerekített sarkok, szegély, árnyék, függőleges pozicionálás).
- **`.my-container`**: A teljes háttér stílusozása (magasság, szélesség, margó/padding eltávolítása, háttérkép, méretezés, pozicionálás).
- **`.card`**: A felhasználónév, e-mail és jelszó kártyáinak stílusozása (háttérszín, szegély, lekerekített sarkok, árnyék).
- **`.card-body`**: A kártya belső részének stílusozása (flexbox elrendezés, térköz, igazítás, padding).
- **`h1`**: A fő cím stílusozása (méret, igazítás, szín, árnyék).
- **`button`**: Az összes gomb alapértelmezett stílusozása (padding, lekerekített sarkok, betűméret, áttűnés).
- **`.btn-warning`**: A módosítás gomb stílusozása (háttérszín, szegély, szövegszín, hover effekt).
- **`.btn-success`**: A mentés gomb stílusozása (háttérszín, szegély, hover effekt).
- **`.btn-secondary`**: A mégse gomb stílusozása (háttérszín, szegély, hover effekt).
- **`.btn-danger`**: A törlés gomb stílusozása (háttérszín, szélesség, betűvastagság, hover effekt).
- **`input`**: A beviteli mezők stílusozása (szegély, lekerekített sarkok, padding, háttérszín, fókusz stílus).

* Kijelentkezés:

```js
async LogoutAndCloseNavbar() {
      this.closeNavbar();
      await this.Logout();
    },
    async Logout() {
      const url = `${BASE_URL}/users/logout`;
      const headers = {
        Accept: "application/json",
        Authorization: `Bearer ${this.store.token}`,
      };
      try {
        await axios.post(url, null, { headers });
      } catch (error) {
        console.error("Error:", error);
      }

      // Töröld a felhasználói adatokat a store-ból és a localStorage-ból
      this.store.clearStoredData();

      // Kényszerített oldalfrissítés
      window.location.reload(); // Ezzel frissíti az oldalt és törli a helyben tárolt adatokat
    },
```

- `LogoutAndCloseNavbar()` és `Logout()` Metódusok Elemzése

Ez a két aszinkron metódus a felhasználó kijelentkezési folyamatát kezeli egy Vue.js komponensben.

- `LogoutAndCloseNavbar()`

Ez az aszinkron metódus két másik metódust hív meg egymás után:

1.  `this.closeNavbar();`: Ez a sor meghívja a komponens egy másik metódusát (`closeNavbar`), amely feltehetően bezár egy navigációs sávot vagy menüt a felhasználói felületen. Mivel a kódja nem látható, a pontos működése nem ismert, de a neve alapján ez a funkciója.

2.  `await this.Logout();`: Ez a sor aszinkron módon meghívja a `Logout()` metódust. Az `await` kulcsszó biztosítja, hogy a `Logout()` metódus befejeződjön, mielőtt a `LogoutAndCloseNavbar()` metódus folytatódna.

- `Logout()`

Ez az aszinkron metódus felelős a felhasználó tényleges kijelentkeztetéséért a szerverről és a kliens oldali adatok törléséért.

1.  `const url = ${BASE_URL}/users/logout;`: Létrehozza a kijelentkezési API végpontjának URL-jét a `BASE_URL` konstans és a `/users/logout` útvonal összefűzésével. A `BASE_URL` egy környezeti változóban van definiálva.

2.  `const headers = { Accept: "application/json", Authorization: Bearer ${this.store.token}, };`: Létrehozza a HTTP kérés fejlécét.

    - `Accept: "application/json"`: Jelzi a szervernek, hogy a kliens JSON formátumú választ vár.
    - `Authorization: Bearer ${this.store.token}`: Tartalmazza a felhasználó autentikációs tokenjét a kérés engedélyezéséhez. A token a `this.store` objektumból (`useAuthStore`-ból származó tároló) kerül beolvasásra.

3.  `try...catch` blokk: Ez a blokk kezeli az API hívás során esetlegesen fellépő hibákat.

    - `await axios.post(url, null, { headers });`: Aszinkron módon POST kérést küld a kijelentkezési URL-re.
      - `url`: A kijelentkezési API végpontja.
      - `null`: A POST kéréshez nincs szükség kérés törzsre a legtöbb kijelentkezési implementációban.
      - `{ headers }`: Az előzőleg létrehozott fejlécekkel küldi a kérést.
    - `catch (error) { console.error("Error:", error); }`: Ha a `axios.post()` hívás hibát dob, ez a blokk lefut. A hibaobjektumot a konzolra naplózza a hibakeresés megkönnyítése érdekében.

4.  `this.store.clearStoredData();`: Ez a sor meghívja a `this.store` objektum (`useAuthStore` tároló) `clearStoredData()` metódusát. Ez a metódus törli a felhasználói adatokat (beleértve a tokent is) a tárolóból.

5.  `window.location.reload();`: Ez a sor kényszerített oldalfrissítést hajt végre. Ez a drasztikus lépést gyakran alkalmazzák a kijelentkezés után annak biztosítására, hogy minden érzékeny adat törlődjön a kliens oldali memóriából és a tárolóból.

### Amit az admin lát:

## `CategoriesAdminView.vue` elemzése

Ez a Vue.js komponens egy adminisztrátori felületet biztosít a témakörök és azokhoz tartozó források kezeléséhez. Lehetővé teszi a témakörök listázását, szűrését, létrehozását, módosítását és törlését, valamint a témakörökhöz tartozó források kezelését.

### `<template>` (Sablon)

A sablon határozza meg a komponens HTML struktúráját.

1.  **`<div class="my-container">`**: A fő konténer, amely a teljes oldalt lefedi. Az `my-container` osztály egyedi stílusokat alkalmaz (háttérkép).

2.  **`<div class="container my-container-height">`**: Egy Bootstrap konténer, amely a tartalom középre igazítására és minimális magasságának beállítására szolgál.

3.  **`<h1>Témakörök kezelése</h1>`**: A témakörök kezelési oldalának fő címe.

4.  **Szűrés és gomb (`<div class="d-flex ...">`)**: Egy flexbox elrendezésű div, amely a szűrési lehetőséget és a létrehozás gombot tartalmazza egy sorban.

    - **Szűrés (`<div class="filter-container ...">`)**:
      - `<label for="levelFilter" class="me-2">Szűrés szint szerint:</label>`: Felirat a szint szerinti szűréshez.
      - `<select v-model="selectedLevel" class="form-select" style="width: auto; display: inline-block"> ... </select>`: Legördülő menü a témakörök szint szerinti szűréséhez. A `v-model="selectedLevel"` kétirányú adatkapcsolatot biztosít a kiválasztott érték és a `selectedLevel` adattulajdonság között. Az opciók a "Mindegyik", "Közép" és "Emelt" szintek.
    - **Létrehozás gomb (`<div class="flex-shrink-0">`)**:
      - `<OperationsCrudCategories @onClickCreateButton="onClickCreateCategoryButton" />`: Egy egyedi komponens, amely valószínűleg a témakör létrehozásához szükséges gombot tartalmazza. Az `@onClickCreateButton` eseménykötés a `onClickCreateCategoryButton` metódust hívja meg a gomb kattintásakor.

5.  **Témakörök listája (`<div v-for="category in filteredCategories" ...>`)**: Egy `v-for` direktívával iterál a `filteredCategories` computed tulajdonság elemein (amely a `categories` tömb szűrt változata). Minden témakörhöz létrehoz egy div elemet.

    - `:key="category.id"`: A Vue.js hatékony listarendezéséhez szükséges egyedi kulcs.
    - `:class="{ active: category.id === selectedRowId }"`: Dinamikusan alkalmazza az `active` CSS osztályt, ha az aktuális témakör azonosítója megegyezik a `selectedRowId`-vel (valószínűleg a kiválasztott témakör kiemelésére szolgál).
    - `@click="selectCategory(category.id)"`: Eseménykötés, amely a `selectCategory` metódust hívja meg a témakörre kattintáskor, átadva a témakör azonosítóját.
    - `<CategoryCard ... />`: Egy egyedi komponens, amely egyetlen témakör részletes információit és műveleteit jeleníti meg.
      - `:category="category"`: Átadja az aktuális témakör objektumot a kártyának.
      - `:saveCategory="saveCategory"`: Átadja a `saveCategory` metódust a kártyának ( a témakör kidolgozásának helyben történő szerkesztéséhez).
      - `:sources="sources[category.id] || []"`: Átadja a témakörhöz tartozó források tömbjét a `sources` objektumból (ha létezik, egyébként egy üres tömböt).
      - `:onClickDeleteCategoryButton="onClickDeleteCategoryButton"`: Átadja a `onClickDeleteCategoryButton` metódust a törlés gombhoz.
      - `:onClickUpdateCategoryButton="onClickUpdateCategoryButton"`: Átadja a `onClickUpdateButton` metódust a módosítás gombhoz.
      - `:onClickDeleteSourceButton="onClickDeleteSourceButton"`: Átadja a `onClickDeleteSourceButton` metódust a forrás törlés gombjához.
      - `:onClickUpdateSourceButton="onClickUpdateSourceButton"`: Átadja a `onClickUpdateSourceButton` metódust a forrás módosítás gombjához.
      - `:onClickCreateSourceButton="onClickCreateSourceButton"`: Átadja a `onClickCreateSourceButton` metódust az új forrás létrehozás gombjához.

6.  **Témakör hozzáadása modal (`<Modal ...>`)**: Egy egyedi modális ablak komponens.
    - `:title="title"`, `:yes="yes"`, `:no="no"`, `:size="size"`: Propok a modális ablak címének, "igen" gombjának szövegének, "nem" gombjának szövegének és méretének beállításához.
    - `@yesEvent="yesEventHandler"`: Eseménykötés, amely a `yesEventHandler` metódust hívja meg a modális ablak "igen" gombjának kattintásakor.
    - **`<div v-if="state == 'Delete' || state == 'Delete2'"> {{ messageYesNo }} </div>`**: A törlés megerősítő üzenetének megjelenítése, ha a `state` értéke "Delete" (témakör törlése) vagy "Delete2" (forrás törlése).
    - **`<CategoryForm v-if="state == 'Create' || state == 'Update'" ... />`**: Egy egyedi űrlap komponens a témakörök létrehozásához vagy módosításához. Csak akkor jelenik meg, ha a `state` értéke "Create" vagy "Update".
      - `:itemForm="category"`: Átadja az aktuális `category` objektumot az űrlapnak.
      - `@saveItem="saveItemHandler"`: Eseménykötés, amely a `saveItemHandler` metódust hívja meg az űrlap "mentés" eseményének bekövetkezésekor.
    - **`<SourceForm v-if="state == 'Create2' || state == 'Update2'" ... />`**: Egy egyedi űrlap komponens a források létrehozásához vagy módosításához. Csak akkor jelenik meg, ha a `state` értéke "Create2" vagy "Update2".
      - `:itemForm="source"`: Átadja az aktuális `source` objektumot az űrlapnak.
      - `@saveItem="saveSourceHandler"`: Eseménykötés, amely a `saveSourceHandler` metódust hívja meg az űrlap "mentés" eseményének bekövetkezésekor.

### `<script>` (Szkript)

A szkript rész tartalmazza a komponens logikáját és adatait.

- **`class Category { ... }`**: Egy egyszerű JavaScript osztály a témakör objektumok létrehozásához.
- **`class Source { ... }`**: Egy egyszerű JavaScript osztály a forrás objektumok létrehozásához.
- **`import` állítások**: Importálja a szükséges modulokat és komponenseket (`axios`, `BASE_URL`, `useAuthStore`, `CategoryCard`, `CategoryForm`, `OperationsCrudCategories`, Bootstrap, `SourceForm`).
- **`components`**: Regisztrálja a használt egyedi komponenseket.
- **`data()`**: A komponens reaktív állapotának tárolására szolgáló adatok:
  - `urlApiCategory`, `urlApiSource`: Az API végpontjai a témakörökhöz és forrásokhoz.
  - `categories: []`: A lekérdezett témakörök tömbje.
  - `categoryById: []`: Egy adott témakör adatainak tárolására (lehet, hogy nincs széleskörűen használva).
  - `sources: {}`: Egy objektum, ahol a kulcsok a témakörök azonosítói, az értékek pedig az adott témakörhöz tartozó források tömbjei.
  - `selectedLevel: ""`: A kiválasztott szűrési szint.
  - `store: useAuthStore()`: Az autentikációs tároló példánya.
  - `selectedRowId: null`: A kiválasztott sor (témakör vagy forrás) azonosítója.
  - `messageYesNo: null`: Az igen/nem típusú modális üzenete.
  - `state: "Read"`: A komponens aktuális állapota (CRUD műveletek különböző entitásokhoz).
  - `title`, `yes`, `no`, `size`: A modális ablak konfigurációs opciói.
  - `category: new Category()`: Az aktuálisan létrehozott vagy módosított témakör objektuma.
  - `source: new Source()`: Az aktuálisan létrehozott vagy módosított forrás objektuma.
  - `selectedCategoryId: null`: A kiválasztott témakör azonosítója (forrás létrehozásához).
- **`computed`**: Számított tulajdonságok:
  - `filteredCategories()`: Visszaadja a `categories` tömb szűrt változatát a `selectedLevel` alapján. Ha nincs kiválasztott szint, az eredeti tömböt adja vissza.
- **`async mounted()`**: Lifecycle hook, amely a komponens DOM-ba illesztése után fut le. Lekérdezi a témaköröket és a forrásokat, valamint inicializál egy Bootstrap modal példányt.
- **`methods`**: A komponens metódusai a témakörök és források lekérdezéséhez, kiválasztásához, létrehozásához, módosításához és törléséhez, valamint a modális ablak kezeléséhez.

### `<style scoped>` (Stílusok)

A `scoped` attribútum biztosítja, hogy ezek a stílusok csak erre a komponensre vonatkozzanak.

- `.my-container-height`: Beállítja a minimális magasságot a fő konténernek.
- `.my-container`: A fő konténer stílusozása (háttérkép, méretek, középre igazítás).
- `.container`: A tartalom konténerének stílusozása (maximális szélesség, margók, padding, háttérszín, lekerekített sarkok, árnyék).
- `.filter-container`: A szűrő elemek elrendezésének stílusozása (flexbox, igazítás).
- `@media (max-width: 768px)`: Reszponzív stílusok kisebb képernyőkre, a szűrő elemek és a létrehozás gomb teljes szélességűvé válnak.

## `CategoriesForm.vue` Komponens Elemzése

Ez a Vue.js komponens egy űrlapot jelenít meg új témakörök létrehozásához vagy meglévő témakörök módosításához. Bootstrap stílusokat használ az űrlap elemeinek megjelenítéséhez és validálásához.

### `<template>` (Sablon)

A sablon határozza meg az űrlap HTML struktúráját.

1.  **`<form @submit.prevent="onClickSubmit" class="row g-3 needs-validation was-validated">`**: A fő űrlap elem.

    - `@submit.prevent="onClickSubmit"`: Megakadályozza az alapértelmezett űrlapküldési viselkedést és meghívja az `onClickSubmit` metódust az űrlap elküldésekor.
    - `class="row g-3 needs-validation was-validated"`: Bootstrap osztályok az űrlap elrendezéséhez (grid rendszer) és a kliens oldali validációhoz. A `was-validated` osztály jelzi, hogy a validáció már megtörtént.

2.  **`<div class="mb-3">`**: Egy Bootstrap margóval ellátott div a "Témakör neve" beviteli mezőjének.

    - `<label for="category" class="form-label">A témakör neve:</label>`: Felirat a témakör nevének beviteli mezőjéhez.
    - `<input type="text" class="form-control" id="category" required v-model="itemForm.category" />`: Szöveges beviteli mező a témakör nevének megadásához.
      - `type="text"`: Szöveges beviteli mező.
      - `class="form-control"`: Bootstrap osztály a beviteli mező stílusozásához.
      - `id="category"`: Azonosító a label-hez való kapcsoláshoz.
      - `required`: HTML5 attribútum, amely jelzi, hogy a mező kitöltése kötelező.
      - `v-model="itemForm.category"`: Kétirányú adatkapcsolatot biztosít a beviteli mező értéke és a komponens `itemForm` propjának `category` tulajdonsága között.

3.  **`<div class="col-md-4 position-relative">`**: Egy Bootstrap grid oszlop a "Szint" legördülő menüjének. A `position-relative` osztály a validációs visszajelzések pozicionálásához lehet szükséges.

    - `<label for="Level" class="form-label">Szint</label>`: Felirat a szint kiválasztóhoz.
    - `<select v-model="itemForm.level" id="Level" class="form-select" required>`: Legördülő menü a témakör szintjének kiválasztásához.
      - `v-model="itemForm.level"`: Kétirányú adatkapcsolatot biztosít a kiválasztott opció értéke és a komponens `itemForm` propjának `level` tulajdonsága között.
      - `id="Level"`: Azonosító a label-hez való kapcsoláshoz.
      - `class="form-select"`: Bootstrap osztály a legördülő menü stílusozásához.
      - `required`: HTML5 attribútum, amely jelzi, hogy a mező kiválasztása kötelező.
      - `<option value="közép">Közép</option>` és `<option value="emelt">Emelt</option>`: A választható szintek.

4.  **`<button type="submit" class="btn btn-success">Mentés</button>`**: Az űrlap elküldésére szolgáló gomb.
    - `type="submit"`: Az űrlap elküldését indítja el.
    - `class="btn btn-success"`: Bootstrap osztály a sikeres műveletet jelző zöld gomb stílusozásához.
    - `"Mentés"`: A gomb felirata.

### `<script>` (Szkript)

A szkript rész tartalmazza a komponens logikáját.

- **`props: ["itemForm"]`**: Deklarál egy `itemForm` nevű propot, amely várhatóan egy objektum lesz (a témakör adatait tartalmazza). Ez a prop az űrlap mezőinek `v-model` direktíváival van összekötve.

- **`emits: ["saveItem"]`**: Deklarál egy `saveItem` nevű eseményt, amelyet a komponens kibocsát, amikor az űrlapot elküldik.

- **`methods: { onClickSubmit() { this.$emit("saveItem", this.itemForm); }, }`**: Tartalmaz egyetlen metódust:
  - **`onClickSubmit()`**: Ez a metódus akkor hívódik meg, amikor az űrlapot elküldik (a `@submit.prevent` miatt). Kibocsátja a `saveItem` eseményt, és átadja az `itemForm` prop aktuális értékét (amely tartalmazza a felhasználó által bevitt vagy módosított adatokat). Az `$emit` egy Vue.js metódus a komponensből szülő komponens felé történő egyéni események kibocsátására.

### `<style scoped>` (Stílusok)

A `scoped` attribútum biztosítja, hogy ezek a stílusok csak erre a komponensre vonatkozzanak.

- `.card`: Általános stílus egy kártya elemhez (átmenet az animációhoz).
- `.card:hover`: Stílus a kártya elem fölé húzásakor (enyhe árnyék).

## `CategoryEditModal.vue` Komponens Részletes Elemzése

Ez a Vue.js komponens egy Bootstrap modális ablakot valósít meg, amely lehetővé teszi a témakörök részletes leírásának szerkesztését egy beágyazott `QuillEditor` komponens segítségével.

### `<template>` (Sablon)

A sablon definiálja a modális ablak vizuális struktúráját.

1.  **`<div class="modal fade" :id="modalId" tabindex="-1" aria-hidden="true" ref="modal">`**: A Bootstrap modális ablakának legkülső konténere.

    - `class="modal fade"`: Bootstrap osztályok a modális ablak animált megjelenítéséhez és elrejtéséhez.
    - `:id="modalId"`: Dinamikusan kötött `id` attribútum, amely a komponens számított `modalId` tulajdonságából származik. Ez biztosítja, hogy minden témakör szerkesztő modalja egyedi azonosítóval rendelkezzen.
    - `tabindex="-1"`: Biztosítja, hogy a modális ablak ne legyen fókuszálható a tab billentyűvel.
    - `aria-hidden="true"`: Jelzi a képernyőolvasók számára, hogy a modális ablak alapértelmezés szerint rejtett.
    - `ref="modal"`: Referencia a DOM elemhez, amely lehetővé teszi a JavaScript kód számára a közvetlen elérését (itt a Bootstrap Modal példányosításához).

2.  **`<div class="modal-dialog">`**: A modális ablak tényleges tartalmának (fejléc, törzs, lábléc) a megfelelő méretezéséért és pozicionálásáért felelős Bootstrap osztály.

3.  **`<div class="modal-content">`**: A modális ablak belső konténere, amely a hátteret, a szegélyeket és az árnyékolást biztosítja.

4.  **`<div class="modal-header">`**: A modális ablak fejléc része.

    - `<h5 class="modal-title">{{ category.category }}</h5>`: A modális ablak címe, amely a `category` propból érkező témakör nevét jeleníti meg. A dupla kapcsos zárójelek közötti rész Vue.js sablon szintaxist használ az adat kötéséhez.
    - `<button type="button" class="btn-close" @click="closeModal"></button>`: Egy Bootstrap stílusú gomb a modális ablak bezárásához. Az `@click` direktíva a `closeModal` metódust hívja meg a gomb kattintásakor.

5.  **`<div class="modal-body"> <QuillEditor v-model="tempText" /> </div>`**: A modális ablak tartalmi része.

    - `<QuillEditor v-model="tempText" />`: A beágyazott `QuillEditor` komponens, amely egy gazdag szövegszerkesztőt biztosít. A `v-model="tempText"` direktíva kétirányú adatkapcsolatot hoz létre a `QuillEditor` tartalma és a `CategoryEditModal` komponens `tempText` adattulajdonsága között.

6.  **`<div class="modal-footer">`**: A modális ablak lábléc része, amely a műveleti gombokat tartalmazza.
    - `<button type="button" class="btn btn-secondary" @click="closeModal"> Mégse </button>`: Egy másodlagos Bootstrap stílusú gomb, amely a `closeModal` metódust hívja meg a modális ablak bezárásához a változtatások mentése nélkül.
    - `<button type="button" class="btn btn-primary" @click="saveChanges"> Mentés </button>`: Egy elsődleges Bootstrap stílusú gomb, amely az `saveChanges` metódust hívja meg a szerkesztett tartalom mentéséhez.

### `<script>` (Szkript)

A `<script>` tag tartalmazza a komponens JavaScript logikáját.

- **`import QuillEditor from "@/components/Editor/QuillEditor.vue";`**: Importálja a `QuillEditor` nevű Vue.js komponenst egy megadott elérési útról. Ez a komponens felelős a gazdag szövegszerkesztő megjelenítéséért és működéséért.
- **`import { Modal } from "bootstrap";`**: Importálja a `Modal` osztályt a Bootstrap JavaScript moduljaiból. Ez az osztály szükséges a Bootstrap modális ablakának programozott vezérléséhez (megjelenítés, elrejtés).

- **`export default { ... }`**: Exportálja a Vue.js komponens definíciós objektumát.

  - **`components: { QuillEditor }`**: Regisztrálja a `QuillEditor` komponenst, hogy használható legyen a sablonban.

  - **`props: { ... }`**: Deklarálja a komponens által elfogadott propokat (szülő komponensből érkező adatok).

    - `category: Object`: Vár egy `category` nevű objektumot, amely valószínűleg a szerkesztendő témakör adatait tartalmazza (beleértve a `category.category` címet és a `category.text` leírást).
    - `saveCategory: Function`: Vár egy `saveCategory` nevű függvényt, amelyet a szülő komponens ad át. Ezt a függvényt kell meghívni a szerkesztett leírás mentésekor. A megjegyzés (`// A mentési függvény`) dokumentálja a prop célját.

  - **`data() { ... }`**: Egy függvény, amely a komponens reaktív állapotát definiáló adattulajdonságokat adja vissza.

    - `tempText: ""` (`// Ideiglenes szöveg a szerkesztőhöz`): Egy üres string, amely a `QuillEditor`-ban szerkesztett szöveget tárolja ideiglenesen.
    - `modalInstance: null` (`// Bootstrap modal objektum`): Egy változó, amely a Bootstrap Modal osztályból létrehozott példányt fogja tárolni. Ez az objektum teszi lehetővé a modális ablak programozott vezérlését.

  - **`computed: { ... }`**: Számított tulajdonságok, amelyek függenek a reaktív adatoktól és automatikusan frissülnek, ha a függőségeik megváltoznak.

    - `modalId()`: Egy függvény, amely egyedi azonosítót generál a modális ablakhoz a `category.id` alapján (`editModal-${this.category.id}`). Ez biztosítja, hogy minden kategóriához külön modális ablak tartozzon egyedi `id`-val.

  - **`watch: { ... }`**: Figyelők, amelyek bizonyos adattulajdonságok változásaira reagálnak.

    - `"category.text": { ... }`: Figyeli a `category.text` prop változását.
      - `handler(newText) { this.tempText = newText; }`: Amikor a `category.text` megváltozik, a `tempText` is frissül az új értékkel. Ez biztosítja, hogy a szerkesztőben mindig a legfrissebb leírás jelenjen meg a kategóriához.
      - `immediate: true`: Ez az opció azt jelenti, hogy a figyelő a komponens létrehozásakor is lefut egyszer, így a `tempText` kezdeti értéke is beállításra kerül a `category.text` alapján.

  - **`mounted() { ... }`**: Egy lifecycle hook, amely a komponens DOM-ba illesztése után fut le.

    - `this.modalInstance = new Modal(this.$refs.modal);`: Létrehoz egy új Bootstrap `Modal` példányt. A `this.$refs.modal` a sablonban a `ref="modal"` attribútummal ellátott `div` DOM elemre hivatkozik. Ezáltal a komponens képes programozottan vezérelni a modális ablak megjelenítését és elrejtését.

  - **`methods: { ... }`**: A komponensben definiált metódusok, amelyek különböző funkciókat valósítanak meg.
    - `openModal()`:
      - `this.tempText = this.category.text;`: Beállítja a `tempText` értékét a `category.text` aktuális értékére, így a szerkesztőben a legfrissebb leírás jelenik meg a megnyitáskor. A megjegyzés (`// Szöveg beállítása a szerkesztőbe`) dokumentálja a sor célját.
      - `this.modalInstance.show();`: Meghívja a Bootstrap `Modal` példány `show()` metódusát, amely megjeleníti a modális ablakot a felhasználó számára.
    - `closeModal()`:
      - `this.tempText = this.category.text;`: Visszaállítja a `tempText` értékét az eredeti `category.text` értékére. Ez azért van, hogy ha a felhasználó nem menti a változtatásokat, a `tempText` ne tartsa meg a szerkesztett verziót.
      - `this.modalInstance.hide();`: Meghívja a Bootstrap `Modal` példány `hide()` metódusát, amely elrejti a modális ablakot.
    - `async saveChanges()`:
      - `this.category.text = this.tempText;`: Frissíti a `category` prop `text` tulajdonságát a `tempText` aktuális tartalmával (a szerkesztett szöveggel). Fontos megjegyezni, hogy a propok általában csak lefelé áramlanak (szülőtől gyermek felé), így ennek a sornak a hatása a szülő komponensben nem azonnali. A szülő komponensnek kell figyelnie a változásokat vagy a kibocsátott eseményeket. A megjegyzés (`// Frissítés a kategória objektumban`) leírja a célját.
      - `await this.saveCategory(this.category);`: Meghívja a szülő komponensből kapott `saveCategory` függvényt, és átadja neki a frissített `category` objektumot. Az `await` kulcsszó arra utal, hogy ez a függvény valószínűleg egy aszinkron műveletet hajt végre (pl. API hívás). A megjegyzés (`// Küldés a szerverre`) ezt erősíti meg.
      - `this.modalInstance.hide();`: Meghívja a Bootstrap `Modal` példány `hide()` metódusát a modális ablak bezárásához a mentés után.

## `QuillEditor.vue` Komponens Részletes Elemzése

Ez a Vue.js komponens egy beágyazott [Quill](https://quilljs.com/) gazdag szövegszerkesztőt valósít meg. Kétirányú adatkapcsolatot biztosít a `v-model` direktívával, lehetővé téve a gazdag szöveges tartalom szerkesztését és integrálását a Vue.js alkalmazásba.

### `<template>` (Sablon)

A sablon egyetlen `div` elemet tartalmaz, amely a Quill szerkesztő konténereként szolgál.

- **`<div ref="editor" class="editor"></div>`**: Ez a `div` elem lesz az a hely, ahová a Quill szerkesztő példánya be lesz illesztve a `mounted()` lifecycle hook-ban. A `ref="editor"` attribútum egy referenciát hoz létre ehhez a DOM elemhez, amely a JavaScript kódból elérhető lesz (`this.$refs.editor`). A `class="editor"` egy CSS osztály, amely a szerkesztő alapvető stílusait (jelen esetben a magasságát) határozza meg a `<style scoped>` szekcióban.

### `<script>` (Szkript)

A `<script>` tag tartalmazza a komponens JavaScript logikáját.

- **`import { onMounted, ref, watch } from "vue";`**: Importálja a `onMounted`, `ref` és `watch` Vue.js funkciókat a Composition API használatához (bár a komponens Options API-t használ). Valószínűleg korábbi kísérletek vagy refaktorálás nyomai.
- **`import Quill from "quill";`**: Importálja a `Quill` osztályt a `quill` npm csomagból. Ez az osztály a gazdag szövegszerkesztő funkcionalitásának alapját képezi.
- **`import "quill/dist/quill.snow.css";`**: Importálja a "snow" témához tartozó alapértelmezett stílusokat a Quill szerkesztő megjelenítéséhez.

- **`export default { ... }`**: Exportálja a Vue.js komponens definíciós objektumát.

  - **`props: { ... }`**: Deklarálja a komponens által elfogadott propokat.

    - `modelValue: String` (`// 🔹 A v-model értékét fogadja`): Egy string típusú prop, amely a `v-model` direktívával kötött értéket fogadja a szülő komponensből. Ez a prop tartalmazza a szerkesztő kezdeti tartalmát. A megjegyzés kiemeli a `v-model` integrációját.

  - **`emits: ["update:modelValue"]`**: Deklarálja a komponens által kibocsátott eseményeket.

    - `"update:modelValue"` (`// 🔹 Kibocsátja a frissített értéket`): Ez az esemény kerül kibocsátásra, amikor a szerkesztő tartalma megváltozik. A `v-model` direktíva működéséhez elengedhetetlen, mivel a szülő komponens ezen az eseményen keresztül értesül a változásokról és frissítheti a kötött adatot. A megjegyzés hangsúlyozza a `v-model` működését.

  - **`data() { ... }`**: Egy függvény, amely a komponens reaktív állapotát definiáló adattulajdonságokat adja vissza.

    - `editor: null` (`// Quill példány tárolása`): Egy változó, amely a Quill szerkesztő példányára fog hivatkozni a `mounted()` hook-ban.

  - **`mounted() { ... }`**: Egy lifecycle hook, amely a komponens DOM-ba illesztése után fut le.
    - `this.editor = new Quill(this.$refs.editor, { ... });`: Létrehoz egy új `Quill` szerkesztő példányt.
      - Az első argumentum a szerkesztő konténerének DOM eleme (`this.$refs.editor`).
      - A második argumentum egy konfigurációs objektum:
        - `theme: "snow"`: Beállítja a szerkesztő vizuális témáját "snow"-ra.
        - `modules: { toolbar: [...] }`: Konfigurálja a szerkesztő eszköztárát. Az itt megadott tömb határozza meg, hogy mely formázási lehetőségek lesznek elérhetők a felhasználó számára (fejlécek, betűtípus, listák, stílusok, igazítás, link, kép, szín, háttérszín).
    - `this.editor.root.innerHTML = this.modelValue || "";`: Beállítja a szerkesztő kezdeti tartalmát a `modelValue` prop értékével. Ha a `modelValue` undefined vagy null, akkor egy üres stringet használ. A megjegyzés (`//  Beállítjuk a kezdeti tartalmat`) ezt dokumentálja.
    - `this.editor.on("text-change", () => { ... });`: Hozzáad egy eseményfigyelőt a szerkesztő "text-change" eseményére. Ez az esemény akkor következik be, amikor a felhasználó módosítja a szerkesztő tartalmát.
      - `this.$emit("update:modelValue", this.editor.root.innerHTML);`: A callback függvényben kibocsátja az `"update:modelValue"`

* `AdminView.vue` elemzése

Ez a Vue.js komponens egy adminisztrátori felületet biztosít a felhasználók kezeléséhez, különös tekintettel a felhasználói szerepkörök módosítására.

- `<template>` (Sablon)

A sablon határozza meg a komponens HTML struktúráját.

1.  **`<div class="my-container">`**: A fő konténer, amely a teljes oldalt lefedi. Az `my-container` osztály egyedi stílusokat alkalmaz (háttérkép, középre igazítás).

2.  **`<div class="admin-container">`**: Egy konténer, amely a felhasználók kezeléséhez tartozó elemeket foglalja magában. Stílusozása korlátozza a szélességét, háttérszínt, paddingot, lekerekített sarkokat, árnyékot és szegélyt ad.

3.  **`<h2 class="title">Felhasználók kezelése</h2>`**: A felhasználókezelési oldal fő címe.

4.  **`<table class="table user-table">`**: Egy HTML táblázat a felhasználók adatainak megjelenítésére. A `table` és `user-table` osztályok stílusokat alkalmaznak a táblázat megjelenésére.
    - **`<thead>`**: A táblázat fejléce, amely a következő oszlopokat tartalmazza: "ID", "Felhasználónév", "Szerepkör", "Művelet".
    - **`<tbody>`**: A táblázat tartalmi része, ahol a `v-for="user in users" :key="user.id"` direktíva iterál a `users` tömb elemein, és minden felhasználóhoz létrehoz egy táblázatsort (`<tr>`).
      - **`<td>{{ user.id }}</td>`**: Megjeleníti a felhasználó azonosítóját.
      - **`<td>{{ user.name }}</td>`**: Megjeleníti a felhasználó nevét.
      - **`<td><select v-model="user.roleId" class="form-select role-select"> ... </select></td>`**: Egy legördülő menü (select elem) a felhasználó szerepkörének kiválasztásához.
        - `v-model="user.roleId"`: Kétirányú adatkapcsolatot biztosít a kiválasztott opció értéke és a `user` objektum `roleId` tulajdonsága között.
        - `<option v-for="role in roles" :key="role.id" :value="role.id"> {{ role.role }} </option>`: Iterál a `roles` tömb elemein, és minden szerepkörhöz létrehoz egy opciót a legördülő menüben. Az opció értéke a `role.id`, a megjelenített szöveg pedig a `role.role`.
      - **`<td><button class="btn save-btn" @click="updateRole(user)" :disabled="user.id == store.id"> Mentés </button></td>`**: Egy "Mentés" gomb a felhasználó szerepkörének frissítéséhez.
        - `@click="updateRole(user)"`: A gomb kattintásakor meghívja az `updateRole` metódust, és átadja az aktuális `user` objektumot.
        - `:disabled="user.id == store.id"`: Letiltja a gombot, ha a megjelenített felhasználó azonosítója megegyezik a bejelentkezett adminisztrátor azonosítójával (megakadályozza, hogy az adminisztrátor saját szerepkörét módosítsa).

- `<script>` (Szkript)

A szkript rész tartalmazza a komponens logikáját és adatait.

- **`import` állítások**: Importálja az `axios` könyvtárat HTTP kérésekhez, a `BASE_URL` konstanst az API alap URL-jéhez, és a `useAuthStore` tárolót a felhasználói autentikációs adatokhoz.

- **`data()`**: A komponens reaktív állapotának tárolására szolgáló adatok:

  - `users: []`: Egy tömb, amely a lekérdezett felhasználói adatokat tárolja.
  - `roles: []`: Egy tömb, amely a lekérdezett szerepkörök adatait tárolja.
  - `store: useAuthStore()`: Az autentikációs tároló példánya, amely tartalmazza a bejelentkezett felhasználó adatait (pl. azonosítóját).

- **`methods`**: A komponens metódusai:

  - **`async fetchRoles()`**: Aszinkron módon lekérdezi az összes elérhető szerepkört az API-ból.
    - HTTP GET kérést küld a `${BASE_URL}/roles` végpontra a bejelentkezett felhasználó tokenjével a fejlécben.
    - A sikeres válaszban kapott adatokat (`response.data.data`) a `this.roles` tömbben tárolja.
    - Hiba esetén naplózza a konzolra a hibaüzenetet.
  - **`async fetchUsers()`**: Aszinkron módon lekérdezi az összes felhasználót a szerepkörükkel együtt az API-ból.
    - HTTP GET kérést küld a `${BASE_URL}/usersWithRoles` végpontra a bejelentkezett felhasználó tokenjével a fejlécben.
    - A sikeres válaszban kapott adatokat (`response.data`) a `this.users` tömbben tárolja.
    - Hiba esetén naplózza a konzolra a hibaüzenetet.
  - **`async updateRole(user)`**: Aszinkron módon frissíti a megadott felhasználó szerepkörét az API-ban.
    - HTTP PUT kérést küld a `${BASE_URL}/usersWithRoles/${user.id}/role` végpontra a felhasználó azonosítójával az URL-ben.
    - A kérés törzsében elküldi az új `roleId`-t.
    - A bejelentkezett felhasználó tokenjét a fejlécben küldi el az engedélyezéshez.
    - Hiba esetén naplózza a konzolra a hibaüzenetet.

- **`mounted()`**: Egy lifecycle hook, amely a komponens DOM-ba illesztése után fut le.
  - Meghívja a `fetchUsers()` metódust a felhasználók adatainak lekérdezéséhez.
  - Meghívja a `fetchRoles()` metódust a szerepkörök adatainak lekérdezéséhez.

* `<style scoped>` (Stílusok)

A `scoped` attribútum biztosítja, hogy ezek a stílusok csak erre a komponensre vonatkozzanak.

- `.my-container`: A fő konténer stílusozása (háttérkép, méretek, középre igazítás).
- `.admin-container`: Az adminisztrációs tartalom konténerének stílusozása (maximális szélesség, háttérszín, padding, lekerekített sarkok, árnyék, szegély, függőleges pozicionálás).
- `.title`: A fő cím stílusozása (betűméret, alsó margó, szín, igazítás).
- `.user-table`: A felhasználói táblázat stílusozása (szélesség, szegélyek összevonása, háttérszín).
- `.user-table th, .user-table td`: A táblázat fejléce és celláinak stílusozása (padding, szegély, igazítás, szín).
- `.user-table th`: A táblázat fejléceinek stílusozása (háttérszín, szövegszín).
- `.role-select`: A szerepkör kiválasztó legördülő menü stílusozása (háttérszín, szegély, szín, padding, lekerekített sarkok).
- `.save-btn`: A mentés gomb stílusozása (háttérszín, szövegszín, szegély, padding, lekerekített sarkok, áttűnés).
- `.save-btn:hover`: A mentés gomb hover állapotának stílusozása (sötétebb háttérszín).

## `QuestionsAnswersView.vue` Komponens Részletes Elemzése

Ez a Vue.js komponens egy nézet, amely lehetővé teszi a kérdések és válaszok kezelését. Két fő részből áll: egy adminisztrációs felület a kérdések listázásához, szerkesztéséhez és törléséhez, valamint egy oldalsó panel a témakörök kiválasztásához a kérdések szűréséhez. Egy modális ablakot is használ új kérdések létrehozásához és a meglévő kérdések szerkesztéséhez.

### `<template>` (Sablon)

A sablon határozza meg a komponens vizuális struktúráját.

1.  **`<div class="my-container row">`**: A fő konténer, amely egy Bootstrap sor elrendezést használ. A `my-container` osztály egyedi stílusokat alkalmaz a háttérkép és az elrendezés beállításához.

2.  **`<div class="admin-container col-12 col-md-12 col-xl-12 col-xxl-5">`**: Egy div, amely az adminisztrációs felületet tartalmazza a kérdések kezeléséhez. Bootstrap oszlop osztályokat használ a reszponzív elrendezéshez. Az `admin-container` osztály egyedi stílusokat alkalmaz a háttérszín, padding, border és árnyékolás beállításához.

    - **`<h2 class="title">Kérdések kezelése</h2>`**: A kérdéskezelő szekció címe.

    - **`<div class="justify-content-end"> ... </div>`**: Egy div Bootstrap flexbox elrendezéssel, amely a tartalmát a jobb oldalra igazítja.

      - **`<h6>Témakör: {{ selectedCategoryName }}</h6>`**: Megjeleníti a jelenleg kiválasztott témakör nevét.
      - **`<OperationsCrudQuestionsAnswers ... />`**: Egy egyedi komponens (`OperationsCrudQuestionsAnswers`) a kérdésekkel kapcsolatos létrehozási műveletekhez.
        - `style="text-align: right"` és `class="mb-2 me-2"`: Inline stílus és Bootstrap osztályok az elhelyezéshez és a margókhoz.
        - `@onClickCreateButton="onClickCreateButton"`: Egy eseménykötés, amely meghívja az `onClickCreateButton` metódust, amikor a komponensben lévő "új létrehozása" gomb megnyomásra kerül.

    - **`<div class="table-wrapper table-responsive"> ... </div>`**: Egy div, amely a táblázatot tartalmazza és reszponzívvá teszi, valamint egyedi stílusokat alkalmaz a `table-wrapper` osztály a görgetés kezelésére.
      - **`<table class="table table-hover user-table mb-5"> ... </table>`**: Egy Bootstrap táblázat, amely hover effekttel és egyedi `user-table` stílusokkal rendelkezik.
        - **`<thead> ... </thead>`**: A táblázat fejléce.
          - **`<tr class="sticky-top"> ... </tr>`**: Egy sor, amely a `sticky-top` Bootstrap osztályt használja, hogy a fejléc rögzítve maradjon görgetés közben.
            - `<th scope="col">Kérdés</th>`, `<th scope="col">Típus</th>`, `<th scope="col">Válaszok</th>`, `<th scope="col">+</th>`: A táblázat oszlopainak címei.
        - **`<tbody> ... </tbody>`**: A táblázat törzse.
          - **`<tr v-for="questionAnswer in filteredQuestions" :key="questionAnswer.questionId"> ... </tr>`**: Egy ciklus, amely a `filteredQuestions` tömb minden elemére létrehoz egy sort. A `:key` attribútum a Vue.js hatékonyabb DOM frissítéséhez szükséges.
            - `<td>{{ questionAnswer.question }}</td>`: A kérdés szövegének megjelenítése.
            - `<td>{{ questionAnswer.questionCategory }}</td>`: A kérdés típusának megjelenítése.
            - `<td> ... </td>`: A kérdéshez tartozó válaszok megjelenítése.
              - `<div v-for="answer in questionAnswer.answers" :key="answer.answerId"> ... </div>`: Egy belső ciklus, amely a kérdéshez tartozó válaszok tömbjének minden elemére létrehoz egy divet.
                - `<i v-if="answer.rightAnswer === true" class="bi bi-check-lg right-answer-icon"></i>`: Egy Bootstrap ikon megjelenítése, ha a válasz a helyes válasz.
                - `- {{ answer.answer }}`: A válasz szövegének megjelenítése.
            - `<td> ... </td>`: Műveleti gombok a kérdéshez.
              - `<OperationsCrudQuestionsAnswers ... />`: Egy egyedi komponens a kérdésekkel kapcsolatos törlési és frissítési műveletekhez.
        - `:questionAnswer="questionAnswer"`: Az aktuális kérdés-válasz objektum átadása a komponensnek.
        - `@onClickDeleteButton="onClickDeleteButton"`: Eseménykötés a törlés gomb megnyomására.
        - `@onClickUpdateButton="onClickUpdateButton"`: Eseménykötés a frissítés gomb megnyomására.

3.  **`<div class="col-12 category-container col-md-4 col-xxl-4 ms-5"> ... </div>`**: Egy div a témakörök listájának megjelenítéséhez. Bootstrap oszlop osztályokat használ a reszponzív elrendezéshez. A `category-container` osztály egyedi stílusokat alkalmaz a háttérszín, padding, border, árnyékolás és görgetés beállításához.

    - **`<h2 class="title">Témakörök</h2>`**: A témakörök szekció címe.

    - **`<table class="table table-hover user-table"> ... </table>`**: Egy Bootstrap táblázat a témakörök listájának megjelenítéséhez.
      - **`<tbody> ... </tbody>`**: A táblázat törzse.
        - **`<tr class="my-cursor" v-for="category in categories" :key="category.id" @click="selectCategory(category.id, category.category)" :class="{ 'table-danger': selectedCategoryId === category.id }"> ... </tr>`**: Egy ciklus, amely a `categories` tömb minden elemére létrehoz egy sort.
          - `class="my-cursor"`: Egyedi osztály a kurzor stílusának megváltoztatásához, jelezve, hogy a sor kattintható.
          - `@click="selectCategory(category.id, category.category)"`: Eseménykötés, amely meghívja a `selectCategory` metódust a témakör kiválasztásakor.
          - `:class="{ 'table-danger': selectedCategoryId === category.id }"`: Dinamikus osztálykötés, amely a sort pirosra színezi, ha az a kiválasztott témakör.
          - `<td>{{ category.category }}</td>`: A témakör nevének megjelenítése.

4.  **`<Modal ...> </Modal>`**: Egy egyedi `Modal` komponens a modális ablak megjelenítéséhez (valószínűleg egy általános célú modális komponens).
    - `:title="title"`, `:yes="yes"`, `:no="no"`, `:size="size"`: Propok átadása a modális komponensnek a cím, a gombok szövege és a méret beállításához.
    - `@yesEvent="yesEventHandler"`: Eseménykötés a "igen" gomb eseményének kezelésére.
    - `<div v-if="state == 'Delete'"> {{ messageYesNo }} </div>`: Feltételes megjelenítés törlés megerősítő üzenethez.
    - `<QuestionsAnswersForm ... />`: Egy egyedi `QuestionsAnswersForm` komponens az új kérdések létrehozásához és a meglévőek szerkesztéséhez.
      - `v-if="state === 'Create' || state === 'Update'"`: Csak "Create" vagy "Update" állapotban jelenik meg.
      - `:key="formKey"`: A `formKey` prop megváltoztatása kényszeríti a komponens újrarenderelését.
      - `:is-create="isCreate"`: Jelzi, hogy új kérdés létrehozása vagy meglévő szerkesztése történik.
      - `:formData="questionAnswers"`: A kérdés és válaszok űrlap adatai.
      - `:categories="categories"`: A témakörök listája.
      - `:questionTypes="questionTypes"`: A kérdéstípusok listája.
      - `:selectedCategoryId="selectedCategoryId"`: A jelenleg kiválasztott témakör ID-ja.
      - `@saveItem="saveItemHandler"`: Eseménykötés a mentés eseményére.
      - `@addAnswer="addAnswerHandler"`: Eseménykötés új válasz hozzáadására.
      - `@saveField="saveField"`: Eseménykötés egy válasz mezőjének mentésére.
      - `@removeAnswer="deleteAnswerById"`: Eseménykötés egy válasz törlésére.

### `<script>` (Szkript)

A `<script>` tag tartalmazza a komponens JavaScript logikáját.

- **Osztály definíciók (`Question`, `Answer`, `QuestionType`)**: Adatstruktúrák a kérdések, válaszok és kérdéstípusok reprezentálására.

- **Importok**: Külső modulok és belső komponensek importálása (`axios`, `BASE_URL`, `useAuthStore`, egyedi form és CRUD komponensek, Bootstrap).

- **`export default { ... }`**: A Vue.js komponens definíciós objektuma.

  - **`components: { ... }`**: Regisztrálja a használt egyedi komponenseket.

  - **`data() { ... }`**: A komponens reaktív állapotának definíciója. Tartalmazza a store-t, a kérdéseket és válaszokat, a témaköröket, a kérdéstípusokat, a létrehozás/szerkesztés állapotát, a kiválasztott kategóriát és nevét, a modális ablakhoz tartozó adatokat, az aktuálisan szerkesztett kérdést és választ, valamint egy kulcsot az űrlap kényszerített újrarendereléséhez.

  - **`computed: { filteredQuestions() { ... } }`**: Egy számított tulajdonság, amely a `questionsAnswers` tömböt szűri a `selectedCategoryId` alapján. Ha nincs kiválasztott kategória, az összes kérdést visszaadja.

  - **`methods: { ... }`**: A komponens metódusainak definíciója.

    - **Adatlekérési metódusok (`fetchQuestionsAnswers`, `fetchQuestionsAnswersById`, `fetchCategories`, `fetchQuestionTypes`)**: Aszinkron hívások az API-tól a kérdések, válaszok, témakörök és kérdéstípusok adatainak lekéréséhez.
    - **Kategória kiválasztás (`selectCategory`)**: Beállítja a `selectedCategoryId` és `selectedCategoryName` értékeket.
    - **Elem mentése (`saveItemHandler`)**: Meghívja a létrehozási vagy frissítési metódust a `state` alapján.
    - **Kérdés létrehozása (`createQuestion`)**: POST kérést küld az új kérdés mentéséhez.
    - **Kérdés frissítése (`updateQuestion`)**: PATCH kérést küld a meglévő kérdés frissítéséhez.
    - **Válasz hozzáadása (`addAnswerHandler`)**: POST kérést küld új válasz hozzáadásához az aktuális kérdéshez.
    - **Mező mentése (`saveField`)**: PATCH kérést küld egy válasz mezőjének frissítéséhez.
    - **Válasz törlése (`deleteAnswerById`)**: DELETE kérést küld egy válasz törléséhez.
    - **Kérdés törlése (`deleteQuestionById`)**: DELETE kérést küld a kérdés törléséhez.
    - **Modális "igen" esemény kezelő (`yesEventHandler`)**: Meghívja a törlési metódust, ha az állapot "Delete".
    - **Törlés gomb kattintás (`onClickDeleteButton`)**: Beállítja a modális ablak adatait törléshez.
    - **Frissítés gomb kattintás (`onClickUpdateButton`)**: Beállítja a modális ablak adatait szerkesztéshez és lekéri a szerkesztendő kérdés adatait.
    - **Létrehozás gomb kattintás (`onClickCreateButton`)**: Beállítja a modális ablak adatait új kérdés létrehozásához.

  - **`mounted() { ... }`**: Lifecycle hook, amely a komponens DOM-ba illesztése után fut le. Lekéri a kérdéseket, kategóriákat és kérdéstípusokat, valamint inicializálja a Bootstrap modális ablakot.

- **`<style scoped>` (Stílusok)**: A komponensre korlátozott CSS stílusok a háttérképhez, az adminisztrációs konténerhez, a táblázathoz, a helyes válasz ikonhoz és a kategória konténerhez. Reszponzív stílusokat is tartalmaz a táblázat görgetéséhez kisebb képernyőkön.

### `QuestionsAnswersForm.vue` elemzése

Ez a Vue.js komponens egy űrlapot valósít meg új kérdések létrehozásához és a meglévő kérdések szerkesztéséhez, beleértve a kérdés szövegét, típusát, témakörét és a hozzá tartozó válaszokat.

### `<template>` (Sablon)

A sablon definiálja az űrlap vizuális struktúráját.

1.  **`<form @submit.prevent="onClickSubmit" class="needs-validation" novalidate>`**: A fő űrlap elem.

    - `@submit.prevent="onClickSubmit"`: Megakadályozza az alapértelmezett űrlapküldési viselkedést és meghívja az `onClickSubmit` metódust.
    - `class="needs-validation"` és `novalidate`: Bootstrap osztályok az űrlap validációjához.

2.  **Kérdés mező (`<div> ... </div>`)**: Egy div flexbox elrendezéssel a kérdés szövegének és típusának beviteli mezőihez.

    - `<label for="question" class="form-label">Kérdés:</label>`: A kérdés beviteli mező címkéje.
    - `<input type="text" id="question" class="form-control" v-model="formData.question" required />`: A kérdés szövegének beviteli mezője. Kétirányú adatkapcsolatban áll a `formData.question` adattulajdonsággal és kötelezően kitöltendő.
    - `<label for="questionType" class="form-label">Típus:</label>`: A kérdéstípus kiválasztó címkéje.
    - `<select id="questionType" class="form-select" v-model="formData.questionTypeId" required> ... </select>`: A kérdéstípus kiválasztó legördülő menü. A `questionTypes` propból érkező opciókat jeleníti meg és kétirányú adatkapcsolatban áll a `formData.questionTypeId` adattulajdonsággal.

3.  **Témakör kiválasztó (`<div class="mb-3"> ... </div>`)**: Egy div a témakör kiválasztó legördülő menühöz.

    - `<label for="category" class="form-label">Téma:</label>`: A témakör kiválasztó címkéje.
    - `<select id="category" class="form-select" v-model="formData.categoryId" required style="max-height: 200px; overflow-y: auto"> ... </select>`: A témakör kiválasztó legördülő menü. A `categories` propból érkező opciókat jeleníti meg és kétirányú adatkapcsolatban áll a `formData.categoryId` adattulajdonsággal. A `:selected` attribútum beállítja a kezdeti kiválasztást a `selectedCategoryId` prop alapján.

4.  **Mentés/Frissítés gomb (`<button type="submit" ... >`)**: Egy gomb az űrlap adatainak mentéséhez vagy frissítéséhez. A gomb szövege a `isCreate` prop értékétől függően változik. Csak akkor jelenik meg, ha a kérdés, a témakör és a kérdéstípus ki van választva.

5.  **Válaszok (`<div v-for="(answer, index) in formData.answers" :key="answer.answerId" class="mb-3"> ... </div>`)**

Ez a rész iterálja a `formData` objektum `answers` tömbjét, és minden válaszhoz megjelenít egy szakaszt. Minden válaszhoz tartozik egy címke, egy checkbox a helyes válasz jelölésére (ami alapértelmezés szerint letiltott, ha nem szerkesztünk), a válasz szövege (ami szerkesztés közben egy beviteli mezővé válik), valamint gombok a válasz szerkesztéséhez és törléséhez. Szerkesztés módban a felhasználó módosíthatja a válasz szövegét és beállíthatja, hogy az a helyes válasz-e. A szerkesztés mentésére és megszakítására külön gombok szolgálnak.

6. **Új válasz hozzáadása gomb (`<div class="mb-3"> ... </div>`)**

Egy gomb, amely lehetővé teszi új válaszok hozzáadását a kérdéshez. Ez a gomb csak akkor jelenik meg, ha egy meglévő kérdést szerkesztünk, és a kérdés, a témakör és a kérdéstípus ki van választva.

### `<script>` (Szkript)

A szkript rész tartalmazza a komponens működéséhez szükséges logikát.

- `props`: A komponens fogadja a szülő komponenstől a témakörök (`categories`), az űrlap adatait (`formData`), a kérdéstípusokat (`questionTypes`), azt, hogy új létrehozás történik-e (`isCreate`), és az esetlegesen előre kiválasztott témakör ID-ját (`selectedCategoryId`).
- `emits`: A komponens eseményeket bocsát ki a szülő komponens felé, jelezve a különböző felhasználói interakciókat, mint például az űrlap mentése (`saveItem`), az űrlap alaphelyzetbe állítása (`resetForm`), új válasz hozzáadása (`addAnswer`), egy válasz mezőjének mentése (`saveField`) és egy válasz törlése (`removeAnswer`).
- `data()`: A komponens belső állapotát kezeli. Tárolja a szerkesztett mező ideiglenes értékét (`updatedField`), a jelenleg szerkesztett válasz ID-ját (`isEditingField`), egy logikai flaget, amely jelzi, hogy szerkesztés módban vagyunk-e (`editing`), és egy ideiglenes változót a helyes válasz állapotának kezelésére (`rightAnswerTempValue`).
- `methods`: A komponens metódusai definiálják a különböző interakciók logikáját.
  - `addAnswer()`: Kibocsátja az eseményt egy új válasz hozzáadásához.
  - `removeAnswer(answer, answerId)`: Kibocsátja az eseményt egy válasz törléséhez, átadva a törlendő válasz adatait és ID-ját.
  - `resetForm()`: Kibocsátja az eseményt az űrlap alaphelyzetbe állításához.
  - `saveField(answer, answerId)`: Ezt a metódust a szerkesztett válasz mentésekor hívjuk meg. Konvertálja a helyes válasz logikai értékét egész számmá, kibocsátja a `saveField` eseményt a frissített válasz adataival és ID-jával, majd kilép a szerkesztés módból.
  - `startEdit(answerId, answer)`: Beállítja a szerkesztés módot az adott válaszhoz, tárolva annak ID-ját és szövegét.
  - `cancelEdit()`: Kilép a szerkesztés módból és visszaállítja a szerkesztett mező állapotát.
  - `onClickSubmit()`: Ezt a metódust az űrlap beküldésekor hívjuk meg. Ellenőrzi, hogy a kötelező mezők ki vannak-e töltve, és ha igen, kibocsátja a `saveItem` eseményt az űrlap adataival.

### `<style scoped>` (Stílus)

A stílus rész lokális CSS szabályokat tartalmaz, amelyek csak erre a komponensre vonatkoznak. Meghatározza az űrlap elemeinek alapvető stílusát, beleértve a címkéket, a margókat, az input csoportokat.

## `QuestionTypesView.vue` elemzése

Ez a Vue.js komponens a kérdéstípusok kezelésére szolgáló felületet jeleníti meg. Lehetővé teszi a kérdéstípusok listázását, kiválasztását, létrehozását, módosítását és törlését.

### `<template>` (Sablon)

A sablon határozza meg a komponens HTML struktúráját.

1.  **`<div class="my-container">`**: A fő konténer, amely a teljes oldalt lefedi. Az `my-container` osztály egyedi stílusokat alkalmaz (háttérkép, középre igazítás).

2.  **`<div class="col-10 col-md-8 col-xxl-3 category-container">`**: Egy Bootstrap grid oszlop és egyedi stílusokkal rendelkező konténer a kérdéstípusok kezeléséhez. Korlátozza a szélességet és stílusozza a hátteret, paddingot, sarkokat, árnyékot és szegélyt. A `overflow: auto` biztosítja a görgetést, ha a tartalom túl hosszú.

3.  **`<h2 class="title">Kérdéstípusok</h2>`**: A kérdéstípusok oldalának fő címe.

4.  **`<OperationsCrudQuestionTypes ... />`**: Egy egyedi komponens, amely valószínűleg a létrehozás gombot tartalmazza.

    - `style="text-align: right"`: A gomb jobbra igazításához.
    - `class="mb-2 me-2"`: Bootstrap osztályok a margók beállításához.
    - `@onClickCreateButton="onClickCreateQuestionTypeButton"`: Eseménykötés, amely a `onClickCreateQuestionTypeButton` metódust hívja meg a "létrehozás" gomb kattintásakor.

5.  **`<table class="table table-hover user-table">`**: Egy Bootstrap táblázat a kérdéstípusok listájának megjelenítésére. A `table`, `table-hover` és `user-table` osztályok Bootstrap és egyedi stílusokat alkalmaznak.

    - **`<thead>`**: A táblázat fejléce, amely egyetlen oszlopot tartalmaz "Kérdéstípusok" néven és egy oszlopot a műveletekhez ("+").
    - **`<tbody>`**: A táblázat tartalmi része, ahol a `v-for="questionType in questionTypes" :key="questionType.id"` direktíva iterál a `questionTypes` tömb elemein, és minden kérdéstípushoz létrehoz egy táblázatsort (`<tr>`).
      - **`<tr ...>`**: A táblázat sorai.
        - `class="my-cursor"`: Egyedi kurzor stílus a sor fölé húzáskor.
        - `@click="selectQuestionType(questionType.id)"`: Eseménykötés, amely a `selectQuestionType` metódust hívja meg a sor kattintásakor, átadva a kérdéstípus azonosítóját.
        - `:class="{ 'table-danger': selectedQuestionTypeId === questionType.id, }"`: Dinamikusan alkalmazza a `table-danger` Bootstrap osztályt, ha a sorhoz tartozó `questionType.id` megegyezik a `selectedQuestionTypeId`-vel (kiemelve a kiválasztott sort).
        - **`<td>{{ questionType.questionCategory }}</td>`**: Megjeleníti a kérdéstípus nevét (`questionCategory`).
        - **`<td style="width: 50px"> ... </td>`**: Egy cella rögzített szélességgel a műveleti gombok számára.
          - **`<OperationsCrudQuestionTypes ... />`**: Egy másik `OperationsCrudQuestionTypes` komponens, amely a törlés és módosítás gombokat tartalmazza az egyes kérdéstípusokhoz.
            - `:questionType="questionType"`: Átadja az aktuális `questionType` objektumot a komponensnek.
            - `@onClickDeleteButton="onClickDeleteQuestionTypeButton"`: Eseménykötés a törlés gomb kattintásakor, meghívja a `onClickDeleteQuestionTypeButton` metódust az aktuális `questionType` objektummal.
            - `@onClickUpdateButton="onClickUpdateQuestionTypeButton"`: Eseménykötés a módosítás gomb kattintásakor, meghívja a `onClickUpdateQuestionTypeButton` metódust az aktuális `questionType` objektummal.

6.  **`<Modal ...>`**: Egy egyedi modális ablak komponens.
    - `:title="title"`, `:yes="yes"`, `:no="no"`, `:size="size"`: Propok a modális ablak címének, "igen" gombjának szövegének, "nem" gombjának szövegének és méretének beállításához.
    - `@yesEvent="yesEventHandler"`: Eseménykötés, amely a `yesEventHandler` metódust hívja meg a modális ablak "igen" gombjának kattintásakor.
    - **`<div v-if="state == 'Delete'"> {{ messageYesNo }} </div>`**: A törlés megerősítő üzenetének megjelenítése, ha a `state` értéke "Delete".
    - **`<QuestionTypesForm ... />`**: Egy egyedi űrlap komponens a kérdéstípusok létrehozásához vagy módosításához.
      - `v-if="state === 'Create' || state === 'Update'"`: Csak akkor jelenik meg, ha a `state` értéke "Create" vagy "Update".
      - `:itemForm="questionType"`: Átadja az aktuális `questionType` objektumot az űrlapnak.
      - `@saveItem="saveQuestionTypeHandler"`: Eseménykötés, amely a `saveQuestionTypeHandler` metódust hívja meg az űrlap "mentés" eseményének bekövetkezésekor.

### `<script>` (Szkript)

A szkript rész tartalmazza a komponens logikáját és adatait.

- **`class QuestionType { ... }`**: Egy egyszerű JavaScript osztály a kérdéstípus objektumok létrehozásához.

- **`import` állítások**: Importálja a szükséges modulokat és komponenseket (`axios`, `BASE_URL`, `useAuthStore`, `QuestionTypesForm`, `OperationsCrudQuestionTypes`, Bootstrap).

- **`components`**: Regisztrálja a használt egyedi komponenseket (`QuestionTypesForm`, `OperationsCrudQuestionTypes`).

- **`data()`**: A komponens reaktív állapotának tárolására szolgáló adatok:

  - `store: useAuthStore()`: Az autentikációs tároló példánya.
  - `urlApiQuestionType`: Az API végpontja a kérdéstípusokhoz.
  - `questionTypes: []`: Egy tömb a lekérdezett kérdéstípusok tárolására.
  - `selectedQuestionTypeId: null`: A kiválasztott kérdéstípus azonosítója.
  - `messageYesNo: null`: Az igen/nem típusú modális üzenete.
  - `state: "Read"`: A komponens aktuális állapota (CRUD műveletek).
  - `title`, `yes`, `no`, `size`: A modális ablak konfigurációs opciói.
  - `questionType: new QuestionType()`: Az aktuálisan létrehozott vagy módosított kérdéstípus objektuma.

- **`mounted()`**: Egy lifecycle hook, amely a komponens DOM-ba illesztése után fut le.

  - `this.fetchQuestionTypes()`: Meghívja a kérdéstípusok lekérdezésére szolgáló metódust.
  - `this.modal = new bootstrap.Modal("#modal", { keyboard: false, });`: Inicializál egy Bootstrap modal példányt a `#modal` ID-jű elemhez.

- **`methods`**: A komponens metódusai:
  - **`selectQuestionType(questionTypeId)`**: Beállítja a `selectedQuestionTypeId`-t a kiválasztott kérdéstípus azonosítójára.
  - **`async fetchQuestionTypes()`**: Lekérdezi a kérdéstípusokat az API-ból és tárolja a `questionTypes` tömbben.
  - **`async createQuestionType()`**: Létrehoz egy új kérdéstípust az API-n keresztül és frissíti a listát.
  - **`async updateQuestionType()`**: Módosítja a kiválasztott kérdéstípust az API-n keresztül és frissíti a listát.
  - **`async deleteQuestionTypeById()`**: Törli a kiválasztott kérdéstípust az API-ból és frissíti a listát.
  - **`onClickDeleteQuestionTypeButton(questionType)`**: Beállítja a törléshez szükséges modális állapotot és üzenetet.
  - **`onClickUpdateQuestionTypeButton(questionType)`**: Beállítja a módosításhoz szükséges modális állapotot és adatokat.
  - **`onClickCreateQuestionTypeButton()`**: Beállítja a létrehozáshoz szükséges modális állapotot és inicializál egy új `QuestionType` objektumot.
  - **`saveQuestionTypeHandler()`**: Meghívja a létrehozás vagy módosítás metódusát a `state` alapján, majd elrejti a modális ablakot.
  - **`yesEventHandler()`**: Meghívja a törlés metódust, ha a `state` "Delete", majd elrejti a modális ablakot.

### `<style scoped>` (Stílusok)

A `scoped` attribútum biztosítja, hogy ezek a stílusok csak erre a komponensre vonatkozzanak.

- `.my-container`: A fő konténer stílusozása (háttérkép, méretek, középre igazítás).
- `.table-wrapper`: Korlátozza a táblázat magasságát és engedélyezi a függőleges görgetést (nincs közvetlen használatban a sablonban).
- `.user-table`: A felhasználói táblázat stílusozása (szélesség, szegélyek összevonása, háttérszín).
- `.user-table th, .user-table td`: A táblázat fejléce és celláinak stílusozása (padding, szegély, igazítás).
- `.user-table th`: A táblázat fejléceinek stílusozása (háttérszín, szövegszín).
- `.right-answer-icon`: Egy ikon stílusa (nincs használatban a sablonban).
- `.category-container`: A kérdéstípusok konténerének stílusozása (maximális magasság és szélesség, háttérszín, padding, lekerekített sarkok, árnyék, szegély, margók, görgetés).
- `.my-cursor`: Mutató kurzor stílus a táblázat sorai fölé húzáskor.
- `h2`: A cím stílusozása (középre igazítás).
