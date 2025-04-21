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


## A validáció:

## A REST API kérések:


## A tesztelés:

# A frontend oldal: