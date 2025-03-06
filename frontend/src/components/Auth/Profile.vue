<template>
  <div class="container mt-5">
    <h1>User Profile</h1>

    <!-- Username -->
    <div class="card mb-3">
      <div class="card-body d-flex justify-content-between align-items-center">
        <p><strong>Username:</strong> {{ isEditingField === 'name' ? '' : user.name }}</p>
        <div v-if="isEditingField === 'name'" class="d-flex align-items-center">
          <input
            type="text"
            class="form-control me-2"
            v-model="updatedField.name"
            placeholder="Enter new username"
          />
          <button class="btn btn-success me-2" @click="saveField('name')">Save</button>
          <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
        </div>
        <button v-else class="btn btn-warning" @click="startEdit('name')">Edit</button>
      </div>
    </div>

    <!-- Email -->
    <div class="card mb-3">
      <div class="card-body d-flex justify-content-between align-items-center">
        <p><strong>Email:</strong> {{ isEditingField === 'email' ? '' : user.email }}</p>
        <div v-if="isEditingField === 'email'" class="d-flex align-items-center">
          <input
            type="email"
            class="form-control me-2"
            v-model="updatedField.email"
            placeholder="Enter new email"
          />
          <button class="btn btn-success me-2" @click="saveField('email')">Save</button>
          <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
        </div>
        <button v-else class="btn btn-warning" @click="startEdit('email')">Edit</button>
      </div>
    </div>

    <!-- Password -->
    <div class="card mb-3">
      <div class="card-body d-flex justify-content-between align-items-center">
        <p><strong>Password:</strong>******</p>
        <div v-if="isEditingField === 'password'" class="d-flex align-items-center">
          <input
            type="password"
            class="form-control me-2"
            v-model="updatedField.password"
            placeholder="Enter new password"
          />
          <button class="btn btn-success me-2" @click="saveField('password')">Save</button>
          <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
        </div>
        <button v-else class="btn btn-warning" @click="startEdit('password')">Edit</button>
      </div>
    </div>

    <!-- Delete Account -->
    <button class="btn btn-danger mt-3" @click="deleteUser">Delete Account</button>
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
    const response = await axios.patch(
      `${BASE_URL}/users/${this.store.id}`,
      { [field]: this.updatedField[field] },
      {
        headers: {
          Authorization: `Bearer ${this.store.token}`,
        },
      }
    );

    // Logoljunk ki minden adatot a válaszból, hogy lássuk, mit kapunk
    console.log(response.data); // Ellenőrizd, mi érkezik a válaszban

    // Ellenőrizzük, hogy van-e üzenet, ami azt jelzi, hogy az email már létezik
    if (response.data.message === "This email already exists") {
      alert("Error: This email is already in use.");
    } else {
      alert(`${field} updated successfully.`);
      this.user[field] = this.updatedField[field];
      this.cancelEdit();

      // Ha email vagy jelszó módosul, log-out és átirányítás
      if (field === "email" || field === "password") {
        alert("Please log in again.");
        this.store.clearStoredData();
        this.$router.push("/login");
      }
    }
  } catch (error) {
    console.error("Error updating field:", error);
    alert("Failed to update field. Please try again.");
  }
},

    async deleteUser() {
      if (confirm("Are you sure you want to delete your account?")) {
        try {
          await axios.delete(`${BASE_URL}/users/${this.store.id}`, {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
            },
          });
          alert("Account deleted successfully.");
          this.store.clearStoredData(); // Clear user data and token
          this.$router.push("/register"); // Redirect to registration page
        } catch (error) {
          console.error("Error deleting user:", error);
          alert("Failed to delete account. Please try again.");
        }
      }
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 600px;
  margin: auto;
}
.card {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.card-body {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
button {
  margin-top: 10px;
}
</style>
