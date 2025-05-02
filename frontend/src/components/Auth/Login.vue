<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Bejelentkezés</h2>
      <form @submit.prevent="userAuth">
        <div class="input-group">
          <span class="icon"><i class="fas fa-envelope"></i></span>
          <input type="email" v-model="user.email" placeholder="Email cím*" required id="email"/>
        </div>

        <div class="input-group">
          <span class="icon"><i class="fas fa-lock"></i></span>
          <input type="password" v-model="user.password" placeholder="Jelszó*" required id="password"/>
        </div>

        <button type="submit" class="login-button" id="login-button">
          <span v-if="loading"> Bejelentkezés...</span>
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
        email: "",
        password: "",
      },
      store: useAuthStore(),
      errorMessage: null,
      loading: false,
    };
  },
  methods: {
    async userAuth() {
      this.errorMessage = null;
      this.loading = true;

      try {
        if (!this.user.email || !this.user.password) {
          this.errorMessage = "Kérlek, add meg az email címed és a jelszavad!";
          this.loading = false;
          return;
        }

        const response = await axios.post(
          `${BASE_URL}/users/login`,
          this.user,
          {
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
            },
          }
        );

        if (response.data && response.data.user) {
          this.store.setId(response.data.user.id);
          this.store.setUser(response.data.user.name);
          this.store.setToken(response.data.user.token);
          this.store.setRoleId(response.data.user.roleId);

          // 🔴 Itt állítsd be az új tokent az Axios fejlécekhez
          axios.defaults.headers.common[
            "Authorization"
          ] = `Bearer ${response.data.user.token}`;

          this.$router.push("/");
        } else {
          this.errorMessage = "Helytelen bejelentkezési adatok!";
        }
      } catch (error) {
        console.error("Error:", error);
        this.errorMessage = "Sikertelen bejelentkezés!";
      } finally {
        this.loading = false;
      }
      // Fixálja a magasságot, hogy az UI ne ugráljon a billentyűzet feljövetelekor
      function setDynamicHeight() {
        document.documentElement.style.setProperty(
          "--vh",
          `${window.innerHeight}px`
        );
      }

      // Meghívás betöltéskor és méretváltozáskor
      window.addEventListener("resize", setDynamicHeight);
      setDynamicHeight();
    },
  },
};
</script>

<style scoped>
html,
body {
  height: var(--vh, 100vh);
  /* Dinamikus magasság a JavaScript alapján */
  overflow: hidden;
  /* Megakadályozza a görgetést */
}

/* 📌 Teljes képernyős bejelentkezési doboz */
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  height: 100vh;
  width: 100vw;
  overflow: hidden;
}

/* 📌 Középre igazított bejelentkezési kártya */
.login-card {
  background: rgba(255, 248, 220, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  text-align: center;
  max-width: 400px;
  width: 100%;
  border: 2px solid #8b5a2b;
  transform: translateY(-10%);
}

/* 📌 Cím */
.login-title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  font-family: 'Cinzel', serif;
  color: #5a3e1b
}

/* 📌 Bemeneti mezők */
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

/* 📌 Bejelentkezés gomb */
.login-button {
  background: #8b5a2b;
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
  background: #5a3e1b;
  transform: scale(1.05);
}

/* 📌 Hibaüzenet */
.error-message {
  color: red;
  margin-top: 10px;
  font-size: 0.9rem;
}
</style>

