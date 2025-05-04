<template>
  <div>
    <div class="my-container row">
      <div class="admin-container col-12 col-md-12 col-xl-12 col-xxl-5">
        <!-- Rögzített új kérdés gomb -->
        <h2 class="title">Kérdések kezelése</h2>
        <div class="justify-content-end">
          <h6>Témakör: {{ selectedCategoryName }}</h6>
          <OperationsCrudQuestionsAnswers
            style="text-align: right"
            class="mb-2 me-2"
            @onClickCreateButton="onClickCreateButton"
          />
        </div>
        <!-- <button class="create-question-btn" @click="onClickCreateButton">Új kérdés létrehozása</button> -->

        <!-- Táblázat Wrapper, hogy a görgetés csak itt történjen -->
        <div class="table-wrapper table-responsive">
          <table class="table table-hover user-table mb-5">
            <thead>
              <tr class="sticky-top">
                <th scope="col">Kérdés</th>
                <th scope="col">Típus</th>
                <th scope="col">Válaszok</th>
                <th scope="col">+</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="questionAnswer in filteredQuestions"
                :key="questionAnswer.questionId"
              >
                <td>{{ questionAnswer.question }}</td>
                <td>{{ questionAnswer.questionCategory }}</td>
                <td>
                  <div
                    v-for="answer in questionAnswer.answers"
                    :key="answer.answerId"
                  >
                    <i
                      v-if="answer.rightAnswer === true"
                      class="bi bi-check-lg right-answer-icon"
                    ></i>
                    - {{ answer.answer }}
                  </div>
                </td>
                <td>
                  <OperationsCrudQuestionsAnswers
                    :questionAnswer="questionAnswer"
                    @onClickDeleteButton="onClickDeleteButton"
                    @onClickUpdateButton="onClickUpdateButton"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-12 category-container col-md-4 col-xxl-4 ms-5">
        <h2 class="title">Témakörök</h2>
        <!-- Témakörök -->
        <table class="table table-hover user-table">
          <thead>
            <!-- <tr>
              <th scope="col">Témakörök</th>
            </tr> -->
          </thead>
          <tbody>
            <tr
              class="my-cursor"
              v-for="category in categories"
              :key="category.id"
              @click="selectCategory(category.id, category.category)"
              :class="{ 'table-danger': selectedCategoryId === category.id }"
            >
              <td>{{ category.category }}</td>
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

      <QuestionsAnswersForm
        v-if="state === 'Create' || state === 'Update'"
        :key="formKey"
        :is-create="isCreate"
        :formData="questionAnswers"
        :categories="categories"
        :questionTypes="questionTypes"
        :selectedCategoryId="selectedCategoryId"
        @saveItem="saveItemHandler"
        @addAnswer="addAnswerHandler"
        @saveField="saveField"
        @removeAnswer="deleteAnswerById"
      />
    </Modal>
  </div>
</template>


<script>
class Question {
  constructor(
    question = null,
    id = null,
    questionTypeId = null,
    categoryId = null
  ) {
    //Itt nem kell létrehozni a változót
    //JS nem erősen típusos
    //Dekaráció pongyola: nem is kell, csak leírjuk
    this.id = id;
    this.question = question;
    this.questionTypeId = questionTypeId;
    this.categoryId = categoryId;
  }
}

class Answer {
  constructor(id = null, answer = null, questionId = null, rightAnswer = null) {
    //Itt nem kell létrehozni a változót
    //JS nem erősen típusos
    //Dekaráció pongyola: nem is kell, csak leírjuk
    this.id = id;
    this.answer = answer;
    this.questionId = questionId;
    this.rightAnswer = rightAnswer;
  }
}

class QuestionType {
  constructor(id = null, questionCategory = null) {
    this.id = id;
    this.questionCategory = questionCategory;
  }
}
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import QuestionsAnswersForm from "@/components/Forms/QuestionsAnswersForm.vue";
import QuestionTypesForm from "@/components/Forms/QuestionTypesForm.vue";
import OperationsCrudQuestionsAnswers from "@/components/Modals/OperationsCrudQuestionsAnswers.vue";
import OperationsCrudQuestionTypes from "@/components/Modals/OperationsCrudQuestionTypes.vue";
import * as bootstrap from "bootstrap";

export default {
  components: {
    QuestionsAnswersForm,
    QuestionTypesForm,
    OperationsCrudQuestionsAnswers,
    OperationsCrudQuestionTypes,
  },
  data() {
    return {
      store: useAuthStore(),
      questionsAnswers: [],
      questionAnswers: [],
      categories: [],
      questionTypes: [],
      isCreate: true,
      selectedCategoryId: null,
      selectedCategoryName: null,
      messageYesNo: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      question: new Question(),
      answer: new Answer(),
      formKey: 0,
    };
  },
  computed: {
    // Szűrt kérdések
    filteredQuestions() {
      if (this.selectedCategoryId) {
        return this.questionsAnswers.filter(
          (question) => question.categoryId === this.selectedCategoryId
        );
      }
      return this.questionsAnswers;
    },
  },
  methods: {
    async fetchQuestionsAnswers() {
      try {
        const response = await axios.get(
          `${BASE_URL}/getQuestionsWithTypesAndAnswers`,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );

        this.questionsAnswers = response.data.data.map((category) => ({
          ...category,
        }));
      } catch (error) {
        console.error("Hiba a kérdések és válaszok lekérésekor:", error);
        alert("A kérdések és válaszok betöltése sikertelen.");
      }
    },

    async fetchQuestionsAnswersById(id) {
      try {
        const response = await axios.get(
          `${BASE_URL}/getQuestionsWithTypesAndAnswers/${id}`,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );

        this.questionAnswers = response.data.data[0];
        this.formKey++; // kulcs növelése, hogy újrarenderelődjön
        console.log("Adatok: ", this.questionAnswers);
      } catch (error) {
        console.error("Hiba a kérdések és válaszok lekérésekor:", error);
        alert("A kérdések és válaszok betöltése sikertelen.");
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

    async fetchQuestionTypes() {
      try {
        const response = await axios.get(`${BASE_URL}/questionTypes`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        this.questionTypes = response.data.data.map((questionType) => ({
          ...questionType,
        }));
      } catch (error) {
        console.error("Hiba a kérdéstípusok lekérésekor:", error);
        alert("Kérdéstípusok betöltése sikertelen.");
      }
    },

    // Kategória kiválasztás
    selectCategory(categoryId, categoryName) {
      this.selectedCategoryId = categoryId;
      this.selectedCategoryName = categoryName;
    },

    saveItemHandler(formData) {
      if (this.state === "Create") {
        this.createQuestion(formData);
        this.modal.hide();
      } else if (this.state === "Update") {
        this.updateQuestion(formData);
      }
    },

    async createQuestion(formData) {
      try {
        const response = await axios.post(`${BASE_URL}/questions`, formData, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        console.log("Új kérdés mentése sikeres:", response);
        this.fetchQuestionsAnswers();
        // this.fetchQuestionsAnswersById(formData.questionid);
        this.state = "Read";
      } catch (error) {
        console.error("Hiba történt a kérdés mentésekor:", error);
      }
    },

    async updateQuestion(formData) {
      try {
        const response = await axios.patch(
          `${BASE_URL}/questions/${this.selectedRowId}`,
          formData,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );
        console.log("Kérdés frissítése sikeres:", response);
        this.fetchQuestionsAnswers();
        this.fetchQuestionsAnswersById(formData.questionId);
        // this.state = "Read";
      } catch (error) {
        console.error("Hiba történt a kérdés frissítésekor:", error);
      }
    },

    async addAnswerHandler() {
      if (!this.questionAnswers || !this.questionAnswers.questionId) {
        console.error("Nincs érvényes kérdés!");
        return;
      }

      const newAnswer = {
        answer: "Új válasz", // alapértelmezett válasz szöveg
        rightAnswer: 0, // alapértelmezett helyes válasz
        questionId: this.questionAnswers.questionId, // a kérdés ID-ja
      };

      try {
        // POST kérés küldése a válasz hozzáadásához
        const response = await axios.post(`${BASE_URL}/answers`, newAnswer, {
          headers: {
            Authorization: `Bearer ${this.store.token}`,
          },
        });
        this.questionAnswers.answers.push(response.data);
        this.fetchQuestionsAnswers();
        this.fetchQuestionsAnswersById(this.questionAnswers.questionId);
        console.log("Új válasz mentve:", response.data);
      } catch (error) {
        console.error("Hiba történt a válasz mentésekor:", error);
      }
    },

    async saveField(questionAnswers, index) {
      // console.log(`Mentés az index-nél: ${index}`);
      console.log(questionAnswers);
      console.log(index);
      try {
        const response = await axios.patch(
          `${BASE_URL}/answers/${index}`,
          questionAnswers,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );
        console.log("válasz frissítése sikeres:", response);

        this.fetchQuestionsAnswers();
        this.fetchQuestionsAnswersById(questionAnswers.questionId);
        // this.state = "Read";
      } catch (error) {
        console.error("Hiba történt a kérdés frissítésekor:", error);
      }
    },

    async deleteAnswerById(answer, answerId) {
      console.log(answerId);
      try {
        const id = answerId;
        const response = await axios.delete(`${BASE_URL}/answers/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        // A sikeres törlés után frissíteni kell a válaszok listáját
        this.fetchQuestionsAnswers();
        this.fetchQuestionsAnswersById(answer.questionId);

        // alert("A kategória sikeresen törölve!");
      } catch (error) {
        console.error("Törlés hiba:", error);
        alert("A válasz törlése nem sikerült!");
      }
    },
    async deleteQuestionById() {
      try {
        const id = this.selectedRowId;
        const response = await axios.delete(`${BASE_URL}/questions/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        // A sikeres törlés után frissíteni kell a kérdések listáját
        this.fetchQuestionsAnswers();
      } catch (error) {
        console.error("Törlés hiba:", error);
        alert("A kérdés törlése nem sikerült!");
      }
    },

    yesEventHandler() {
      if (this.state == "Delete") {
        this.deleteQuestionById();
        this.modal.hide(); // A modal bezárása a törlés után
      }
    },

    onClickDeleteButton(questionAnswer) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${questionAnswer.question} nevű kérdést?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = questionAnswer.questionId;
      // console.log(this.selectedRowId);
    },

    async onClickUpdateButton(questionAnswer) {
      this.state = "Update";
      this.title = "Kérdés módosítása";
      this.isCreate = false;
      this.yes = null;
      this.no = "Bezár";
      this.size = "lg";
      this.selectedRowId = questionAnswer.questionId;
      await this.fetchQuestionsAnswersById(this.selectedRowId); // FONTOS
    },

    onClickCreateButton() {
      this.isCreate = true;
      this.state = "Create";
      this.title = "Új kérdés bevitele";
      this.yes = null;
      this.no = "Bezár";
      this.size = "lg";
      this.question = new Question();
      this.answer = new Answer();
      this.questionAnswers = {
        questionId: null,
        question: "", // Alapértelmezett értékek
        categoryId: this.selectedCategoryId || null, // Ha van kiválasztott kategória, az lesz az alapértelmezett
        questionTypeId: null,
        answers: [],
      };
    },
  },

  mounted() {
    this.fetchQuestionsAnswers();
    this.fetchCategories();
    this.fetchQuestionTypes();
    this.modal = new bootstrap.Modal("#modal", {
      keyboard: false,
    });
  },
};
</script>

<style scoped>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-attachment: fixed;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0;
  padding: 0;
}

.admin-container {
  /* height: calc(100vh - 200px); */
  max-height: 600px;
  max-width: 1000px;
  background: rgba(255, 248, 220, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border: 2px solid #8b5a2b;
  margin-top: 100px;
  margin-bottom: 200px;
  overflow: hidden;
  /* Elrejti a görgetést az admin-containeren kívül */
  position: relative;
  /* A gomb pozicionálásához szükséges */
}

.table-wrapper {
  max-height: 500px;
  /* Maximális magasság a táblázathoz */
  overflow-y: scroll;
  /* Görgetés csak a táblázat számára */
}

@media (max-width: 767.98px) {
  .table-wrapper {
    max-height: 500px; /* tetszőleges, vagy hagyd ahogy volt */
    overflow-y: scroll; /* mindig jelenítse meg a függőleges scroll-t */
    -webkit-overflow-scrolling: touch; /* natív görgetés iOS-en */
  }
}

.user-table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255, 248, 220, 0.9);
}

.user-table th,
.user-table td {
  padding: 10px;
  border: 2px solid #8b5a2b;
  text-align: center;
}

.user-table th {
  background-color: #8b5a2b;
  color: white;
}

.right-answer-icon {
  margin-right: 5px;
  color: green;
}

.category-container {
  max-height: 600px;
  max-width: 1000px;
  background: rgba(255, 248, 220, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border: 2px solid #8b5a2b;
  margin-top: 100px;
  margin-bottom: 200px;
  overflow: auto;
  margin-right: 10px;
}

.my-cursor {
  cursor: pointer;
}

h2 {
  text-align: center;
}
</style>