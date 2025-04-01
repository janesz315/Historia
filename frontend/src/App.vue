<template>
  <div class="my-container">
    <!-- Fő fejléc -->
    <header>
      <div class="wrapper container-fluid ">
        <!-- Logo / Weboldal neve -->
        

        <!-- Navigációs menü -->
        <nav
          class="navbar navbar-expand-lg navbar-dark fixed-top"
          style="background-color: #8b5a2b"
        >
        <div class="logo">
          <RouterLink to="/">Historia</RouterLink>
        </div>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <RouterLink to="/" class="nav-link">Kezdőlap</RouterLink>
              </li>
              <li class="nav-item">
                <RouterLink to="/rolunk" class="nav-link">Rólunk</RouterLink>
              </li>
              <!-- Témakörök és Tesztelés menüpontok csak akkor jelennek meg, ha a felhasználó be van jelentkezve -->
              <!-- Admin -->
              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink to="/temakorokAdmin" class="nav-link"
                  >Témakörök</RouterLink
                >
              </li>
              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink to="/tesztekAdmin" class="nav-link"
                  >Tesztelés</RouterLink
                >
              </li>
              <!-- User -->
              <li
                v-if="stateAuth.user && stateAuth.roleId === 2"
                class="nav-item"
              >
                <RouterLink to="/temakorok" class="nav-link"
                  >Témakörök</RouterLink
                >
              </li>
              <li
                v-if="stateAuth.user && stateAuth.roleId === 2"
                class="nav-item"
              >
                <RouterLink to="/tesztek" class="nav-link"
                  >Tesztelés</RouterLink
                >
              </li>
              <!-- Admin menüpontok -->
              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink to="/admin" class="nav-link"
                  >Admin Felület</RouterLink
                >
              </li>
              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink to="/kerdesek" class="nav-link"
                  >Kérdésbank</RouterLink
                >
              </li>
              <!-- Bejelentkezés és Regisztráció csak akkor jelenik meg, ha nincs bejelentkezve -->
              <li v-if="!stateAuth.user" class="nav-item">
                <RouterLink to="/bejelentkezes" class="nav-link"
                  >Bejelentkezés</RouterLink
                >
              </li>
              <li v-if="!stateAuth.user" class="nav-item">
                <RouterLink to="/regisztracio" class="nav-link"
                  >Regisztráció</RouterLink
                >
              </li>
              <!-- Kijelentkezés csak akkor jelenik meg, ha be van jelentkezve a felhasználó -->
              <li v-if="stateAuth.user" class="nav-item">
                <RouterLink class="nav-link" to="/" @click="Logout()"
                  >Kijelentkezés</RouterLink
                >
              </li>
              <!-- Saját profil -->
              <li v-if="stateAuth.user" class="nav-item">
                <RouterLink class="nav-link" to="/profil">Profil</RouterLink>
              </li>
              <li v-if="stateAuth.user" class="nav-item nav-link">
                <i class="bi bi-person"></i>
                <span v-if="stateAuth.user"> {{ stateAuth.user }}</span>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>

    <!-- Dinamikus tartalom megjelenítése -->
    <RouterView />
  </div>
</template>

<script>
import { useAuthStore } from "@/stores/useAuthStore.js";
import { RouterLink, RouterView } from "vue-router";
import axios from "axios";
import { BASE_URL } from "@/helpers/baseUrls";

export default {
  data() {
    return {
      stateAuth: useAuthStore(),
    };
  },
  methods: {
    async Logout() {
      const url = `${BASE_URL}/users/logout`;
      const headers = {
        Accept: "application/json",
        Authorization: `Bearer ${this.stateAuth.token}`,
      };
      try {
        await axios.post(url, null, { headers });
      } catch (error) {
        console.error("Error:", error);
      }

      // Töröld a felhasználói adatokat a store-ból és a localStorage-ból
      this.stateAuth.clearStoredData();

      // Kényszerített oldalfrissítés
      window.location.reload(); // Ezzel frissíti az oldalt és törli a helyben tárolt adatokat
    },
  },
};
</script>

<style scoped>
/* Alap stílusok */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.my-container {
  height: 100%;
  overflow: hidden;
}

/* Fejléc és navigáció */
.wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  background-color: #8b5a2b; /* Sötét kékeszöld háttér */
}

.logo a {
  font-family: "Cinzel", serif;
  font-size: 32px;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
}

/* A Bootstrap navbar osztályok most biztosítják a menü megjelenését és rejtését */
.navbar-nav {
  display: flex;
}

.nav-item {
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
  /* Az alapértelmezett menü el van rejtve, és csak a hamburger ikon jelenik meg */
  .navbar-collapse {
    display: none;
  }

  .navbar-toggler {
    display: block; /* Hamburger ikon */
  }

  .navbar-collapse.show {
    display: block; /* A menü akkor jelenik meg, ha a hamburger ikonra kattintanak */
  }

  .navbar-nav {
    display: block;
    width: 100%;
    text-align: center;
  }

  .nav-item {
    margin: 10px 0;
  }

  .nav-link {
    display: block;
    padding: 15px;
    font-size: 18px;
  }
}
</style>
