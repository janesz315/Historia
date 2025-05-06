Legelső lépésként klónozzuk le ezt a repozitóriumot a git clone parancssal, amit a Git nevű program használatával lehet megoldani akár a Visual Studio Code-ban is. Vagy esetlegesen ZIP-ben is le lehet tölteni a repozitórium tartalmát.

# Adatbázis
- A backend/dbsave útvonalú mappában van egy mentés az adatbázisról.
![A kép](/backend/img/save.jpg)
- Ahhoz, hogy ezt be tudd importálni, a XAMPP telepítése szükséges, mert az adatbáziskezelő szerver (MySQL) és a backend futtatásához szükséges PHP szerver is ebben van benne. Szóval le kell, hogy telepítsük [innen](https://www.apachefriends.org/hu/download.html)

- A telepítés után indítsuk el a programot. Ha ez megvan, akkor ennek a képnek kell bennünket fogadnia: 
![XAMPP_kezdetleges](/backend/img/XAMPP_starter.jpg)
- Nekünk az Apache és a MySQL szervereket kell okvetlenül elindítanunk. Körülbelül így kell kinéznie, ha minden jól ment:
![XAMPP_fut](/backend/img/XAMPP_working.jpg)

- Ha ez eddig megvan, akkor kattintsunk a "Stop" gomb melletti "Admin" gombra. Ezt kell látnunk:
![phpMyAdmin](/backend/img/phpMyAdmin.jpg)

- Ezután felül az importálás gombra rákattintva importáljuk be az sql fájlt itt a tallózás gombra kattintva:
![Database import](/backend/img/db_import.jpg)

- A sikeres importálás után ezt kell látnod:
![Database import sikeres](/backend/img/db_import_successful.jpg)

# Backend

- Szükségünk lesz egy pár programra: A [Composer](https://getcomposer.org/download/) nevű programra és a [Visual Studio Code](https://code.visualstudio.com/) programra.

- Ha ezek megvannak, akkor nyissuk meg a letöltött mappánkat a Visual Studio Code-ban. A Ctrl + Ö billentyűkombinációval a terminálba lépünk, ahol lépjünk bele a backend mappájába a "cd backend" paranccsal.

- Ezután a "composer install" paranccsal tudjuk letelepíteni a Laravel keretrendszerhez szükséges fájlokat, amiket a vendor mappába tesz bele. 

- Ezután márcsak a "php artisan serve" parancsot kell kiadnunk és ezzel elindul a backend oldal. Ennek a helyes működéséről a request.rest nevű fájlban megtalálható kérések lefuttatásáról bizonyosodhatunk meg.

- Amennyiben szeretnénk teszteket is lefuttatni, akkor importáljuk be a teszt adatbázis sql-jét a phpMyAdmin-ba. Ezután egy másik terminált nyitva beírjuk a "php artisan test" parancsot.



# Frontend

- Először is telepítsük le a [Node.js](https://nodejs.org/en) nevű programot, amellyel együtt az npm csomagkezelő is lejön, ami kulcsfontosságú a frontend beindítása terén.

- Kövessük a parancsokat és ha mindez megvan, akkor egy másik terminált nyitva menjünk bele a frontend mappánkba és adjuk ki az "npm i" parancsot, ami letölt minden szükséges csomagot a megfelelő működéshez.

- Ezután az "npm run dev" paranccsal tudjuk beindítani a projektet. És ha a Ctrl lenyomása közben rákattintunk a localhost-os hivatkozásra, akkor megjelenik a weboldal.

- Az npm run test:unit / npm run test:e2e paranccsal egy másik terminálban még tesztelni is tudjuk a frontend oldalt is.