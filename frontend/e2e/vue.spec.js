import { test, expect } from '@playwright/test';
// import axios from 'axios';
// import { vi } from 'vitest';

// See here how to get started:
//https://playwright.dev/docs/intro
// test('visits the app root url', async ({ page }) => {
//   await page.goto('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');
// })

// test('Homepage has title', async ({ page }) => {
//   await page.goto('/');
//   await expect(page).toHaveTitle(/Historia - Kezdőlap/);
// })

// test('Vue application loads the homepage', async ({ page }) => {
//   // Nyisd meg a webalkalmazást
//   // await page.goto('http://localhost:5173'); // Vite alapértelmezetten 5173-es porton fut
//   await page.goto('/'); // Vite alapértelmezetten 5173-es porton fut

//   // Ellenőrizd, hogy a fő szöveg benne van az oldalon
//   const title = await page.innerText('h1');
//   expect(title).toBe('Kezdőlap');
// });

// // Megtörténik a bejelentkezés (adminként), eljutunk a kezdőlapra
// test('Login with Admin', async ({ page }) => {
//   // Nyisd meg a bejelentkezési oldalt
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.click('button[type="submit"]');
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

// });


// test('Admin categories from the homepage is opening', async ({ page }) => {
//   // Nyisd meg a bejelentkezési oldalt
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.click('button[type="submit"]');
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

//   // Teszteljük az admin témakörök oldalát
//   await page.waitForSelector('a#categoriesAdmin', { state: 'visible' }); // Várakozás, amíg az elem láthatóvá válik
//   await page.click('a#categoriesAdmin'); // Feltételezve, hogy az elem egy <a> tag az ID alapján
//   await expect(page).toHaveURL('/temakorokAdmin'); // Ellenőrizzük, hogy az URL helyes
//   const title = await page.innerText('h1');
//   expect(title).toBe('Témakörök kezelése'); // Ellenőrizzük az oldal tartalmát
//   // await expect(page.locator('h1')).toHaveText('Témakörök kezelése');
// });

// test('Admin categories from the navigation bar is opening', async ({ page }) => {
//   // Nyisd meg a bejelentkezési oldalt
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.click('button[type="submit"]');
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

//   // Teszteljük az admin témakörök oldalát
//   await page.waitForSelector('a.nav-link#categoriesAdmin', { state: 'visible' }); // Várakozás, amíg az elem láthatóvá válik
//   await page.click('a.nav-link#categoriesAdmin'); // Feltételezve, hogy az elem egy <a> tag az ID alapján
//   await expect(page).toHaveURL('/temakorokAdmin'); // Ellenőrizzük, hogy az URL helyes
//   const title = await page.innerText('h1');
//   expect(title).toBe('Témakörök kezelése'); // Ellenőrizzük az oldal tartalmát
//   // await expect(page.locator('h1')).toHaveText('Témakörök kezelése');
// });

// test('Create a question', async ({ page }) => {
//   // Nyisd meg a bejelentkezési oldalt
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.waitForSelector('button:has-text("Bejelentkezés")', { state: 'visible' });
//   // await page.click('button:has-text("Bejelentkezés")');
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

//   // Teszteljük az kérdésbank oldalát
//   await page.waitForSelector('a:has-text("Kérdésbank")', { state: 'visible' });
//   await page.click('a:has-text("Kérdésbank")');
//   await expect(page).toHaveURL('/kerdesek');

//   const headings = await page.locator('h2').allInnerTexts();
//   expect(headings).toContain('Kérdések kezelése');
//   expect(headings).toContain('Témakörök');
  
//   // Kattintunk a "A középkori város" szövegre 
//   await page.click('td:has-text("A középkori város")');
//   await page.click('button:has-text("Új kérdés létrehozása")');

//   await page.fill('input#question', 'Mikor volt az Aranybulla?');
//   await page.selectOption('select#questionType', { label: 'Évszám' }); // Kiválasztjuk az "Évszám" opciót az elnevezés alapján
//   await page.waitForSelector('button.btn.btn-success.mb-2', { state: 'visible' });
//   await page.click('button.btn.btn-success.mb-2');

//   await expect(page.locator('td:has-text("Mikor volt az Aranybulla?")')).toBeVisible({ timeout: 5000 });
// });

// test(' Update a question', async ({ page }) => {
//   // Nyisd meg a bejelentkezési oldalt
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.waitForSelector('button:has-text("Bejelentkezés")', { state: 'visible' });
//   // await page.click('button:has-text("Bejelentkezés")');
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

//   // Teszteljük az kérdésbank oldalát
//   await page.waitForSelector('a:has-text("Kérdésbank")', { state: 'visible' });
//   await page.click('a:has-text("Kérdésbank")');
//   await expect(page).toHaveURL('/kerdesek');

//   const headings = await page.locator('h2').allInnerTexts();
//   expect(headings).toContain('Kérdések kezelése');
//   expect(headings).toContain('Témakörök');
  
//   // Kattintunk a "A középkori város" szövegre 
//   await page.click('td:has-text("A középkori város")');
//   const questionToModify = page.locator('tr', { hasText: 'Mikor volt az Aranybulla?' });
//   await questionToModify.locator('button.btn.btn-outline-primary.d-block').click();

//   // Várakozás a módosító űrlap megjelenésére (a modális ablakra)
//   await page.waitForSelector('#modal', { state: 'visible' });
//   await page.waitForSelector('h1.modal-title:has-text("Kérdés módosítása")', { state: 'visible' });
//   await page.waitForTimeout(1000); 
//   // Módosítsuk a kérdés szövegét
//   const newQuestionText = 'Mikor adták ki az Aranybullát?';
//   await page.fill('input#question', newQuestionText);

//   // Kattintsunk a "Mentés" gombra a módosító űrlapon
//   await page.waitForSelector('button.btn.btn-success.mb-2', { state: 'visible' });
//   await page.click('button:has-text("Frissítés")', { within: page.locator('#modal') }); // Fontos a modális ablakon belül keresni

//   // Várakozás a sikeres módosítás jelzésére (opcionális)
//   // await page.waitForSelector('div:has-text("Sikeresen módosítva!")', { state: 'visible' });

//   // Ellenőrizzük, hogy a kérdés szövege megváltozott-e a táblázatban
//   const modifiedQuestion = page.locator('tr', { hasText: newQuestionText });
//   await expect(modifiedQuestion.locator('td:first-child')).toHaveText(newQuestionText);

// });


test('Create and delete a question', async ({ page }) => {
  // Nyisd meg a bejelentkezési oldalt
  await page.goto('/bejelentkezes');

  // Töltsd ki az űrlapot
  await page.fill('input#email', 'test@example.com');
  await page.fill('input#password', '123');
  // await page.fill('input[name="email"]', 'test@example.com');
  // await page.fill('input[name="password"]', '123');

  // Kattints a bejelentkezés gombra
  // await page.waitForSelector('button:has-text("Bejelentkezés")', { state: 'visible' });
  // await page.click('button:has-text("Bejelentkezés")');
  await page.click('button:has-text("Bejelentkezés")');

  // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
  await expect(page).toHaveURL('/');
  await expect(page.locator('h1')).toHaveText('Kezdőlap');

  // Teszteljük az kérdésbank oldalát
  await page.waitForSelector('a:has-text("Kérdésbank")', { state: 'visible' });
  await page.click('a:has-text("Kérdésbank")');
  await expect(page).toHaveURL('/kerdesek');

  const headings = await page.locator('h2').allInnerTexts();
  expect(headings).toContain('Kérdések kezelése');
  expect(headings).toContain('Témakörök');
  
  // Kattintunk a "A középkori város" szövegre 
  await page.click('td:has-text("A középkori város")');
  await page.click('button:has-text("Új kérdés létrehozása")');

  await page.fill('input#question', 'Mikor volt az Aranybulla?');
  await page.selectOption('select#questionType', { label: 'Évszám' }); // Kiválasztjuk az "Évszám" opciót az elnevezés alapján
  await page.waitForSelector('button.btn.btn-success.mb-2', { state: 'visible' });
  await page.click('button.btn.btn-success.mb-2');

  await expect(page.locator('td:has-text("Mikor volt az Aranybulla?")')).toBeVisible({ timeout: 5000 });

  const questionToDelete = page.locator('tr', { hasText: 'Mikor volt az Aranybulla?' });
  await questionToDelete.locator('button.btn.btn-outline-danger.d-block.mb-2').click();

  // Várakozás a törlő űrlap megjelenésére (a modális ablakra)
  await page.waitForSelector('#modal', { state: 'visible' });
  await page.waitForSelector('h1.modal-title:has-text("Törlés")', { state: 'visible' });

  // Kattintsunk a "Törlés" gombra a törlő űrlapon
  await page.click('button:has-text("Igen")', { within: page.locator('#modal') }); // Fontos a modális ablakon belül keresni

  // Várakozás a törlés befejezésére (opcionális, de ajánlott)
  await page.waitForTimeout(500); // Rövid várakozás a frissülésre

  // Ellenőrizzük, hogy a kérdés eltűnt-e a listából
  await expect(page.locator('td:has-text("Mikor volt az Aranybulla?")')).toBeHidden({ timeout: 5000 });
});
// test('Go to Profile', async ({ page }) => {
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.waitForSelector('button:has-text("Bejelentkezés")', { state: 'visible' });
//   // await page.click('button:has-text("Bejelentkezés")');
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

//   // Nyisd meg a dropdown menüt az Iskola menüpontra kattintva
//   // Use a more specific selector for the "Iskola" menu item
//   await page.click('a#userDropdown.nav-link.dropdown-toggle');

//   // Wait for the dropdown menu to be visible
//   await expect(page.locator('a#userDropdown.nav-link.dropdown-toggle')).toBeVisible();

//   // Click the "Kártyák" menu item
//   await page.click('a.dropdown-item:has-text("Profil")');
//   await expect(page.locator('h1')).toHaveText('Felhasználói Profil');
  

// });

// test('Login and logout', async ({ page }) => {
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.waitForSelector('button:has-text("Bejelentkezés")', { state: 'visible' });
//   // await page.click('button:has-text("Bejelentkezés")');
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

//   // Nyisd meg a dropdown menüt az Iskola menüpontra kattintva
//   // Use a more specific selector for the "Iskola" menu item
//   await page.click('a#userDropdown.nav-link.dropdown-toggle');

//   // Wait for the dropdown menu to be visible
//   await expect(page.locator('a#userDropdown.nav-link.dropdown-toggle')).toBeVisible();

//   // Click the "Kártyák" menu item
//   await page.click('a.dropdown-item:has-text("Kijelentkezés")');
//   await expect(page.locator('p')).toHaveText('Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!');
// });


// test('Register and Login', async ({ page }) => {
//   await page.goto('/regisztracio');

//   // Töltsd ki az űrlapot
//   await page.fill('input[placeholder="Felhasználónév*"]', 'user');
//   await page.fill('input[placeholder="E-mail cím*"]', 'user2@example.com');
//   await page.fill('input[placeholder="Jelszó*"]', 'heslo123');
//   await page.fill('input[placeholder="Jelszó még egyszer*"]', 'heslo123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.waitForSelector('button:has-text("Bejelentkezés")', { state: 'visible' });
//   // await page.click('button:has-text("Bejelentkezés")');
//   await page.click('button:has-text("Regisztrálás")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/bejelentkezes');
//   await expect(page.locator('h2')).toHaveText('Bejelentkezés');

//   await page.fill('input#email', 'user2@example.com');
//   await page.fill('input#password', 'heslo123');

//   await page.click('button:has-text("Bejelentkezés")');
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');
// });

// test('Register and Login and delete the account', async ({ page }) => {
//   await page.goto('/regisztracio');

//   // Töltsd ki az űrlapot
//   await page.fill('input[placeholder="Felhasználónév*"]', 'user');
//   await page.fill('input[placeholder="E-mail cím*"]', 'user5@example.com');
//   await page.fill('input[placeholder="Jelszó*"]', 'heslo123');
//   await page.fill('input[placeholder="Jelszó még egyszer*"]', 'heslo123');
//   // await page.fill('input[name="email"]', 'test@example.com');
//   // await page.fill('input[name="password"]', '123');

//   // Kattints a bejelentkezés gombra
//   // await page.waitForSelector('button:has-text("Bejelentkezés")', { state: 'visible' });
//   // await page.click('button:has-text("Bejelentkezés")');
//   await page.click('button:has-text("Regisztrálás")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
//   await expect(page).toHaveURL('/bejelentkezes');
//   await expect(page.locator('h2')).toHaveText('Bejelentkezés');

//   await page.fill('input#email', 'user5@example.com');
//   await page.fill('input#password', 'heslo123');

//   await page.click('button:has-text("Bejelentkezés")');
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');
  
//   await page.click('a#userDropdown.nav-link.dropdown-toggle');

//   // Wait for the dropdown menu to be visible
//   await expect(page.locator('a#userDropdown.nav-link.dropdown-toggle')).toBeVisible();

//   // Click the "Kártyák" menu item
//   await page.click('a.dropdown-item:has-text("Profil")');
//   await expect(page.locator('h1')).toHaveText('Felhasználói Profil');

//    // Figyeld a "dialog" eseményt ÉS kattints a "Fiók törlése" gombra
//    page.once('dialog', async dialog => {
//      console.log(`Alert üzenet: ${dialog.message()}`);
//      await dialog.accept();
//      // Ha el kellene utasítanod, akkor: await dialog.dismiss();
//     });
//     await page.click('button:has-text("Fiók törlése")');

//   // Itt ellenőrizheted, hogy a fiók törlése sikeres volt-e, pl. átirányítás egy másik oldalra
//   await expect(page).toHaveURL('/regisztracio');
//   await expect(page.locator('h2')).toHaveText('Regisztráció');
  
// });


// test('Go to Profile and change the username', async ({ page }) => {
//   await page.goto('/bejelentkezes');

//   // Töltsd ki az űrlapot
//   await page.fill('input#email', 'test@example.com');
//   await page.fill('input#password', '123');

//   // Kattints a bejelentkezés gombra
//   await page.click('button:has-text("Bejelentkezés")');

//   // Ellenőrizd, hogy sikeres bejelentkezés után átirányítottak
//   await expect(page).toHaveURL('/');
//   await expect(page.locator('h1')).toHaveText('Kezdőlap');

//   // Nyisd meg a felhasználói menüt és navigálj a Profil oldalra
//   await page.click('a#userDropdown.nav-link.dropdown-toggle');
//   await expect(page.locator('a#userDropdown.nav-link.dropdown-toggle')).toBeVisible();
//   await page.click('a.dropdown-item:has-text("Profil")');
//   await expect(page.locator('h1')).toHaveText('Felhasználói Profil');

//   // Kattints a "Módosítás" gombra a felhasználónév mellett
//   await page.click('div:has-text("Felhasználó:") button:has-text("Módosítás")');

//   // Várd meg, amíg az input mező láthatóvá válik a felhasználónév szerkesztéséhez
//   const usernameInput = page.locator('input[placeholder="Enter new username"]');
//   await expect(usernameInput).toBeVisible();

//   // Töröld ki a jelenlegi felhasználónevet az input mezőből
//   await usernameInput.fill(''); // Vagy await usernameInput.press('Control+a'); await usernameInput.press('Delete');

//   // Írd be az új felhasználónevet
//   const newUsername = 'test3';
//   await usernameInput.fill(newUsername);

//   // Kattints a "Mentés" gombra a felhasználónév módosításához
//   await page.click('div:has-text("Felhasználó:") button:has-text("Mentés")');

//   // Várd meg, amíg a sikeres üzenet megjelenik (ha van ilyen)
//   // await expect(page.locator('div.alert-success')).toHaveText('Felhasználónév sikeresen frissítve.');

//   // Ellenőrizd, hogy az új felhasználónév megjelenik-e a profil oldalon
//   await expect(page.locator('.card-body:has-text("Felhasználó:") p')).toHaveText(`Felhasználó: ${newUsername}`);
// });


// test('fetchCategories fetches and processes categories correctly', async () => {
//   // Mock the axios.get method
//   const mockResponse = {
//     data: {
//       data: [
//         { id: 1, name: 'Category 1' },
//         { id: 2, name: 'Category 2' },
//       ],
//     },
//   };
//   vi.spyOn(axios, 'get').mockResolvedValue(mockResponse);

//   // Mock the store token
//   const mockStore = { token: 'mock-token' };

//   // Mock the component instance
//   const component = {
//     store: mockStore,
//     categories: [],
//     async fetchCategories() {
//       try {
//         const response = await axios.get(`${BASE_URL}/categories`, {
//           headers: { Authorization: `Bearer ${this.store.token}` },
//         });

//         this.categories = response.data.data.map((category) => ({
//           ...category,
//           expanded: false,
//           editing: false,
//         }));
//       } catch (error) {
//         console.error('Hiba a kategóriák lekérésekor:', error);
//       }
//     },
//   };

//   // Call the fetchCategories method
//   await component.fetchCategories();

//   // Assertions
//   expect(axios.get).toHaveBeenCalledWith(`${BASE_URL}/categories`, {
//     headers: { Authorization: `Bearer mock-token` },
//   });
//   expect(component.categories).toEqual([
//     { id: 1, name: 'Category 1', expanded: false, editing: false },
//     { id: 2, name: 'Category 2', expanded: false, editing: false },
//   ]);

//   // Restore the mocked axios.get
//   vi.restoreAllMocks();
// });