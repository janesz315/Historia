<template>
  <div class="my-container">
    <div class="container">
      <h1>Témakörök kezelése</h1>
      <div class="mb-3">
        <label for="levelFilter">Szűrés szint szerint:</label>
        <select v-model="selectedLevel" class="form-select">
          <option value="">Mindegyik</option>
          <option value="közép">Közép</option>
          <option value="emelt">Emelt</option>
        </select>
      </div>

      <div v-for="category in filteredCategories" :key="category.id" class="card mb-3 p-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5>{{ category.category }}</h5>
          <div>
            <button class="btn btn-outline-secondary me-2" @click="toggleCategory(category.id)">
              {{ expandedCategories.includes(category.id) ? '▲' : '▼' }}
            </button>
            <button class="btn btn-outline-danger" @click="confirmDelete(category.id)">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
        <div v-if="expandedCategories.includes(category.id)">
          <p>Szint: {{ category.level }}</p>
          <p>{{ category.text }}</p>
          <div v-if="sources[category.id]">
            <h6>Források:</h6>
            <ul>
              <li v-for="source in sources[category.id]" :key="source.id">
                <a :href="source.sourceLink" target="_blank">{{ source.sourceLink }}</a>
                <p>{{ source.note }}</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";

export default {
  data() {
    return {
      categories: [],
      sources: {},
      selectedLevel: "",
      expandedCategories: [],
      store: useAuthStore(),
    };
  },
  computed: {
    filteredCategories() {
      return this.selectedLevel
        ? this.categories.filter((category) => category.level === this.selectedLevel)
        : this.categories;
    },
  },
  async created() {
    await this.fetchCategories();
    await this.fetchSources();
  },
  methods: {
    toggleCategory(categoryId) {
      const index = this.expandedCategories.indexOf(categoryId);
      if (index > -1) {
        this.expandedCategories.splice(index, 1);
      } else {
        this.expandedCategories.push(categoryId);
      }
    },
    async fetchCategories() {
      try {
        const response = await axios.get(`${BASE_URL}/categories`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        this.categories = response.data.data;
      } catch (error) {
        alert("Kategóriák betöltése sikertelen.");
      }
    },

    async fetchSources() {
      try {
        const response = await axios.get(`${BASE_URL}/sources`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        this.sources = response.data.data.reduce((acc, source) => {
          if (!acc[source.categoryId]) acc[source.categoryId] = [];
          acc[source.categoryId].push(source);
          return acc;
        }, {});
      } catch (error) {
        alert("Források betöltése sikertelen.");
      }
    },
    async deleteCategory(categoryId) {
      try {
        await axios.delete(`${BASE_URL}/categories/${categoryId}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        alert("Kategória sikeresen törölve!");
        await this.fetchCategories();
        await this.fetchSources();
      } catch (error) {
        console.error("Hiba törléskor:", error);
        alert("Törlés sikertelen.");
      }
    },
    confirmDelete(categoryId) {
      if (confirm("Biztosan törölni szeretnéd ezt a kategóriát?")) {
        this.deleteCategory(categoryId);
      }
    },
  },
};
</script>

<style scoped>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}

.container {
  max-width: auto;
  margin: auto;
  padding: 40px;
  background: rgba(255, 248, 220, 0.9);
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h1 {
  font-size: 2.8rem;
  margin-bottom: 30px;
  color: #5a3e1b;
  font-weight: bold;
}

.mb-3 {
  margin-bottom: 30px;
}

.form-select {
  background-color: rgba(255, 248, 220, 0.8);
  border: 2px solid #8b5a2b;
  color: #5a3e1b;
  padding: 10px;
  border-radius: 8px;
}

.card {
  background: rgba(255, 248, 220, 0.8);
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border: 2px solid #8b5a2b;
  margin-bottom: 20px;
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.d-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card h5 {
  color: #5a3e1b;
  font-size: 1.8rem;
}

.card button {
  min-width: 40px;
  padding: 8px;
  border-radius: 5px;
}

.card button:hover {
  background-color: #8b5a2b;
  color: #fff;
}

.card .bi-trash {
  color: #d9534f;
}

.card .bi-trash:hover {
  color: #fff;
}

.card .d-flex div {
  display: flex;
  gap: 8px;
}

.card .d-flex h5 {
  margin-right: 10px;
}

.card .d-flex div {
  gap: 5px;
}

.card .d-flex button {
  flex-shrink: 0;
  min-width: 40px;
}

.card button:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.5);
}

.card p {
  color: #5a3e1b;
}

ul {
  list-style-type: none;
  padding: 0;
}

ul li {
  margin-bottom: 10px;
}

ul li a {
  color: #007bff;
  text-decoration: none;
}

ul li a:hover {
  text-decoration: underline;
}
</style>