<template>
  <div class="container mt-5">
    <h1>Sign up</h1>
    <form @submit.prevent="handleSubmit">
      <!-- Username -->
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          class="form-control"
          id="username"
          v-model="username"
          autocomplete="username"
          required
        />
        <div v-if="username && username.length < 2" class="text-danger">
          Username must be at least 2 characters long.
        </div>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input
          type="email"
          class="form-control"
          id="exampleInputEmail1"
          v-model="email"
          autocomplete="email"
          required
        />
        <div id="emailHelp" class="form-text">
          We'll never share your email with anyone else.
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password1" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          id="password1"
          v-model="password"
          autocomplete="new-password"
          required
        />
        <div v-if="password && password.length < 6" class="text-danger">
          Password must be at least 6 characters long.
        </div>
      </div>

      <!-- Confirm Password -->
      <div class="mb-3">
        <label for="password2" class="form-label">Confirm Password</label>
        <input
          type="password"
          class="form-control"
          id="password2"
          v-model="confirmPassword"
          autocomplete="new-password"
          required
        />
        <div
          v-if="confirmPassword && confirmPassword !== password"
          class="text-danger"
        >
          Passwords do not match.
        </div>
      </div>

      <!-- Submit button -->
      <button
        type="submit"
        class="btn btn-primary"
        :disabled="isFormInvalid || isLoading"
      >
        <span
          v-if="isLoading"
          class="spinner-border spinner-border-sm"
          role="status"
          aria-hidden="true"
        ></span>
        <span v-else>Submit</span>
      </button>
    </form>
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
      isLoading: false,
    };
  },
  computed: {
    isFormInvalid() {
      return (
        !this.username ||
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
        alert("Please fix the errors in the form.");
        return;
      }

      const url = `${BASE_URL}/users`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
      };

      const payload = {
        name: this.username,
        email: this.email,
        password: this.password,
      };

      this.isLoading = true;
      try {
        const response = await axios.post(url, payload, { headers });
        alert("Registration successful!");
        this.$router.push("/login");
      } catch (error) {
        const status = error.response?.status;
        if (status === 409) {
          alert("Error: This email address is already registered.");
        } else if (status === 400) {
          alert("Error: Invalid request. Please check your inputs.");
        } else if (status === 500) {
          alert("Error: Server error. Please try again later.");
        } else {
          alert("An unknown error occurred. Please try again.");
        }
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>


<style scoped>
/* Optional: Custom styling for form validation feedback */
.form-control:invalid {
  border-color: red;
}

.form-control:valid {
  border-color: green;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.text-danger {
  font-size: 0.875rem;
}
</style>
