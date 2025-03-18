<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Bejelentkezés</h2>
      <form @submit.prevent="userAuth">
        <!-- Email mező -->
        <div class="input-group">
          <span class="icon"><i class="fas fa-envelope"></i></span>
          <input
            type="email"
            v-model="user.email"
            placeholder="Email*"
            autocomplete="email"
            required
          />
        </div>

        <!-- Jelszó mező -->
        <div class="input-group">
          <span class="icon"><i class="fas fa-lock"></i></span>
          <input
            type="password"
            v-model="user.password"
            placeholder="Jelszó*"
            autocomplete="current-password"
            required
          />
        </div>

        <!-- Bejelentkezés gomb -->
        <button type="submit" class="login-button" :disabled="isLoading">
          <span v-if="isLoading"> Bejelentkezés...</span>
          <span v-else> Bejelentkezés</span>
        </button>

        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "../../stores/useAuthStore.js";
import axios from "axios";
import { BASE_URL } from "../../helpers/baseUrls";

export default {
  data() {
    return {
      user: {
        email: "test@example.com",
        password: "123",
      },
      store: useAuthStore(),
      errorMessage: null,
      isLoading: false,
    };
  },
  methods: {
    async userAuth() {
      this.errorMessage = null;
      this.isLoading = true;
      
      try {
        if (!this.user.email || !this.user.password) {
          this.errorMessage = " Email és jelszó megadása kötelező!";
          return;
        }

        const response = await axios.post(`${BASE_URL}/users/login`, this.user, {
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });

        if (response.data && response.data.user) {
          this.store.setId(response.data.user.id);
          this.store.setUser(response.data.user.name);
          this.store.setToken(response.data.user.token);
          this.store.setRoleId(response.data.user.roleId);

          this.$router.push("/");
        } else {
          this.errorMessage = " Érvénytelen adatok!";
        }
      } catch (error) {
        console.error("Hiba:", error);
        this.errorMessage = " Sikertelen bejelentkezés!";
        this.store.clearStoredData();
      } finally {
        this.isLoading = false;
      }
    },
  },
};

// Fixálja a magasságot, hogy az UI ne ugráljon a billentyűzet feljövetelekor
function setDynamicHeight() {
  document.documentElement.style.setProperty("--vh", `${window.innerHeight}px`);
}

// Meghívás betöltéskor és méretváltozáskor
window.addEventListener("resize", setDynamicHeight);
setDynamicHeight();
</script>

<style scoped>
html, body {
  height: var(--vh, 100vh); /* Dinamikus magasság a JavaScript alapján */
  overflow: hidden; /* Megakadályozza a görgetést */
}


/* 📌 Háttér */
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 80vh; /* Mindig legalább a teljes képernyőt lefedi */
  padding: 20px; /* Megakadályozza, hogy teljesen a tetejére kerüljön */
  background: #f9f9f9;
}

.login-card {
  background: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 350px;
  transition: transform 0.3s ease-in-out; /* Finom animáció a méretváltozásra */
}


/* 📌 Cím */
.login-title {
  font-size: 1.8rem;
  margin-bottom: 20px;
}

/* 📌 Input mezők */
.input-group {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 10px;
  background: #f9f9f9;
}

.input-group .icon {
  margin-right: 10px;
  color: #007bff;
}

input {
  border: none;
  outline: none;
  flex-grow: 1;
  background: transparent;
  font-size: 1rem;
}

/* 📌 Bejelentkezési gomb */
.login-button {
  background: #007bff;
  color: white;
  border: none;
  padding: 12px;
  width: 100%;
  border-radius: 8px;
  font-size: 1.2rem;
  cursor: pointer;
  transition: 0.3s;
}

.login-button:hover {
  background: #0056;
}

/* 📌 Hibaüzenetek */
.error-message {
  color: red;
  margin-top: 5px;
  font-size: 0.9rem;
}
</style>
