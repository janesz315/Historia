<template>
  <div class="card p-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 v-if="category.level === 'közép'" class="category-title">
        {{ category.category }}
        <img :src="'/images/' + imageLevelK" alt="Kép leírása" height="35" />
      </h5>
      <h5 v-else class="category-title">
        {{ category.category }}
        <img :src="'/images/' + imageLevelE" alt="Kép leírása" height="35" />
      </h5>
      <div class="d-flex justify-content-between align-items-center">
        <button class="btn btn-outline-secondary me-2" @click="toggleExpand">
          <i
            :class="
              category.expanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down'
            "
          ></i>
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

        <button
          v-if="stateAuth.roleId === 1"
          @click="openEditModal"
          class="btn btn-outline-primary btn-sm"
        >
          Szerkesztés
        </button>

        <!-- Források megjelenítése -->
        <div v-if="sources.length" class="sources mt-3">
          <h6>Források:</h6>
          <ul>
            <li v-for="(source, index) in sources" :key="source.id">
              <a :href="source.sourceLink" target="_blank">{{
                source.sourceLink
              }}</a>
              <p>{{ source.note }}</p>

              <!-- Szerkesztés gomb csak adminoknak -->
              <button
                v-if="stateAuth.roleId === 1"
                @click="openSourceEditModal(index)"
                class="btn btn-sm btn-outline-warning"
              >
                <i class="bi bi-pencil"></i> Szerkesztés
              </button>
            </li>
          </ul>
        </div>
        <!-- Ha nincs forrás, akkor is jelenjen meg a szerkesztés gomb -->
        <div v-if="stateAuth.roleId === 1 && !sources.length" class="mt-3">
          <button
            @click="openSourceEditModal(-1)"
            class="btn btn-outline-info btn-sm"
          >
            <i class="bi bi-plus-circle"></i> Forrás hozzáadása
          </button>
        </div>
      </div>
    </transition>

    <!-- Kategória szerkesztő modal -->
    <CategoryEditModal
      :category="category"
      :saveCategory="saveCategory"
      ref="editModalRef"
    />

    <!-- Forrás szerkesztő modal -->
    <SourceEditModal
      v-if="editingSource !== null"
      :localSource="
        editingSource === -1
          ? { sourceLink: '', note: '' }
          : sources[editingSource]
      "
      @saveItem="saveSource"
      @close="editingSource = null"
    />
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "@/helpers/baseUrls";
import CategoryEditModal from "@/components/Modals/CategoryEditModal.vue";
import SourceEditModal from "@/components/Modals/SourceEditModal.vue";
import { useAuthStore } from "@/stores/useAuthStore.js";
import OperationsCrud from "../Modals/OperationsCrudCategories.vue";

export default {
  components: { CategoryEditModal, SourceEditModal, OperationsCrud },
  data() {
    return {
      imageLevelK: "letter-k.svg",
      imageLevelE: "letter-e.svg",
      stateAuth: useAuthStore(),
      editingSource: null,
    };
  },
  props: [
    "category",
    "saveCategory",
    "sources",
    "onClickDeleteButton",
    "onClickUpdateButton",
  ],
  methods: {
    toggleExpand() {
      this.category.expanded = !this.category.expanded;
    },
    openEditModal() {
      this.$refs.editModalRef.openModal();
    },
    openSourceEditModal(index) {
      this.editingSource = index;
    },
    saveSource(updatedSource) {
      console.log("Mentés indítása:", updatedSource); // Debugging, hogy lásd a kitöltött adatokat

      // Validáció
      if (!updatedSource.sourceLink || !updatedSource.note) {
        console.error("A forrás linkje és a megjegyzés nem lehet üres!");
        return; // Ha üres, ne küldd el
      }

      // Küldés az API-nak
      axios
        .patch(`${BASE_URL}/sources/${updatedSource.id}`, updatedSource)
        .then((response) => {
          console.log("Válasz az API-tól:", response.data);
          // Forrás frissítése
          const index = this.sources.findIndex(
            (source) => source.id === updatedSource.id
          );
          if (index !== -1) {
            this.sources[index] = { ...updatedSource };
          }
        })
        .catch((error) => {
          console.error("Hiba történt a forrás frissítésekor:", error);
        });
        console.log("Szülő komponens saveSource:", updatedSource);
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
