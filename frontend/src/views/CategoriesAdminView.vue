<template>
  <div class="container">
    <h1>Itt tudod hozzáadni, törölni, vagy módosítani a témakörökkel kapcsolatos információkat.</h1>
    
    <div v-for="category in categories" :key="category.id" class="card mb-3">
      <CategoryCard 
        :category="category" 
        @save="saveCategory" 
        @toggleEditing="toggleEditing"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import CategoryCard from '@/components/Cards/CategoryCard.vue'; // Az új komponens, amely a kategóriák kezelését végzi

export default {
  components: {
    CategoryCard,
  },
  data() {
    return {
      categories: [], // A kategóriák listája
      store: useAuthStore(), // Auth store, a token kezeléséhez
    };
  },
  async created() {
    await this.fetchCategories(); // Kategóriák betöltése a komponens létrehozásakor
  },
  methods: {
    // Kategóriák lekérése
    async fetchCategories() {
      try {
        const response = await axios.get(`${BASE_URL}/categories`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        this.categories = response.data.data.map(category => ({
          ...category,
          expanded: false, // Kezdetben nem kibővített
          editing: false,  // Kezdetben nem szerkesztett
        }));
      } catch (error) {
        console.error('Hiba a kategóriák lekérésekor:', error);
        alert("Kategóriák betöltése sikertelen.");
      }
    },

    // Kategória szerkesztésének elindítása vagy leállítása
    toggleEditing(category) {
      category.editing = !category.editing;
    },

    // Kategória mentése
    async saveCategory(category) {
      const semanticText = category.getSemanticHTML(); // QuillEditor getSemanticHTML hívása

      if (!semanticText.trim()) {
        alert("A kategória szövege nem lehet üres.");
        return;
      }

      category.text = semanticText; // A tisztított szöveg beállítása

      try {
        await axios.put(
          `${BASE_URL}/categories/${category.id}`,
          category,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );
        alert("Kategória mentése sikeres.");
        category.editing = false; // Szerkesztés befejezése
      } catch (error) {
        console.error('Hiba a kategória mentésekor:', error);
        alert("Kategória mentése sikertelen.");
      }
    },

    // Kategória törlése
    async deleteCategory(id) {
      if (!confirm("Biztosan törölni szeretnéd ezt a kategóriát?")) return;

      try {
        await axios.delete(`${BASE_URL}/categories/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        alert("Kategória törlés sikeres.");
        await this.fetchCategories(); // A frissített lista betöltése
      } catch (error) {
        console.error('Hiba a kategória törlésekor:', error);
        alert("Kategória törlése sikertelen.");
      }
    },

    // Kategória kibővítése vagy összecsukása
    toggleExpand(category) {
      category.expanded = !category.expanded;
    },
  },
};
</script>

<style>

</style>
