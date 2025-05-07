<template>
  <div class="register-container">
    <div class="register-card">
      <h2 class="register-title">Regisztráció</h2>
      <form @submit.prevent="handleSubmit">
        <!-- Felhasználónév -->
        <div class="input-group">
          <span class="icon"><i class="fas fa-user"></i></span>
          <input
            type="text"
            v-model="username"
            placeholder="Felhasználónév*"
            required
          />
        </div>
        <p v-if="username && username.length < 2" class="error-message">
          Legalább 2 karakter hosszúnak kell lennie.
        </p>

        <!-- Email -->
        <div class="input-group">
          <span class="icon"><i class="fas fa-envelope"></i></span>
          <input
            type="email"
            v-model="email"
            placeholder="E-mail cím*"
            required
          />
        </div>

        <!-- Jelszó -->
        <div class="input-group">
          <span class="icon"><i class="fas fa-lock"></i></span>
          <input
            type="password"
            v-model="password"
            placeholder="Jelszó*"
            required
          />
        </div>
        <p v-if="password && password.length < 6" class="error-message">
          A jelszónak minimum 6 karakter hosszúnak kell lennie.
        </p>

        <!-- Jelszó megerősítés -->
        <div class="input-group">
          <span class="icon"><i class="fas fa-lock"></i></span>
          <input
            type="password"
            v-model="confirmPassword"
            placeholder="Jelszó még egyszer*"
            required
          />
        </div>
        <p
          v-if="confirmPassword && confirmPassword !== password"
          class="error-message"
        >
          A jelszavak nem egyeznek!
        </p>

        <!-- Regisztráció gomb -->
        <button
          type="submit"
          class="register-button"
          :disabled="isFormInvalid || isLoading"
        >
          <span v-if="isLoading"> Regisztráció...</span>
          <span v-else> Regisztrálás</span>
        </button>

        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../../helpers/baseUrls";

export default {
  data() {
    return {
      username: "",
      email: "",
      password: "",
      confirmPassword: "",
      roleId: 2, // Minden új felhasználó alapból roleId = 2 (normál felhasználó)
      isLoading: false,
      errorMessage: null,
    };
  },
  computed: {
    isFormInvalid() {
      return (
        !this.username ||
        this.username.length < 2 ||
        !this.email ||
        !this.password ||
        this.password.length < 6 ||
        this.password !== this.confirmPassword
      );
    },
  },
  methods: {
    async handleSubmit() {
      if (this.isFormInvalid) {
        this.errorMessage = " Kérlek, javítsd ki a hibákat!";
        return;
      }

      const payload = {
        name: this.username,
        email: this.email,
        password: this.password,
        roleId: this.roleId,
      };

      this.isLoading = true;
      this.errorMessage = null;

      try {
        await axios.post(`${BASE_URL}/users`, payload, {
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });

        alert(" Sikeres regisztráció!");
        this.$router.push("/bejelentkezes");
      } catch (error) {
        console.error("Hiba:", error);
        this.errorMessage = " Hiba történt. Próbáld újra!";
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
/* Háttér */
.register-container {
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

/* Regisztrációs kártya */
.register-card {
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

/* Cím */
.register-title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  font-family: "Cinzel", serif;
  color: #5a3e1b;
}

/* Input mezők */
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

/* Regisztrációs gomb */
.register-button {
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

.register-button:hover {
  background: #5a3e1b;
  transform: scale(1.05);
}

/* Hibaüzenetek */
.error-message {
  color: red;
  margin-top: 5px;
  font-size: 0.9rem;
}
</style>
