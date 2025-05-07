<template>
  <div class="container">
    <OperationsCrudUserTests
      style="margin-top: 10px"
      @onClickCreateButton="onClickCreateButton"
    />

    <div class="row" style="min-height: 100vh">
      <div class="col-12 col-md-6">
        <h2 class="title">Eddigi tesztek</h2>
        <!-- Témakörök -->
        <div class="user-table-container table-responsive">
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
                <td style="width: 50px">
                  {{ userTest.score !== null ? userTest.score : "N/A" }}
                </td>
                <td class="d-flex justify-content-start align-items-center">
                  <OperationsCrudUserTests
                    :userTest="userTest"
                    @onClickDeleteButton="onClickDeleteButton"
                    @onClickUpdateButton="onClickUpdateButton"
                  />

                  <!-- Ha nincs score (null), akkor a "Kitöltés" gomb jelenik meg -->
                  <button
                    class="btn btn-outline-info ms-2"
                    v-if="userTest.score === null"
                    @click="loadTestQuestions(userTest.id)"
                  >
                    Kitöltés
                  </button>

                  <!-- Ha van score, akkor a "Megtekintés" gomb jelenik meg -->
                  <button
                    class="btn btn-outline-info ms-2"
                    v-else
                    @click="loadTest(userTest.id)"
                  >
                    Megtekintés
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <span v-if="generating"><p>A teszt generálása folyamatban.</p></span>
        </div>
      </div>
      <div class="col-12 col-md-6" v-if="currentUserTestId">
        <!-- Teszt kérdések tartálya -->
        <div class="test-questions-container">
          <div
            v-for="(testQuestion, index) in testQuestions"
            :key="testQuestion.questionId"
            class="test-card mb-4 p-4 border rounded shadow-sm"
          >
            <h5 class="question-title">
              {{ index + 1 }}. {{ testQuestion.question }}
            </h5>

            <!-- Válaszok -->
            <div
              v-for="answer in testQuestion.answers"
              :key="answer.answerId"
              class="form-check answer-option"
            >
              <input
                class="form-check-input"
                type="radio"
                :value="answer.answerId"
                v-model="testQuestion.selectedAnswerId"
                :disabled="submitted"
              />
              <label
                :class="getAnswerClass(testQuestion, answer)"
                class="answer-label"
              >
                {{ answer.answer }}
              </label>
            </div>
          </div>
        </div>
        <span v-if="rating"><p>A teszt kiértékelése folyamatban.</p></span>
        <!-- Eredmény megjelenítése -->
        <h3 v-if="submitted" class="result-title">
          Eredmény: <span class="score">{{ scorePercent }}%</span>
        </h3>

        <!-- Teszt beküldése vagy Bezárás gomb -->
        <div class="button-group">
          <button
            class="btn btn-success w-100 mb-2"
            @click="submitTestAnswers"
            v-if="!submitted"
          >
            Teszt Beküldése
          </button>
          <button
            class="btn btn-secondary w-100 mb-3"
            @click="closeTest"
            v-else
          >
            Bezárás
          </button>
        </div>
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
      testQuestionIds: [],
      testQuestions: [],
      submitted: false,
      generating: false,
      rating: false,
      scorePercent: null,
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
        const response = await axios.get(this.urlApiUserTest, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.userTests = response.data.data.filter(
          (userTest) => userTest.userId === this.store.id
        );
      } catch (error) {
        console.error("Hiba a kérdéstípusok lekérésekor:", error);
        alert("Kérdéstípusok betöltése sikertelen.");
      }
    },

    async createUserTest() {
      this.generating = true;
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
        this.generating = false;
        await this.fetchUserTests(); // Tesztlista frissítése
      } catch (error) {
        console.error("Nem sikerült a teszt létrehozása:", error);
      }

      this.state = "Read";
    },

    async generateTestQuestions(userTestId) {
      try {
        // Lekérdezzük az összes kérdést (kérdés + válaszok)
        const questionsResponse = await axios.get(`${BASE_URL}/questions`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        const allQuestions = questionsResponse.data.data;

        // Véletlenszerűen kiválasztunk 10 kérdést
        const selectedQuestions = this.getRandomElements(allQuestions, 10);

        // Előkészítjük a küldendő adatokat
        const testQuestions = selectedQuestions.map((question) => ({
          userTestId: userTestId,
          questionId: question.id,
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
      this.submitted = false;
      this.testQuestions = [];
      this.testQuestionIds = [];
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
          this.testQuestionIds.push(testQuestionsAll[i].id);
        }
      }
      // console.log(questionIds);
      // console.log(this.testQuestionIds);

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
      this.testQuestions.forEach((question) => {
        question.selectedAnswerId = null;
      });

      console.log(this.testQuestions);
    },

    async loadTest(userTestId) {
      this.submitted = true;
      this.testQuestions = [];
      this.testQuestionIds = [];
      this.currentUserTestId = userTestId;

      try {
        const response = await axios.get(
          `${BASE_URL}/userTests/${userTestId}`,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );

        const userTest = response.data.data;
        this.scorePercent = userTest.score;

        const testQuestionsresponse = await axios.get(
          `${BASE_URL}/testQuestions`,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );
        var testQuestionsAll = testQuestionsresponse.data.data;
      } catch (error) {
        console.error("Nem sikerült betölteni a kérdéseket:", error);
      }

      var questionIds = [];
      var selectedAnswersByQuestionId = {}; // új objektum

      for (let i = 0; i < testQuestionsAll.length; i++) {
        if (testQuestionsAll[i].userTestId == this.currentUserTestId) {
          questionIds.push(testQuestionsAll[i].questionId);
          this.testQuestionIds.push(testQuestionsAll[i].id);

          // Itt megjegyezzük, hogy a kérdéshez milyen answerId tartozott
          selectedAnswersByQuestionId[testQuestionsAll[i].questionId] =
            testQuestionsAll[i].answerId;
        }
      }

      for (const questionId of questionIds) {
        try {
          const response = await axios.get(
            `${BASE_URL}/getQuestionsWithTypesAndAnswers/${questionId}`,
            {
              headers: { Authorization: `Bearer ${this.store.token}` },
            }
          );
          const questionData = response.data.data[0];

          // Itt beállítjuk a korábban választott answerId-t
          questionData.selectedAnswerId =
            selectedAnswersByQuestionId[questionId];

          this.testQuestions.push(questionData);
        } catch (error) {
          console.error("Nem sikerült betölteni a kérdést:", error);
        }
      }

      console.log(this.testQuestions);
    },

    async submitTestAnswers() {
      this.rating = true;
      const token = this.store.token;

      try {
        for (let i = 0; i < this.testQuestions.length; i++) {
          const selectedAnswerId = this.testQuestions[i].selectedAnswerId;
          const testQuestionId = this.testQuestionIds[i];

          if (selectedAnswerId) {
            const data = { answerId: selectedAnswerId };

            await axios.patch(
              `${BASE_URL}/testQuestions/${testQuestionId}`,
              data,
              {
                headers: {
                  Accept: "application/json",
                  "Content-Type": "application/json",
                  Authorization: `Bearer ${token}`,
                },
              }
            );
          } else {
            alert(`Nem jelöltél meg választ az ${i + 1}. kérdésre!`);
            this.rating = false;
            return;
          }
        }

        this.submitted = true;
        this.rating = false;

        let correctAnswers = 0;
        let totalQuestions = this.testQuestions.length;

        // Végigmegyünk a kérdéseken
        this.testQuestions.forEach((question) => {
          const selectedAnswer = question.answers.find(
            (answer) => answer.answerId === question.selectedAnswerId
          );
          if (selectedAnswer && selectedAnswer.rightAnswer) {
            correctAnswers++;
          }
        });

        this.scorePercent = Math.round((correctAnswers / totalQuestions) * 100);

        const url = `${this.urlApiUserTest}/${this.currentUserTestId}`;
        const headers = {
          Accept: "application/json",
          "Content-Type": "application/json",
          Authorization: `Bearer ${this.store.token}`,
        };

        const data = {
          score: this.scorePercent,
        };
        try {
          const response = await axios.patch(url, data, { headers });
          this.fetchUserTests();
        } catch (error) {
          console.error("Nem sikerült a teszt frissítése:", error);
        }
        // Feltételezem, hogy a userTests[0] az aktuális teszt
        if (this.userTests.length > 0) {
          this.userTests[0].score = this.scorePercent;
        }

        console.log("Eredmény:", this.scorePercent + "%");
        this.fetchUserTests();
      } catch (error) {
        console.error("Hiba a válaszok beküldésekor:", error);
        // alert("Hiba történt a beküldéskor.");
      }
    },

    closeTest() {
      this.testQuestions = [];
      this.currentUserTestId = null;
    },

    getAnswerClass(question, answer) {
      if (!this.submitted) {
        return ""; // ha még nincs beküldve, semmit nem színezünk
      }

      if (answer.rightAnswer) {
        return "correct";
      }

      if (
        answer.answerId === question.selectedAnswerId &&
        !answer.rightAnswer
      ) {
        return "incorrect";
      }

      return "";
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

/* .correct {
  color: green;
  font-weight: bold;
}

.incorrect {
  color: red;
  font-weight: bold;
} */

.user-table {
  table-layout: auto; /* Automatikus oszlop szélesség */
}

/* Görgetősáv beállítása a táblázathoz és kérdőívhez */
.user-table-container {
  max-height: 600px; /* Maximális magasság beállítása */
  overflow-y: auto; /* Vertikális görgetősáv megjelenítése, ha a tartalom meghaladja a magasságot */
}

.test-questions-container {
  max-height: 600px; /* Maximális magasság a kérdésekhez */
  overflow-y: auto; /* Görgetősáv, ha a kérdések száma meghaladja a maximális magasságot */
  margin-top: 20px;
  margin-bottom: 40px;
}

/* Mobil nézetekhez */
@media (max-width: 768px) {
  .user-table-container,
  .test-questions-container {
    max-height: 400px; /* Mobilon kisebb magasság */
  }
}

/* .test-questions-container {
 
} */

.test-card {
  background-color: #f9f9f9; /* világos háttér */
  border: 1px solid #ddd; /* világos szegély */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* finom árnyék */
  transition: transform 0.2s, box-shadow 0.2s;
}

.test-card:hover {
  transform: translateY(-5px); /* finom emelés hover effekt */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* erősebb árnyék hover effekt */
}

.question-title {
  font-weight: bold;
  font-size: 1.1rem;
  color: #333;
}

.answer-option {
  margin-bottom: 12px;
}

.answer-label {
  font-size: 1rem;
  color: #555;
}

.answer-label.correct {
  color: green;
  font-weight: bold;
}

.answer-label.incorrect {
  color: red;
  font-weight: bold;
}

.result-title {
  font-size: 1.2rem;
  margin-top: 20px;
  font-weight: bold;
  color: #333;
}

.score {
  color: #28a745; /* Eredmény zöld */
}

.button-group {
  margin-top: 20px;
}

button {
  font-size: 1rem;
  padding: 10px;
}

button:focus {
  outline: none;
  box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}
</style>
