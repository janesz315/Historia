### Mi a feladat?

Egy olyan weboldal létrehozása, amely egyrészt, a közép és az emelt szintű érettségire való felkészülést támogatja kidolgozott szóbeli tételekkel és témakörökkel, másrészt a tanulónak lehetősége van tesztelni tudását az érettségire feleletválasztós kérdések segítségével. Kiválaszthatja, hogy milyen szintről szeretne kérdéseket kapni. Ezután választhat a témakörök vagy a tételek közül attól függően, hogy melyiket választotta. Ha a felhasználó szeretné tesztelni teljeskörűen a tudását, akkor megjelölheti azt, hogy az összes témakörből kapjon kérdést. Miután végzett a teszttel, akkor megkapja a pontszámot és az értékelést is. A weboldal használata regiszrációhoz kötött. A felhasználó nem hozhat létre új tartalmakat, csakis az admin. 

### Az adatbázis:

## Táblák

 - categories (témakörök):
    - id: a témakör azonosítója, int(10), autoIncrement
    - category (témakör): a témakör neve, string(60), notNull
    - level (szint): közép vagy emelt szint, string(15), notNull
    - text (szöveg): maga a témakör tartalma, text, notNull

- sources (források):
    - id: a forrás azonosítója, int(10), autoIncrement
    - categoryId (témakörId): itt kapcsolódunk a témakörhöz, ez adja meg, melyik témakörbe kell,  int(10), autoIncrement
    - sourceLink (forrásLink): maga a hivatkozás, string(255)
    - note (megjegyzés): ha a forrás egy pdf-ben van, akkor itt adjuk meg az oldalszámot például, string(255), notNull

- questions (kérdések):
    - id: a kérdés azonosítója, int(10), autoIncrement
    - question (kérdés): itt találjuk meg a kérdést, string(255), notNull
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
    - percentage (százalék): Itt adjuk meg a teszt eredményét, double(5,2), notNull

- testQuestions (tesztKérdések):
    - id: a tesztkérdés azonosítója, int(10), autoIncrement
    - QuestionsId (KérdésekId): a tesztben megtalálható véletlenszerűen generált kérdés azonosítója, int(10), autoIncrement
    - answerId (válaszId): Itt adjuk meg a tesztben található kérdésnek a helyes válaszát az azonosító segítségével, int(10), autoIncrement
    - userTestId (userTesztId): Itt adjuk meg, hogy a tesztkérdés melyik felhasználó tesztjében jelenik meg az azonosító segítségével, int(10), autoIncrement



