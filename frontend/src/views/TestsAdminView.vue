<template>
  <div class="container">
    <OperationsCrudUserTests @onClickCreateButton="onClickCreateButton" />

    <div class="d-flex justify-content-center" style="min-height: 100vh">
      <div class="col-12 col-md-8 col-xxl-3">
        <h2 class="title">Eddigi tesztek</h2>
        <!-- Témakörök -->
        <table class="table table-hover user-table">
          <thead>
            <tr>
              <th scope="col">Név</th>
              <th scope="col">%</th>
              <th scope="col">+</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="my-cursor"
              v-for="userTest in userTests"
              :key="userTest.id"
            >
              <td>{{ userTest.testName }}</td>
              <td style="width: 50px">{{ userTest.score }}</td>
              <td>
                <OperationsCrudUserTests
                  :userTest="userTest"
                  @onClickDeleteButton="onClickDeleteButton"
                  @onClickUpdateButton="onClickUpdateButton"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
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

      <UserTestForm
        v-if="state === 'Create' || state === 'Update'"
        :itemForm="userTest"
        @saveItem="saveItemHandler"
        :categories="categories"
      />
    </Modal>
  </div>
</template>

<script>
class UserTest {
  constructor(
    id = null,
    userId = null,
    testName = null,
    score = null,
    categoryId = null
  ) {
    this.id = id;
    this.userId = userId;
    this.testName = testName;
    this.score = score;
    this.categoryId = categoryId;
  }
}

import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import UserTestForm from "@/components/Forms/UserTestForm.vue";
import OperationsCrudUserTests from "@/components/Modals/OperationsCrudUserTests.vue";
import * as bootstrap from "bootstrap";

export default {
  components: { UserTestForm, OperationsCrudUserTests },
  data() {
    return {
      store: useAuthStore(),
      urlApiUserTest: `${BASE_URL}/userTests`,
      userTests: [],
      categories: [],
      category: {},
      selectedRowId: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      userTest: new UserTest(),
    };
  },
  mounted() {
    this.fetchUserTests();
    this.fetchCategories();
    this.modal = new bootstrap.Modal("#modal", {
      keyboard: false,
    });
  },
  methods: {
    async fetchUserTests() {
      try {
        const response = await axios.get(`${BASE_URL}/userTests`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.userTests = response.data.data.map((userTest) => ({
          ...userTest,
        }));
      } catch (error) {
        console.error("Hiba a kérdéstípusok lekérésekor:", error);
        alert("Kérdéstípusok betöltése sikertelen.");
      }
    },

    async fetchCategories() {
      try {
        const response = await axios.get(`${BASE_URL}/categories`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.categories = response.data.data.map((category) => ({
          ...category,
        }));
      } catch (error) {
        console.error("Hiba a kategóriák lekérésekor:", error);
        alert("Kategóriák betöltése sikertelen.");
      }
    },

    async createUserTest() {
      const token = this.store.token;
      const url = this.urlApiUserTest;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      const data = {
        userId: this.store.id,
        testName: this.userTest.testName,
        score: 0,
      };
      try {
        const response = await axios.post(url, data, { headers });
        this.fetchUserTests();
      } catch (error) {
        console.error("Nem sikerült a teszt létrehozása:", error);
      }
      this.state = "Read";
    },

    async updateUserTest() {
      const id = this.selectedRowId;
      const url = `${this.urlApiUserTest}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${this.store.token}`,
      };

      const data = {
        userId: this.store.id,
        testName: this.userTest.testName,
        // score: this.userTest.score,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.fetchUserTests();
      } catch (error) {
        console.error("Nem sikerült a teszt frissítése:", error);
      }
      this.state = "Read";
    },

    async deleteUserTestById() {
      try {
        const id = this.selectedRowId;
        const url = `${this.urlApiUserTest}/${id}`;
        const response = await axios.delete(url, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        await this.fetchUserTests();
      } catch (error) {
        console.error("Nem sikerült a teszt törlése:", error);
      }
    },

    onClickDeleteButton(userTest) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${userTest.testName} nevű tesztet?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = userTest.id;
    },
    onClickUpdateButton(userTest) {
      this.state = "Update";
      this.title = "Teszt módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.userTest = { ...userTest };
      this.selectedRowId = userTest.id;
    },

    onClickCreateButton() {
      this.state = "Create";
      this.title = "Új teszt bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.userTest = new UserTest();
    },

    saveItemHandler() {
      if (this.state === "Update") {
        this.updateUserTest();
      } else if (this.state === "Create") {
        this.createUserTest();
      }

      this.modal.hide(); // Ha a modalnak van hide() metódusa
    },

    yesEventHandler() {
      if (this.state == "Delete") {
        this.deleteUserTestById();
        this.modal.hide(); // A modal bezárása a törlés után
      }
    },
  },
};
</script>

<style>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-attachment: fixed;
}

.container {
  max-width: auto;
  margin: auto;
  padding: 40px;
  transform: translateY(2%);
}

h2 {
  text-align: center;
}

.my-cursor {
  cursor: pointer;
}
</style>
