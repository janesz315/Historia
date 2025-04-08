<template>
  <div class="container">

    <div class="d-flex justify-content-end align-items-end" style="min-height: 100vh;">
      <div class="col-12 col-md-8 col-xxl-6">
        <h2 class="title">Eddigi tesztek</h2>
        <!-- Témakörök -->
        <table class="table table-hover user-table">
          <thead>
            <tr>
              <th scope="col">Név</th>
            <th scope="col">%</th>
          </tr>
        </thead>
        <tbody>
          <tr class="my-cursor" v-for="userTest in userTests" :key="userTest.id">
            <td>{{ userTest.testName }}</td>
            <td style="width: 50px;">{{ userTest.score }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";

export default {
  data() {
    return {
      store: useAuthStore(),
      userTests: [],
    };
  },
  mounted() {
    this.fetchUserTests();
  },
  methods: {
    async fetchUserTests() {
      try {
        const response = await axios.get(`${BASE_URL}/userTests`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.userTests = response.data.data.map((userTest) => ({
          ...userTest,
        }));
      } catch (error) {
        console.error("Hiba a kérdéstípusok lekérésekor:", error);
        alert("Kérdéstípusok betöltése sikertelen.");
      }
    }
  }
};
</script>

<style>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-attachment: fixed;
}

h2 {
  text-align: center;
}
</style>
