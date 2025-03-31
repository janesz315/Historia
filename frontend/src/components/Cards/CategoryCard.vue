<template>
  <div class="card p-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 v-if="category.level === 'közép'" class="category-title">
        {{ category.category }} 
        <img :src="'/images/' + imageLevelK" alt="Kép leírása" height="35">
      </h5>
      <h5 v-else class="category-title">
        {{ category.category }} 
        <img :src="'/images/' + imageLevelE" alt="Kép leírása" height="35">
      </h5>
      <div class="d-flex justify-content-between align-items-center">
        <button class="btn btn-outline-secondary me-2" @click="toggleExpand">
          <i :class="category.expanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
        </button>
        <div v-if="stateAuth.roleId == 1">

        <OperationsCrud 
          :category="category"
          @onClickDeleteButton="onClickDeleteButton"
          @onClickUpdateButton="onClickUpdateButton"
        />
        </div>
      </div>
    </div>

    <transition name="fade">
      <div v-if="category.expanded" class="expanded-content mt-3">
        <p><strong>Szint:</strong> {{ category.level }}</p>
        <p v-html="category.text"></p>

        <button v-if="stateAuth.roleId === 1" @click="openEditModal" class="btn btn-outline-primary btn-sm">
          Szerkesztés
        </button>

        <!-- Források megjelenítése -->
        <div v-if="sources.length" class="sources mt-3">
          <h6>Források:</h6>
          <ul>
            <li v-for="source in sources" :key="source.id">
              <a :href="source.sourceLink" target="_blank">{{ source.sourceLink }}</a>
              <p>{{ source.note }}</p>
            </li>
          </ul>
        </div>
      </div>
    </transition>

    <CategoryEditModal
      :category="category"
      :saveCategory="saveCategory"
      ref="editModalRef"
    />
  </div>
</template>


<script>
import CategoryEditModal from "@/components/Modals/CategoryEditModal.vue";
import { useAuthStore } from "@/stores/useAuthStore.js";
import OperationsCrud from '../Modals/OperationsCrudCategories.vue';

export default {
  components: { CategoryEditModal, OperationsCrud},
  data() {
    return {
      imageLevelK: 'letter-k.svg',
      imageLevelE: 'letter-e.svg',
      stateAuth: useAuthStore(),
    };
  },
  props: ["category", "saveCategory", "sources", "onClickDeleteButton","onClickUpdateButton"],
  methods: {
    toggleExpand() {
      this.category.expanded = !this.category.expanded;
    },
    openEditModal() {
      this.$refs.editModalRef.openModal();
    },
  },
};
</script>

<style scoped>
.card {
  background: rgba(255, 248, 220, 0.8);
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border: 2px solid #8b5a2b;
  margin-bottom: 20px;
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.category-title {
  color: #5a3e1b;
  font-size: 1.6rem;
  font-weight: bold;
}

.btn {
  min-width: 40px;
  padding: 6px;
  border-radius: 5px;
}

.btn-outline-secondary:hover {
  background-color: #8b5a2b;
  color: #fff;
}

.bi-trash {
  color: #d9534f;
}

.bi-trash:hover {
  color: #fff;
}

.expanded-content {
  border-top: 1px solid #8b5a2b;
  padding-top: 10px;
}

/* Forráslista stílusa */
.sources h6 {
  margin-top: 15px;
  font-size: 1.2rem;
  color: #5a3e1b;
}

.sources ul {
  list-style-type: none;
  padding: 0;
}

.sources li {
  margin-bottom: 10px;
}

.sources li a {
  color: #007bff;
  text-decoration: none;
}

.sources li a:hover {
  text-decoration: underline;
}
</style>
