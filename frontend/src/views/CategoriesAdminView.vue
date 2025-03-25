<template>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Témakörök kezelése</h1>
      <button @click="openModal" class="btn btn-primary">Új Témakör</button>
    </div>

    <!-- Új témakör modal -->
    <TopicModal v-if="showModal" :showModal="showModal" @close="closeModal" @submit="addNewTopic" />

    <!-- Szűrő -->
    <div class="mb-3">
      <label for="levelFilter">Szűrés szint szerint:</label>
      <select v-model="selectedLevel" class="form-select">
        <option value="">Mindegyik</option>
        <option value="közép">Közép</option>
        <option value="emelt">Emelt</option>
      </select>
    </div>

    <!-- Szűrt kategóriák megjelenítése -->
    <div v-for="category in filteredCategories" :key="category.id" class="card mb-3 p-3">
      <CategoryCard :category="category" :saveCategory="saveCategory" :confirmDelete="confirmDelete" />

      <!-- Kategória leírás -->

      <!-- Források -->
      <div v-if="sources[category.id]">
        <h6>Források:</h6>
        <ul>
          <li v-for="source in sources[category.id]" :key="source.id">
            <a :href="source.sourceLink" target="_blank">{{
              source.sourceLink
              }}</a>
            <p>{{ source.note }}</p>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import CategoryCard from "@/components/Cards/CategoryCard.vue";
import TopicModal from "@/components/Modals/TopicModal.vue";

export default {
  components: { CategoryCard, TopicModal },
  data() {
    return {
      categories: [],
      sources: {},
      selectedLevel: "",
      store: useAuthStore(),
      showModal: false,
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
    async addNewTopic(newTopic) {
      try {
        // Extra ellenőrzés a szintre
        if (!["közép", "emelt"].includes(newTopic.level)) {
          alert("Érvénytelen szint érték!");
          return;
        }

        const response = await axios.post(
          `${BASE_URL}/categories`,
          {
            category: newTopic.category.trim(),
            level: newTopic.level, // Itt már biztos helyes az érték
            text: newTopic.text.trim(),
          },
          {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
              "Content-Type": "application/json",
            },
          }
        );

        alert("Sikeres létrehozás!");
        await this.fetchCategories();
        this.showModal = false;
      } catch (error) {
        console.error("Hiba részletei:", {
          request: error.config,
          response: error.response,
        });
        alert(`Hiba: ${error.response?.data?.message || error.message}`);
      }
    },

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
/* Opcionális stílusok a jobb megjelenítéshez */
.btn-primary {
  background-color: #28a745;
  border-color: #28a745;
}

.card {
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
</style>