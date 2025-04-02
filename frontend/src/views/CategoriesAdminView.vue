<template>
  <div class="my-container">
    <div class="container my-container-height">
      <h1>Témakörök kezelése</h1>

      <!-- Szűrés és gomb egy sorban -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="filter-container">
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
        <OperationsCrud @onClickCreateButton="onClickCreateButton" />
      </div>

      <!-- Témakörök listája -->
      <div
        v-for="category in filteredCategories"
        :key="category.id"
        :class="{ active: category.id === selectedRowId }"
      >
        <CategoryCard
          :category="category"
          :saveCategory="saveCategory"
          :sources="sources[category.id] || []"
          :onClickDeleteButton="onClickDeleteButton"
          :onClickUpdateButton="onClickUpdateButton"
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
      <div v-if="state == 'Delete'">
        {{ messageYesNo }}
      </div>

      <CategoryForm
        v-if="state == 'Create' || state == 'Update'"
        :itemForm="category"
        @saveItem="saveItemHandler"
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
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import CategoryCard from "../components/Cards/CategoryCard.vue";
// import TopicModal from "../components/Modals/CategoryForm.vue";
import CategoryForm from "@/components/Forms/CategoryForm.vue";
import OperationsCrud from "@/components/Modals/OperationsCrudCategories.vue";
// import bootstrap from "bootstrap/dist/js/bootstrap.bundle.min.js";
import * as bootstrap from "bootstrap";

export default {
  components: { CategoryCard, CategoryForm, OperationsCrud },
  data() {
    return {
      urlApiCategory: `${BASE_URL}/categories`,
      categories: [],
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

    // A törlés végrehajtása
    async deleteCategoryById() {
      try {
        const id = this.selectedRowId;
        const response = await axios.delete(`${BASE_URL}/categories/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        // A sikeres törlés után frissíteni kell a kategóriák listáját
        await this.fetchCategories();

        alert("A kategória sikeresen törölve!");
      } catch (error) {
        console.error("Törlés hiba:", error);
        alert("A kategória törlése nem sikerült!");
      }
    },

    yesEventHandler() {
      if (this.state == "Delete") {
        this.deleteCategoryById();
        this.modal.hide(); // A modal bezárása a törlés után
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
        alert("A kategória sikeresen módosítva!");
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
        // this.items.push(response.data.data);
        this.fetchCategories();
        alert("A kategória sikeresen létrehozva!");
      } catch (error) {
        this.errorMessages = "A bővítés nem sikerült.";
      }
      this.state = "Read";
    },

    onClickDeleteButton(category) {
      // if (!category || !category.id) {
      //   console.error("A kategória nem található.");
      //   alert("Hiba: A kategória nem található.");
      //   return;
      // }
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${category.category} nevű témakört?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = category.id;
    },
    onClickUpdateButton(category) {
      this.state = "Update";
      this.title = "Témakör módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.category = { ...category }; // Beállítjuk a category-t, nem item
      this.selectedRowId = category.id;
    },

    onClickCreateButton() {
      this.title = "Új témakör bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.state = "Create";
      this.category = new Category();
    },
    saveItemHandler() {
      if (this.state === "Update") {
        this.updateCategory();
      } else if (this.state === "Create") {
        this.createCategory();
      }

      this.modal.hide(); // Ha a modalnak van hide() metódusa
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
</style>