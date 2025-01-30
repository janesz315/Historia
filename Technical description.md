### Az adatbázis:

## Táblák

 - témakörök (categories):
    - id: azonosító, int, autoIncrement
    - category (témakör): int, string(60), notNull
    - level: (szint), string(15), notNull
    - elaboration (kidolgozás), string, notNull

- források (sources)
    - id: azonosító, int, autoIncrement
    - categoryId (témakörId),  int, autoIncrement
    - sourceLink (forrásLink): string
    - note (megjegyzés): string, notNull
