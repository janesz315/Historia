<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div>

    <!-- Fő fejléc -->
    <header>
      <div class="wrapper">
        <!-- Logo / Weboldal neve -->
        <div class="logo">
          <RouterLink to="/">Historia</RouterLink>
        </div>

        <!-- Navigációs menü -->
        <nav>
          <ul>
            <li>
              <RouterLink to="/" class="nav-link">Kezdőlap</RouterLink>
            </li>
            <li>
              <RouterLink to="/about" class="nav-link">Rólunk</RouterLink>
            </li>
            <!-- <li>
              <RouterLink to="/temakor" class="nav-link">Témakörök</RouterLink>
            </li>
            <li>
              <RouterLink to="/test" class="nav-link">Tesztelés</RouterLink>
            </li>
            <li>
              <RouterLink to="/login" class="nav-link">Bejelentkezés</RouterLink>
            </li>
            <li>
              <RouterLink to="/register" class="nav-link">Regisztráció</RouterLink>
            </li> -->
          </ul>
        </nav>
      </div>
    </header>

    <!-- Dinamikus tartalom megjelenítése -->
    <RouterView />
  </div>
</template>

<style scoped>
/* Alap stílusok */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
}

/* Fejléc és navigáció */
.wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  background-color: #2c3e50; /* Sötét kékeszöld háttér */
}

.logo a {
  font-size: 28px;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
}

nav {
  display: flex;
}

nav ul {
  display: flex;
  list-style: none;
  margin: 0;
}

nav li {
  margin-left: 20px;
}

.nav-link {
  color: #ecf0f1; /* Világos szürke szín */
  text-decoration: none;
  font-size: 16px;
  padding: 8px 12px;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.nav-link:hover {
  background-color: #3498db; /* Kék szín hover esetén */
  color: white;
}

/* Mobil reszponzív menü */
@media (max-width: 768px) {
  nav {
    display: none; /* Elrejtjük a menüt mobil nézetben */
  }

  .wrapper {
    justify-content: center; /* Középre igazítjuk a menüt mobil nézetben */
  }

  nav ul {
    display: block;
    width: 100%;
    text-align: center;
  }

  nav li {
    margin: 10px 0;
  }

  .nav-link {
    display: block;
    padding: 15px;
    font-size: 18px;
  }

  /* Hamburger ikon a mobil verzióhoz */
  .hamburger {
    display: block;
    cursor: pointer;
    font-size: 30px;
    color: #fff;
  }
}
</style>

