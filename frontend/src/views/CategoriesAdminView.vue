<template>
  <div class="my-container">
    <div class="container my-container-height">
      <h1>Témakörök kezelése</h1>

      <!-- Szűrés és gomb egy sorban -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="filter-container">
          <label for="levelFilter" class="me-2">Szűrés szint szerint:</label>
          <select
            v-model="selectedLevel"
            class="form-select"
            style="width: auto; display: inline-block"
          >
            <option value="">Mindegyik</option>
            <option value="közép">Közép</option>
            <option value="emelt">Emelt</option>
          </select>
        </div>

        <!-- Új témakör gomb -->
        <button
          class="btn btn-success"
          data-bs-toggle="modal"
          data-bs-target="#topicModal"
        >
          Új témakör
        </button>
      </div>

      <!-- Témakörök listája -->
      <div v-for="category in filteredCategories" :key="category.id">
        <CategoryCard
          :category="category"
          :saveCategory="saveCategory"
          :confirmDelete="confirmDelete"
          :sources="sources[category.id] || []"
        />
      </div>
    </div>

    <!-- Témakör hozzáadása modal -->
    <TopicModal @addTopic="addCategory" />
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import CategoryCard from "../components/Cards/CategoryCard.vue";
import TopicModal from "../components/Modals/TopicModal.vue";
import bootstrap from "bootstrap/dist/js/bootstrap.bundle.min.js";

export default {
  components: { CategoryCard, TopicModal },
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

    async addCategory(newCategory) {
      if (!newCategory.category || !newCategory.level) {
        alert("A témakör neve és szintje kötelező!");
        return;
      }

      try {
        await axios.post(
          `${BASE_URL}/categories`,
          {
            category: newCategory.category,
            level: newCategory.level,
            text: newCategory.text || "", // Leírás opcionális
          },
          { headers: { Authorization: `Bearer ${this.store.token}` } }
        );

        alert("Új témakör sikeresen létrehozva!");
        await this.fetchCategories();

        // Bezárjuk a modált programozottan
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("topicModal")
        );
        if (modal) modal.hide();
      } catch (error) {
        console.error("Hiba az új kategória létrehozásakor:", error);
        alert("Létrehozás sikertelen.");
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

.my-container-height{
  min-height: 100vh;
}
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

.filter-container {
  display: flex;
  align-items: center;
}
</style>