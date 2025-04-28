import { test, expect } from '@playwright/test';

// See here how to get started:
// https://playwright.dev/docs/intro
test('visits the app root url', async ({ page }) => {
  await page.goto('/');
  await expect(page.locator('h1')).toHaveText('Kezdőlap');
})

test('Vue alkalmazás alapértelmezett oldalának betöltése', async ({ page }) => {
  // Nyisd meg a webalkalmazást
  await page.goto('http://localhost:5173'); // Vite alapértelmezetten 5173-es porton fut
  
  // Ellenőrizd, hogy a fő szöveg benne van az oldalon
  const title = await page.innerText('h1');
  expect(title).toBe('Kezdőlap');
});


test('Bejelentkezés teszt', async ({ page }) => {
  // Nyisd meg a bejelentkezési oldalt
  await page.goto('http://localhost:5173/bejelentkezes');

  // Töltsd ki az űrlapot
  await page.fill('input[name="test@example.com"]', 'email');
  await page.fill('input[name="123"]', 'jelszó');

  // Kattints a bejelentkezés gombra
  await page.click('button[type="submit"]');

  // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
  await expect(page).toHaveURL('http://localhost:3000/dashboard');
  await expect(page.locator('h1')).toHaveText('Üdvözlünk a dashboardon');
});
