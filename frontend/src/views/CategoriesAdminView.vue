<template>
  <div class="container">
    <h1>Témakörök kezelése</h1>

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
    <div v-for="category in filteredCategories" :key="category.id" class="card mb-3">
      <CategoryCard :category="category" :saveCategory="saveCategory" :confirmDelete="confirmDelete"  />
      <!-- <button class="btn btn-danger" @click="confirmDelete(category.id)">Törlés</button> -->
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import CategoryCard from "@/components/Cards/CategoryCard.vue";

export default {
  components: { CategoryCard },
  data() {
    return {
      categories: [],
      selectedLevel: "", // Szűréshez kiválasztott szint
      store: useAuthStore(),
    };
  },
  computed: {
    filteredCategories() {
      if (!this.selectedLevel) return this.categories; // Ha nincs szűrés, az összes kategória látható
      return this.categories.filter(category => category.level === this.selectedLevel);
    },
  },
  async created() {
    await this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        const response = await axios.get(`${BASE_URL}/categories`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.categories = response.data.data.map(category => ({
          ...category,
          expanded: false, 
          editing: false,  
        }));
      } catch (error) {
        console.error("Hiba a kategóriák lekérésekor:", error);
        alert("Kategóriák betöltése sikertelen.");
      }
    },

    async saveCategory(category) {
      try {
        await axios.patch(
          `${BASE_URL}/categories/${category.id}`,
          { 
            category: category.category,  //  Kategória neve
            level: category.level,        //  Szint
            text: category.text           //  Szerkesztett szöveg
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
