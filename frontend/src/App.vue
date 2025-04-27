<template>
  <div class="my-container">
    <!-- Fő fejléc -->
    <header>
      <div class="wrapper container-fluid">
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
            <ul class="navbar-nav ms-auto d-flex justify-content-end">
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
              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink
                  to="/temakorokAdmin"
                  class="nav-link"
                  @click="closeNavbar"
                  >Témakörök</RouterLink
                >
              </li>
              <li
                v-if="stateAuth.user && stateAuth.roleId === 2"
                class="nav-item"
              >
                <RouterLink
                  to="/temakorok"
                  class="nav-link"
                  @click="closeNavbar"
                  >Témakörök</RouterLink
                >
              </li>

              <li v-if="stateAuth.user" class="nav-item">
                <RouterLink to="/tesztek" class="nav-link" @click="closeNavbar"
                  >Tesztek</RouterLink
                >
              </li>

              <!-- User -->

              <!-- Admin -->
              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink to="/admin" class="nav-link" @click="closeNavbar"
                  >Admin</RouterLink
                >
              </li>

              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink
                  to="/kerdestipusok"
                  class="nav-link"
                  @click="closeNavbar"
                  >Kérdéstípusok</RouterLink
                >
              </li>

              <li
                v-if="stateAuth.user && stateAuth.roleId === 1"
                class="nav-item"
              >
                <RouterLink to="/kerdesek" class="nav-link" @click="closeNavbar"
                  >Kérdésbank</RouterLink
                >
              </li>

              <!-- Bejelentkezés és regisztráció -->
              <li v-if="!stateAuth.user" class="nav-item dropdown">
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
                      >Bejelentkezés</RouterLink
                    >
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

              <!-- Dropdown menü a felhasználónévvel -->
              <li v-if="stateAuth.user" class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="userDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="bi bi-person"></i> {{ stateAuth.user }}
                </a>
                <ul
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="userDropdown"
                >
                  <li>
                    <RouterLink
                      class="dropdown-item"
                      to="/profil"
                      @click="closeNavbar"
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
import * as bootstrap from "bootstrap";
export default {
  data() {
    return {
      stateAuth: useAuthStore(),
    };
  },
  methods: {
    closeNavbar() {
      const nav = document.querySelector(".navbar-collapse");
      if (nav && nav.classList.contains("show")) {
        nav.classList.remove("show");
      }
    },

    async LogoutAndCloseNavbar() {
      this.closeNavbar();
      await this.Logout();
    },
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
    // closeNavbar() {
    //   // Az összecsukás biztosítása
    //   const navbarCollapse = document.getElementById("navbarNav");
    //   const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
    //     toggle: false,
    //   });
    //   bsCollapse.hide(); // Bezárja a menüt
    // },
  },
};
</script>

<style>
/* Alap stílusok */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Dropdown menü testreszabása */
.dropdown-menu {
  background-color: #8b5a2b; /* Az oldal háttérszíne */
  border: 2px solid white;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

/* Dropdown menüpontok stílusa */
.dropdown-item {
  color: #ffffff; /* Fehér szöveg */
  font-size: 16px;
  padding: 10px 15px;
  transition: background-color 0.3s ease;
}

/* Hover hatás a menüpontokon */
.dropdown-item:hover {
  background-color: #a06b3c; /* Egy világosabb barna az egér fölé vitelekor */
  color: white;
}

/* A dropdown nyílának (caret) testreszabása */
.navbar .dropdown-toggle::after {
  border-top-color: white; /* Fehér nyíl */
}

.my-container {
  height: 100%;
  /* overflow: visible; */
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
  margin-left: 15px;
}

nav.navbar {
  position: sticky;
  top: 0;
  z-index: 1020;
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