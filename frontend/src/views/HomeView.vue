<template>
  <div class="my-container">
    <div class="content-wrapper">
      <div class="container">
        <h1 class="title">Kezdőlap</h1>

        <div v-if="!user" class="card">
          <p>Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!</p>
          <div class="btn-group">
            <router-link to="/bejelentkezes" class="btn"
              >Bejelentkezés</router-link
            >
            <router-link to="/regisztracio" class="btn"
              >Regisztráció</router-link
            >
          </div>
        </div>

        <div v-else-if="roleId === 2" class="card">
          <p>
            Üdvözlünk, <strong>{{ user }}</strong
            >! Választhatsz az alábbi menüpontokból:
          </p>
          <div class="btn-group">
            <router-link to="/temakorok" class="btn">Témakörök</router-link>
            <router-link to="/profil" class="btn">Profil</router-link>
            <router-link to="/tesztek" class="btn">Tesztek</router-link>
          </div>
        </div>

        <div v-else-if="roleId === 1" class="card">
          <p>
            Üdvözlünk, <strong>admin</strong>! Itt kezelheted a felhasználókat
            és a tartalmakat.
          </p>
          <div class="btn-group">
            <router-link to="/temakorokadmin" class="btn"
              >Admin Témakörök</router-link
            >
            <router-link to="/tesztekadmin" class="btn"
              >Admin Tesztek</router-link
            >
            <router-link to="/admin" class="btn">Admin Felület</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "@/stores/useAuthStore.js";

export default {
  setup() {
    const store = useAuthStore();
    return {
      user: store.user,
      roleId: store.roleId,
    };
  },
};
</script>

<style scoped>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  height: 100vh;
  width: 100vw;
  overflow: hidden; /* Biztosítja, hogy ne legyen scroll */
  position: fixed; /* Megakadályozza a görgetést */
}

.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 20px;
  box-sizing: border-box;
  transform: translateY(-10%); /* Finom felemelés */
}

.title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  font-family: "Cinzel", serif;
  color: #5a3e1b;
}

.card {
  background: rgba(255, 248, 220, 0.9);
  padding: 25px; /* Nagyobb padding */
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  text-align: center;
  max-width: 450px; /* Szélesebb kártya */
  width: 100%;
  border: 2px solid #8b5a2b;
  margin: 0 auto; /* Középre igazítás */
}

.btn-group {
  display: flex;
  justify-content: center;
  gap: 8px; /* Kisebb rés a gombok között */
  flex-wrap: wrap; /* Ha szükséges, sortörés */
}

.btn {
  background: #8b5a2b;
  color: white;
  padding: 8px 12px; /* Kicsit kisebb padding */
  border-radius: 5px;
  text-decoration: none;
  transition: background 0.3s, transform 0.2s;
  font-family: "Cinzel", serif;
  font-size: 0.9rem; /* Kicsit kisebb betűméret */
  white-space: nowrap; /* Megakadályozza a sortörést */
}

.btn:hover {
  background: #5a3e1b;
  transform: scale(1.05);
}

/* Reszponzív beállítások kisebb képernyőkre */
@media (max-width: 500px) {
  .card {
    max-width: 95%;
    padding: 15px;
  }

  .btn-group {
    flex-direction: column; /* Gombok egymás alatt */
    gap: 10px;
  }

  .btn {
    width: 100%;
  }
}
</style>
