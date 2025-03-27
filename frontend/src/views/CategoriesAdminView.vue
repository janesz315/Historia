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

      <div
        v-for="category in filteredCategories"
        :key="category.id"
      >
        <CategoryCard
          :category="category"
          :saveCategory="saveCategory"
          :confirmDelete="confirmDelete"
          :sources="sources[category.id] || []"
        />
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import CategoryCard from "../components/Cards/CategoryCard.vue";

export default {
  components: { CategoryCard },
  data() {
    return {
      categories: [],
      sources: {},
      selectedLevel: "",
      store: useAuthStore(),
    };
  },
  computed: {
    filteredCategories() {
      if (!this.selectedLevel) return this.categories;
      return this.categories.filter(
        (category) => category.level === this.selectedLevel
      );
    },
  },
  async created() {
    await this.fetchCategories();
    await this.fetchSources();
  },
  methods: {
    async fetchCategories() {
      try {
        const response = await axios.get(`${BASE_URL}/categories`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.categories = response.data.data.map((category) => ({
          ...category,
          expanded: false,
          editing: false,
        }));
      } catch (error) {
        console.error("Hiba a kategóriák lekérésekor:", error);
        alert("Kategóriák betöltése sikertelen.");
      }
    },

    async fetchSources() {
      try {
        const response = await axios.get(`${BASE_URL}/sources`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        // A sources objektumban kategória ID szerint csoportosítjuk a forrásokat
        this.sources = response.data.data.reduce((acc, source) => {
          if (!acc[source.categoryId]) acc[source.categoryId] = [];
          acc[source.categoryId].push(source);
          return acc;
        }, {});
      } catch (error) {
        console.error("Hiba a források lekérésekor:", error);
        alert("Források betöltése sikertelen.");
      }
    },

    async saveCategory(category) {
      try {
        await axios.patch(
          `${BASE_URL}/categories/${category.id}`,
          {
            category: category.category,
            level: category.level,
            text: category.text,
          },
          { headers: { Authorization: `Bearer ${this.store.token}` } }
        );

        alert("Sikeres mentés!");
        await this.fetchCategories();
      } catch (error) {
        console.error("Hiba mentéskor:", error);
        alert("Mentés sikertelen.");
      }
    },

    async deleteCategory(categoryId) {
      try {
        await axios.delete(`${BASE_URL}/categories/${categoryId}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        alert("Kategória sikeresen törölve!");
        await this.fetchCategories();
      } catch (error) {
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
</style>
