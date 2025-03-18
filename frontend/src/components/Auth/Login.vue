<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Bejelentkezés</h2>
      <form @submit.prevent="userAuth">
        <div class="input-group">
          <span class="icon"><i class="fas fa-envelope"></i></span>
          <input 
            type="email" 
            v-model="user.email" 
            placeholder="Email cím*" 
            required
          />
        </div>

        <div class="input-group">
          <span class="icon"><i class="fas fa-lock"></i></span>
          <input 
            type="password" 
            v-model="user.password" 
            placeholder="Jelszó*" 
            required
          />
        </div>

        <button type="submit" class="login-button">
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
          this.errorMessage = " Kérlek, add meg az email címed és a jelszavad!";
          this.loading = false;
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
          this.errorMessage = " Helytelen bejelentkezési adatok!";
        }
      } catch (error) {
        console.error("Error:", error);
        this.errorMessage = " Sikertelen bejelentkezés!";
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
/* 📌 Teljes képernyős bejelentkezési doboz */
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 80vh;
}

/* 📌 Középre igazított bejelentkezési kártya */
.login-card {
  background: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 320px;
}

/* 📌 Cím */
.login-title {
  font-size: 1.8rem;
  margin-bottom: 20px;
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
  background: #0056b3;
}

/* 📌 Hibaüzenet */
.error-message {
  color: red;
  margin-top: 10px;
  font-size: 0.9rem;
}
</style>
