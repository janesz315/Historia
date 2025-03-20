<template>
  <div class="my-container" id="app">
    <div class="container mt-5">
      <h1>Felhasználói Profil</h1>

      <!-- Username -->
      <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <p><strong>Felhasználó:</strong> {{ isEditingField === 'name' ? '' : user.name }}</p>
          <div v-if="isEditingField === 'name'" class="d-flex align-items-center">
            <input type="text" class="form-control me-2" v-model="updatedField.name" placeholder="Enter new username" />
            <button class="btn btn-success me-2" @click="saveField('name')">Mentés</button>
            <button class="btn btn-secondary" @click="cancelEdit">Mégse</button>
          </div>
          <button v-else class="btn btn-warning" @click="startEdit('name')">Módosítás</button>
        </div>
      </div>

      <!-- Email -->
      <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <p><strong>Email:</strong> {{ isEditingField === 'email' ? '' : user.email }}</p>
          <div v-if="isEditingField === 'email'" class="d-flex align-items-center">
            <input type="email" class="form-control me-2" v-model="updatedField.email" placeholder="Enter new email" />
            <button class="btn btn-success me-2" @click="saveField('email')">Mentés</button>
            <button class="btn btn-secondary" @click="cancelEdit">Mégse</button>
          </div>
          <button v-else class="btn btn-warning" @click="startEdit('email')">Módosítás</button>
        </div>
      </div>

      <!-- Password -->
      <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <p><strong>Jelszó:</strong>******</p>
          <div v-if="isEditingField === 'password'" class="d-flex align-items-center">
            <input type="password" class="form-control me-2" v-model="updatedField.password"
              placeholder="Enter new password" />
            <button class="btn btn-success me-2" @click="saveField('password')">Mentés</button>
            <button class="btn btn-secondary" @click="cancelEdit">Mégse</button>
          </div>
          <button v-else class="btn btn-warning" @click="startEdit('password')">Módosítás</button>
        </div>
      </div>

      <!-- Delete Account -->
      <button class="btn btn-danger mt-3" @click="deleteUser">Fiók törlése</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../../helpers/baseUrls";
import { useAuthStore } from "../../stores/useAuthStore";

export default {
  data() {
    return {
      user: {}, // Stores current user data
      updatedField: {}, // Stores the value of the field being edited
      isEditingField: null, // Tracks which field is being edited
      store: useAuthStore(),
    };
  },
  async created() {
    try {
      const response = await axios.get(`${BASE_URL}/users/${this.store.id}`, {
        headers: {
          Authorization: `Bearer ${this.store.token}`,
        },
      });
      this.user = response.data.row; // Store user data
    } catch (error) {
      console.error("Error fetching user profile:", error);
    }
  },
  methods: {
    startEdit(field) {
      this.isEditingField = field;
      this.updatedField[field] = this.user[field];
    },
    cancelEdit() {
      this.isEditingField = null;
      this.updatedField = {};
    },
    async saveField(field) {
      try {
        // A teljes user objektumot elküldjük, de csak egy mezőt módosítunk
        const updatedUser = { ...this.user, [field]: this.updatedField[field] };

        const response = await axios.patch(
          `${BASE_URL}/users/${this.store.id}`,
          updatedUser,
          {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
            },
          }
        );

        console.log("Szerver válasza:", response.data);

        if (response.data.message === "This email already exists") {
          alert("Error: Ez az e-mail már használatban van.");
        } else {
          alert(`${field} sikeresen frissítve.`);
          this.user = response.data.row; // Frissítsük a teljes user objektumot
          this.cancelEdit();

          if (field === "email" || field === "password") {
            alert("Kérlek jelentkezz be újra.");
            this.store.clearStoredData();
            this.$router.push("/bejelentkezes");
          }
        }
      } catch (error) {
        console.error("Error updating field:", error);
        alert("Nem sikerült frissíteni a mezőt. Kérjük, próbálja újra.");
      }
    }
    ,

    async deleteUser() {
      if (confirm("Biztosan le akarod törölni a fiókodat?")) {
        try {
          await axios.delete(`${BASE_URL}/users/${this.store.id}`, {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
            },
          });
          alert("Felhasználó sikeresen törölve");
          this.store.clearStoredData(); // Clear user data and token
          this.$router.push("/register"); // Redirect to registration page
        } catch (error) {
          console.error("Error deleting user:", error);
          alert("Nem sikerült letörölni a fiókot. Kérlek próbáld újra.");
        }
      }
    },
  },
};
</script>

<style scoped>
/* 📜 Profil konténer */
.container {
  max-width: 600px;
  margin: auto;
  padding: 20px;
  background: rgba(255, 248, 220, 0.9);
  border-radius: 12px;
  border: 2px solid #8b5a2b;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.my-container {
  height: 90vh;
  margin: 0;
  padding: 0;
  background-image: url('images/parchment-texture.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  overflow: hidden;
}

/* 📜 Kártyák (Felhasználónév, Email, Jelszó) */
.card {
  background: rgba(255, 248, 220, 0.95);
  border: 2px solid #8b5a2b;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

/* 📜 Kártya belső része */
.card-body {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
}

/* 🖋 Címek */
h1 {
  font-size: 2rem;
  text-align: center;
  color: #5a3e1b;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

/* 🔘 Gombok */
button {
  padding: 10px 15px;
  border-radius: 8px;
  font-size: 1rem;
  transition: 0.3s;
}

/* 🟡 Módosítás gomb */
.btn-warning {
  background: #d4a76a;
  border: none;
  color: white;
}

.btn-warning:hover {
  background: #b58452;
  transform: scale(1.05);
}

/* ✅ Mentés gomb */
.btn-success {
  background: #5a3e1b;
  border: none;
}

.btn-success:hover {
  background: #422a14;
  transform: scale(1.05);
}

/* ⚠️ Mégse gomb */
.btn-secondary {
  background: #c0a080;
  border: none;
}

.btn-secondary:hover {
  background: #9a7c60;
}

/* ❌ Törlés gomb */
.btn-danger {
  background: #8b0000;
  width: 100%;
  font-weight: bold;
}

.btn-danger:hover {
  background: #600000;
  transform: scale(1.05);
}

/* ✏️ Input mezők */
input {
  border: 2px solid #8b5a2b;
  border-radius: 5px;
  padding: 8px;
  background: #f9f4e8;
}

input:focus {
  border-color: #5a3e1b;
  outline: none;
  box-shadow: 0 0 5px rgba(90, 62, 27, 0.5);
}
</style>
