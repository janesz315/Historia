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
            <!-- Témakörök és Tesztelés menüpontok csak akkor jelennek meg, ha a felhasználó be van jelentkezve -->
            <li v-if="stateAuth.user && stateAuth.roleId === 2">
              <RouterLink to="/temakorok" class="nav-link">Témakörök</RouterLink>
            </li>
            <li v-if="stateAuth.user && stateAuth.roleId === 2">
              <RouterLink to="/teszt" class="nav-link">Tesztelés</RouterLink>
            </li>
            <li v-if="stateAuth.user && stateAuth.roleId === 1">
              <RouterLink to="/temakorokAdmin" class="nav-link">Témakörök</RouterLink>
            </li>
            <li v-if="stateAuth.user && stateAuth.roleId === 1">
              <RouterLink to="/tesztAdmin" class="nav-link">Tesztelés</RouterLink>
            </li>
            <!-- Admin menüpontok -->
            <li v-if="stateAuth.user && stateAuth.roleId === 1">
              <RouterLink to="/admin" class="nav-link">Admin Felület</RouterLink>
            </li>
            <!-- Bejelentkezés és Regisztráció csak akkor jelenik meg, ha nincs bejelentkezve -->
            <li v-if="!stateAuth.user">
              <RouterLink to="/login" class="nav-link">Bejelentkezés</RouterLink>
            </li>
            <li v-if="!stateAuth.user">
              <RouterLink to="/signup" class="nav-link">Regisztráció</RouterLink>
            </li>
            <!-- Kijelentkezés csak akkor jelenik meg, ha be van jelentkezve a felhasználó -->
            <li v-if="stateAuth.user">
              <RouterLink class="nav-link" to="#" @click="Logout()">Kijelentkezés</RouterLink>
            </li>
          </ul>
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
      this.stateAuth.clearStoredData();
      this.$router.push("/");
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

