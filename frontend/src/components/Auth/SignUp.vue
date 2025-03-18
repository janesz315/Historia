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
            placeholder="Jelszó mégegyszer*"
            required
          />
        </div>
        <p v-if="confirmPassword && confirmPassword !== password" class="error-message">
           A jelszavak nem egyeznek!
        </p>

        <!-- Regisztráció gomb -->
        <button type="submit" class="register-button" :disabled="isFormInvalid || isLoading">
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
          headers: { Accept: "application/json", "Content-Type": "application/json" },
        });

        alert(" Sikeres regisztráció!");
        this.$router.push("/login");
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
/* 📌 Háttér */
.register-container {
 display: flex;
  align-items: center;
  justify-content: center;
  min-height: 80vh; /* Mindig legalább a teljes képernyőt lefedi */
  padding: 20px; /* Megakadályozza, hogy teljesen a tetejére kerüljön */
  background: #f9f9f9;
}

/* 📌 Regisztrációs kártya */
.register-card {
   background: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 350px;
  transition: transform 0.3s ease-in-out; /* Finom animáció a méretváltozásra */
}

/* 📌 Cím */
.register-title {
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

/* 📌 Regisztrációs gomb */
.register-button {
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

.register-button:hover {
  background: #0056b3;
}

/* 📌 Hibaüzenetek */
.error-message {
  color: red;
  margin-top: 5px;
  font-size: 0.9rem;
}
</style>
