<template>
  <div class="card">
    <div class="card-header" style="cursor:pointer" @click="category.expanded = !category.expanded">
      {{ category.category }}
      <span v-if="category.expanded">▲</span>
      <span v-else>▼</span>
    </div>
    <div v-if="category.expanded" class="card-body">
      <p class="card-text">Szint: {{ category.level }}</p>
      <p class="card-text" v-html="category.text"></p>
      <button @click="openEditModal">Szerkesztés</button>
    </div>

    <CategoryEditModal :category="category" :saveCategory="saveCategory" ref="editModalRef" />
  </div>
</template>

<script>
import CategoryEditModal from "@/components/Modals/CategoryEditModal.vue";

export default {
  components: { CategoryEditModal },
  props: {
    category: Object,
    saveCategory: Function,
  },
  methods: {
    openEditModal() {
      this.$refs.editModalRef.openModal();
    },
  },
};
</script>
