<template>
  <div class="my-container">
    <div class="container my-container-height">
      <h1>Témakörök kezelése</h1>

      <!-- Szűrés és gomb egy sorban -->
      <div
        class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"
      >
        <div class="filter-container flex-grow-1">
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
        <!-- Create gomb -->
        <div class="flex-shrink-0">
          <OperationsCrudCategories
            @onClickCreateButton="onClickCreateCategoryButton"
          />
        </div>
      </div>

      <!-- Témakörök listája -->
      <div
        v-for="category in filteredCategories"
        :key="category.id"
        :class="{ active: category.id === selectedRowId }"
        @click="selectCategory(category.id)"
      >
        <CategoryCard
          :category="category"
          :saveCategory="saveCategory"
          :sources="sources[category.id] || []"
          :onClickDeleteCategoryButton="onClickDeleteCategoryButton"
          :onClickUpdateCategoryButton="onClickUpdateCategoryButton"
          :onClickDeleteSourceButton="onClickDeleteSourceButton"
          :onClickUpdateSourceButton="onClickUpdateSourceButton"
          :onClickCreateSourceButton="onClickCreateSourceButton"
        />
      </div>
    </div>

    <!-- Témakör hozzáadása modal -->
    <Modal
      :title="title"
      :yes="yes"
      :no="no"
      :size="size"
      @yesEvent="yesEventHandler"
    >
      <div v-if="state == 'Delete' || state == 'Delete2'">
        {{ messageYesNo }}
      </div>

      <CategoryForm
        v-if="state == 'Create' || state == 'Update'"
        :itemForm="category"
        @saveItem="saveItemHandler"
      />

      <SourceForm
        v-if="state == 'Create2' || state == 'Update2'"
        :itemForm="source"
        @saveItem="saveSourceHandler"
      />
    </Modal>
  </div>
</template>


<script>
class Category {
  constructor(id = null, category = null, level = null, text = null) {
    this.id = id;
    this.category = category;
    this.level = level;
    this.text = text;
  }
}

class Source {
  constructor(sourceLink = null, note = null, id = null, categoryId = null) {
    this.sourceLink = sourceLink;
    this.note = note;
    this.id = id;
    this.categoryId = categoryId;
  }
}

import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import CategoryCard from "../components/Cards/CategoryCard.vue";
import CategoryForm from "@/components/Forms/CategoryForm.vue";
import OperationsCrudCategories from "@/components/Modals/OperationsCrudCategories.vue";
import * as bootstrap from "bootstrap";
import SourceForm from "@/components/Forms/SourceForm.vue";

export default {
  components: {
    CategoryCard,
    CategoryForm,
    OperationsCrudCategories,
    SourceForm,
  },
  data() {
    return {
      urlApiCategory: `${BASE_URL}/categories`,
      urlApiSource: `${BASE_URL}/sources`,
      categories: [],
      categoryById: [],
      sources: {},
      selectedLevel: "",
      store: useAuthStore(),
      selectedRowId: null,
      messageYesNo: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      category: new Category(),
      source: new Source(),
      selectedCategoryId: null,
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

  async mounted() {
    await this.fetchCategories();
    await this.fetchSources();
    this.modal = new bootstrap.Modal("#modal", {
      keyboard: false,
    });
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
      }
    },

    selectCategory(categoryId) {
      this.selectedCategoryId = categoryId;
    },

    async fetchCategoryById(id) {
      try {
        const response = await axios.get(`${BASE_URL}/categories/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.categoryById = response.data.data[0];
        console.log("Adatok: ", this.categoryById);
      } catch (error) {
        console.error("Hiba a kérdések és válaszok lekérésekor:", error);
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

        await this.fetchCategories();
      } catch (error) {
        console.error("Hiba mentéskor:", error);
        alert("Mentés sikertelen.");
      }
    },

    // A törlés végrehajtása
    async deleteCategoryById() {
      try {
        const id = this.selectedRowId;
        const response = await axios.delete(`${BASE_URL}/categories/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        // A sikeres törlés után frissíteni kell a témakörök listáját
        await this.fetchCategories();
      } catch (error) {
        console.error("Törlés hiba:", error);
      }
    },

    async deleteSourceById() {
      try {
        const id = this.selectedRowId;
        const response = await axios.delete(`${BASE_URL}/sources/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        // A sikeres törlés után frissíteni kell a források listáját
        await this.fetchSources();
      } catch (error) {
        console.error("Törlés hiba:", error);
      }
    },

    yesEventHandler() {
      if (this.state == "Delete") {
        this.deleteCategoryById();
        this.modal.hide(); // A modal bezárása a törlés után
      } else if (this.state == "Delete2") {
        this.deleteSourceById();
        this.modal.hide();
      }
    },

    async updateCategory() {
      this.loading = true;
      const id = this.selectedRowId;
      const url = `${this.urlApiCategory}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${this.store.token}`,
      };

      const data = {
        category: this.category.category,
        level: this.category.level,
        text: this.category.text,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.fetchCategories();
      } catch (error) {
        this.errorMessages = "A módosítás nem sikerült.";
      }
      this.state = "Read";
    },
    async createCategory() {
      const token = this.store.token;
      const url = this.urlApiCategory;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      const data = {
        category: this.category.category,
        level: this.category.level,
        text: null,
      };
      try {
        const response = await axios.post(url, data, { headers });
        this.fetchCategories();
      } catch (error) {
        this.errorMessages = "A bővítés nem sikerült.";
      }
      this.state = "Read";
    },

    async updateSource() {
      this.loading = true;
      const id = this.selectedRowId;
      const url = `${this.urlApiSource}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${this.store.token}`,
      };

      const data = {
        sourceLink: this.source.sourceLink,
        note: this.source.note,
        categoryId: this.source.categoryId,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.fetchSources();
      } catch (error) {
        console.error("Nem sikerült a forrás frissítése:", error);
      }
      this.state = "Read";
    },

    async createSource() {
      const token = this.store.token;
      const url = this.urlApiSource;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      const data = {
        sourceLink: this.source.sourceLink,
        note: this.source.note,
        categoryId: this.selectedCategoryId,
      };
      try {
        const response = await axios.post(url, data, { headers });
        this.fetchSources();
      } catch (error) {
        console.error("Nem sikerült a forrás létrehozása:", error);
      }
      this.state = "Read";
    },

    onClickDeleteCategoryButton(category) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${category.category} nevű témakört?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = category.id;
    },
    onClickUpdateCategoryButton(category) {
      this.state = "Update";
      this.title = "Témakör módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.category = { ...category };
      this.selectedRowId = category.id;
    },
    onClickCreateCategoryButton() {
      this.title = "Új témakör bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.state = "Create";
      this.category = new Category();
    },
    onClickDeleteSourceButton(source) {
      this.state = "Delete2";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) "${source.sourceLink}" nevű forrást?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = source.id;
    },
    onClickUpdateSourceButton(source) {
      this.state = "Update2";
      this.title = "Forrás módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.source = { ...source };
      this.selectedRowId = source.id;
    },
    onClickCreateSourceButton() {
      this.title = "Új forrás bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.state = "Create2";
      this.source = new Source();
    },
    saveItemHandler() {
      if (this.state === "Update") {
        this.updateCategory();
      } else if (this.state === "Create") {
        this.createCategory();
      }

      this.modal.hide();
    },
    saveSourceHandler() {
      if (this.state === "Update2") {
        this.updateSource();
      } else if (this.state === "Create2") {
        this.createSource();
      }

      this.modal.hide();
    },
  },
};
</script>

<style scoped>
.my-container-height {
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

@media (max-width: 768px) {
  .filter-container,
  .filter-container select,
  .flex-shrink-0 {
    width: 100%;
  }

  .filter-container {
    margin-bottom: 10px;
  }
}
</style>