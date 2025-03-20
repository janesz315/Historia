<template>
    <div class="card">
      <div class="card-header" @click="category.expanded = !category.expanded">
        {{ category.category }}
        <span v-if="category.expanded">▲</span>
        <span v-else>▼</span>
      </div>
      <div v-if="category.expanded" class="card-body">
        <p class="card-text">Szint: {{ category.level }}</p>
        <div v-if="category.editing">
          <!-- A QuillEditor-ba beállítjuk a category.text-et -->
          <QuillEditor v-model="category.text" @update:value="updateText" />
          <button @click="saveCategory(category)">Mentés</button>
          <button @click="cancelEdit">Mégse</button>
        </div>
        <p v-else class="card-text">
          <span v-html="category.text || ''"></span> <!-- Ha üres, akkor üres string -->
          <button @click="category.editing = true">Szerkesztés</button>
        </p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import QuillEditor from '@/components/Editor/QuillEditor.vue';
  import { BASE_URL } from '@/helpers/baseUrls';
  
  export default {
    components: {
      QuillEditor,
    },
    props: {
      category: Object,
    },
    methods: {
        created() {
  // Az eredeti szöveget tároljuk, hogy később vissza tudjuk állítani
  this.category.originalText = this.category.text;
},
      updateText(newText) {
        // A szöveg frissítése a category.text-be
        this.category.text = newText;
      },
      cancelEdit() {
  // Visszaállítjuk az eredeti szöveget
  this.category.text = this.category.originalText; // Az eredeti szöveget visszaállítjuk
  this.category.editing = false;
  this.$emit("update:category", { ...this.category });
},
async saveCategory(category) {
  if (!category.text || category.text.trim() === '') {
    alert('A kategória szövege nem lehet üres.');
    return;
  }

  const semanticHTML = category.text; // A HTML szöveget tartalmazza
  try {
    const response = await axios.patch(`${BASE_URL}/categories/${category.id}`, {
      ...category,
      text: semanticHTML,
    });

    // Mentsd el a változtatásokat
    console.log('Kategória frissítve:', response.data);
    category.originalText = category.text; // Eredeti szöveg mentése a módosításokhoz
    category.editing = false;
  } catch (error) {
    console.error('Hiba a kategória mentésekor:', error);
  }
}
    },
  };
  </script>
  