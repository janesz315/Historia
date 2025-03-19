<template>
  <div class="container">
    <h1>Itt tudod hozzáadni, törölni, vagy módosítani a témakörökkel kapcsolatos információkat.</h1>
    <div v-for="category in categories" :key="category.id" class="card mb-3">
      <div class="card-header" @click="category.expanded = !category.expanded" style="cursor: pointer;">
        {{ category.category }}
        <span v-if="category.expanded">▲</span>
        <span v-else>▼</span>
      </div>
      <div v-if="category.expanded" class="card-body">
        <p class="card-text">Szint: {{ category.level }}</p>
        <p class="card-text">Leírás: {{ category.text }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { BASE_URL } from "../helpers/baseUrls";

export default {
  setup() {
    const categories = ref([]);

    const fetchCategories = async () => {
  try {
    const response = await axios.get(`${BASE_URL}/categories`);
    categories.value = response.data.data.map(category => ({
      ...category,
      expanded: false, // Alapértelmezetten minden kategória összecsukva
    }));
  } catch (error) {
    console.error('Hiba a kategóriák lekérésekor:', error);
  }
};
    // const fetchCategories = async () => {
    //   try {
    //     const response = await axios.get(`${BASE_URL}/categories`); // Feltételezve, hogy a backend végpontja /api/categories
    //     categories.value = response.data;
    //   } catch (error) {
    //     console.error('Hiba a kategóriák lekérésekor:', error);
    //   }
    // };

    onMounted(fetchCategories);

    return {
      categories,
    };
  },
};
</script>

<style>

</style>