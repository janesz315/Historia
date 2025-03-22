<template>
  <div class="container">
    <h1>
      Itt tudod hozzáadni, törölni, vagy módosítani a témakörökkel kapcsolatos
      információkat.
    </h1>

    <div v-for="category in categories" :key="category.id" class="card mb-3">
      <CategoryCard :category="category" :saveCategory="saveCategory" />
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
      store: useAuthStore(),
    };
  },
  async created() {
    await this.fetchCategories();
  },
  methods: {
    // 🔄 Kategóriák lekérése az API-ból
    async fetchCategories() {
      try {
        const response = await axios.get(`${BASE_URL}/categories`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.categories = response.data.data.map((category) => ({
          ...category,
          expanded: false, // Kezdetben összecsukva
          editing: false, // Kezdetben nem szerkeszthető
        }));
      } catch (error) {
        console.error("Hiba a kategóriák lekérésekor:", error);
        alert("Kategóriák betöltése sikertelen.");
      }
    },

    // ✅ Kategória mentése
    async saveCategory(category) {
      try {
        await axios.patch(
          `${BASE_URL}/categories/${category.id}`,
          {
            category: category.category, // ✅ Kategória neve
            level: category.level, // ✅ Szint
            text: category.text, // ✅ Szerkesztett szöveg
          }, // 🔹 Biztosítsd, hogy a `text` kulcs létezik
          {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
              "Content-Type": "application/json", // 🔹 Kifejezetten JSON formátumban küldés
            },
          }
        );

        alert("Sikeres mentés!");
        await this.fetchCategories(); // 🔄 Újratöltjük a kategóriákat mentés után
      } catch (error) {
        console.error("Hiba mentéskor:", error);
        alert("Mentés sikertelen.");
      }
    },
  },
};
</script>
