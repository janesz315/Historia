<template>
  <div class="container">
    <OperationsCrudUserTests
      style="margin-top: 10px"
      @onClickCreateButton="onClickCreateButton"
    />

    <div
      class="row"
      style="min-height: 100vh;"
    >
      <div class="col-12 col-md-6">
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
                <button
                  class="btn btn-outline-info ms-2"
                  @click="loadTestQuestions(userTest.id)"
                >
                  Kitöltés
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    <div class="col-12 col-md-6" v-if="currentUserTestId">
    <h3>Aktív Teszt: {{ currentUserTestId }}</h3>

    <div
      v-for="(testQuestion, index) in testQuestions"
      :key="testQuestion.questionId"
      class="mb-4 p-3 border rounded"
    >
      <h5>{{ index + 1 }}. {{ testQuestion.question }}</h5>

      <div
        v-for="answer in testQuestion.answers"
        :key="answer.answerId"
        class="form-check"
      >
        <input
          class="form-check-input"
          type="radio"
          :value="answer.answerId"
          v-model="testQuestion.answerId"
        />
        <label class="form-check-label">
          {{ answer.answer }}
        </label>
      </div>
    </div>

    <button class="btn btn-success" @click="submitTestAnswers">
      Teszt Beküldése
    </button>
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
  constructor(id = null, userId = null, testName = null, score = null) {
    this.id = id;
    this.userId = userId;
    this.testName = testName;
    this.score = score;
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
      selectedRowId: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      userTest: new UserTest(),
      currentUserTestId: null,
      testQuestions: [],
    };
  },
  mounted() {
    this.fetchUserTests();
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
        score: null,
      };

      try {
        const response = await axios.post(url, data, { headers });
        const createdUserTest = response.data.data; // itt kapod vissza a létrehozott tesztet
        const userTestId = createdUserTest.id;

        // Miután létrejött a teszt, generáljuk le a 10 véletlen kérdést
        await this.generateTestQuestions(userTestId);

        await this.fetchUserTests(); // Tesztlista frissítése
      } catch (error) {
        console.error("Nem sikerült a teszt létrehozása:", error);
      }

      this.state = "Read";
    },

    async generateTestQuestions(userTestId) {
      try {
        // Lekérdezzük az összes kérdést (kérdés + válaszok)
        const questionsResponse = await axios.get(
          `${BASE_URL}/getQuestionsWithTypesAndAnswers`,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );

        const allQuestions = questionsResponse.data.data;

        // Véletlenszerűen kiválasztunk 10 kérdést
        const selectedQuestions = this.getRandomElements(allQuestions, 10);

        // Előkészítjük a küldendő adatokat
        const testQuestions = selectedQuestions.map((question) => ({
          userTestId: userTestId,
          questionId: question.questionId,
          answerId: null, // Ezt majd a felhasználó fogja kiválasztani
        }));

        // Egyesével vagy egyszerre elküldheted őket.
        for (const tq of testQuestions) {
          await axios.post(`${BASE_URL}/testQuestions`, tq, {
            headers: { Authorization: `Bearer ${this.store.token}` },
          });
        }
      } catch (error) {
        console.error("Nem sikerült a kérdéseket létrehozni:", error);
      }
    },

    // Segédfüggvény, ami X véletlenszerű elemet választ
    getRandomElements(arr, count) {
      const shuffled = arr.sort(() => 0.5 - Math.random());
      return shuffled.slice(0, count);
    },

    async loadTestQuestions(userTestId) {
      this.testQuestions = [];
      this.currentUserTestId = userTestId;
      try {
        const response = await axios.get(`${BASE_URL}/testQuestions`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        var testQuestionsAll = response.data.data;
      } catch (error) {
        console.error("Nem sikerült betölteni a kérdéseket:", error);
      }
      var questionIds = [];

      for (let i = 0; i < testQuestionsAll.length; i++) {
        if (testQuestionsAll[i].userTestId == this.currentUserTestId) {
          questionIds.push(testQuestionsAll[i].questionId);
        }
      }
      // console.log(questionIds);

      for (const questionId of questionIds) {
        try {
          const response = await axios.get(
            `${BASE_URL}/getQuestionsWithTypesAndAnswers/${questionId}`,
            {
              headers: { Authorization: `Bearer ${this.store.token}` },
            }
          );
          this.testQuestions.push(response.data.data[0]);
        } catch (error) {
          console.error("Nem sikerült betölteni a kérdést:", error);
        }
      }

      console.log(this.testQuestions);
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

h2 {
  text-align: center;
}
</style>
