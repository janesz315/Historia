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
      </div>

      <!-- Témakörök listája -->
      <div
        v-for="category in filteredCategories"
        :key="category.id"
        :class="{ active: category.id === selectedRowId }"
      >
        <OperationsCrud
          :category="category"
          @onClickDeleteButton="onClickDeleteButton"
          @onClickUpdate="onClickUpdate"
          @onClickCreate="onClickCreate"
        />
        <CategoryCard
          :category="category"
          :saveCategory="saveCategory"
          :sources="sources[category.id] || []"
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
import OperationsCrud from "@/components/Modals/OperationsCrud.vue";
import bootstrap from "bootstrap/dist/js/bootstrap.bundle.min.js";

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
      errorMessages: null,
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
  async created() {
    await this.fetchCategories();
    await this.fetchSources();
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

    // async addCategory(newCategory) {
    //   if (!newCategory.category || !newCategory.level) {
    //     alert("A témakör neve és szintje kötelező!");
    //     return;
    //   }

    //   try {
    //     await axios.post(
    //       `${BASE_URL}/categories`,
    //       {
    //         category: newCategory.category,
    //         level: newCategory.level,
    //         text: newCategory.text || "", // Leírás opcionális
    //       },
    //       { headers: { Authorization: `Bearer ${this.store.token}` } }
    //     );

    //     alert("Új témakör sikeresen létrehozva!");
    //     await this.fetchCategories();

    //     // Bezárjuk a modált programozottan
    //     const modal = bootstrap.Modal.getInstance(
    //       document.getElementById("topicModal")
    //     );
    //     if (modal) modal.hide();
    //   } catch (error) {
    //     console.error("Hiba az új kategória létrehozásakor:", error);
    //     alert("Létrehozás sikertelen.");
    //   }
    // },

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

    // async deleteCategory(categoryId) {
    //   try {
    //     await axios.delete(`${BASE_URL}/categories/${categoryId}`, {
    //       headers: { Authorization: `Bearer ${this.store.token}` },
    //     });
    //     alert("Kategória sikeresen törölve!");
    //     await this.fetchCategories();
    //   } catch (error) {
    //     alert("Törlés sikertelen.");
    //   }
    // },

    // confirmDelete(categoryId) {
    //   if (confirm("Biztosan törölni szeretnéd ezt a kategóriát?")) {
    //     this.deleteCategory(categoryId);
    //   }
    // },

    // async deleteItemById() {
    //   const id = this.selectedRowId;
    //   const token = this.store.token;

    //   const url = `${this.urlApi}/${id}`;
    //   const headers = {
    //     Accept: "application/json",
    //     "Content-Type": "application/json",
    //     Authorization: `Bearer ${token}`,
    //   };

    //   try {
    //     const response = await axios.delete(url, { headers });
    //     // this.items = this.items.filter((sport) => sport.id !== id);
    //     this.fetchCategories();
    //   } catch (error) {
    //     this.errorMessages =
    //       "A diák nem törölhető";
    //   }
    // },

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
      }
    },

    onClickDeleteButton(category) {
      if (!category || !category.id) {
        console.error("A kategória nem található.");
        alert("Hiba: A kategória nem található.");
        return;
      }
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${category.category} nevű témakört?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = category.id;
    },
    onClickUpdate(category) {
      this.state = "Update";
      this.title = "Diák módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.item = { ...category };
      this.selectedRowId = category.id;
    },

    onClickCreate() {
      this.title = "Új diák bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.state = "Create";
      this.item = new Category();
    },
    saveItemHandler() {
      if (this.state === "Update") {
        this.updateItem();
      } else if (this.state === "Create") {
        this.createItem();
      }

      this.modal.hide();
    },

    goToPage(page) {
      this.currentPage = page;
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