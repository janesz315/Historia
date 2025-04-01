<template>
  <div>
    <div class="my-container">
      <div class="category col-3 category-container">
        <h2 class="title">Témakörök</h2>
        <table class="table table-hover user-table">
          <thead>
            <!-- <tr>
              <th scope="col">Témakörök</th>
            </tr> -->
          </thead>
          <tbody>
            <tr
              v-for="category in categories"
              :key="category.id"
              @click="selectCategory(category.id)"
              :class="{'selected-category': selectedCategoryId === category.id,}"
            >
              <td>{{ category.category }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="admin-container col-9">
        <h2 class="title">Kérdések kezelése</h2>
        <table class="table table-hover user-table">
          <thead>
            <tr>
              <th scope="col">Kérdés</th>
              <th scope="col">Típus</th>
              <th scope="col">Válaszok</th>
              <th scope="col">Műveletek</th>
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
                    v-if="answer.rightAnswer === 1"
                    class="bi bi-check-lg right-answer-icon"
                  ></i>
                  {{ answer.answer }}
                </div>
              </td>
              <td>
                <OperationsCrudQuestionsAnswers
                  :questionAnswer="questionAnswer"
                  @onClickDeleteButton="onClickDeleteButton"
                  @onClickUpdateButton="onClickUpdateButton"
                  @onClickCreateButton="onClickCreateButton"
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

      <QuestionsAnswersForm
        v-if="state == 'Create' || state == 'Update'"
        :formData="questionAnswer"
        :categories="categories"
        :questionTypes="questionTypes"
        @saveItem="saveItemHandler"
      />
    </Modal>
  </div>
</template>
  
  <script>
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import QuestionsAnswersForm from "@/components/Forms/QuestionsAnswersForm.vue";
import OperationsCrudQuestionsAnswers from "@/components/Modals/OperationsCrudQuestionsAnswers.vue";

export default {
  components: { QuestionsAnswersForm, OperationsCrudQuestionsAnswers },
  data() {
    return {
      store: useAuthStore(),
      questionsAnswers: [],
      categories: [],
      questionTypes:[],
      selectedCategoryId: null,
      messageYesNo: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
    };
  },
  computed:{
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
    
    async fetchQuestionTypes(){
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
    selectCategory(categoryId) {
      this.selectedCategoryId = categoryId;
    },

    

    onClickDeleteButton(questionAnswer) {
      // if (!category || !category.id) {
      //   console.error("A kategória nem található.");
      //   alert("Hiba: A kategória nem található.");
      //   return;
      // }
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${questionAnswer.question} nevű kérdést?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = questionAnswer.id;
    },
    onClickUpdateButton(questionAnswer) {
      this.state = "Update";
      this.title = "Kérdés módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.questionAnswer = { ...questionAnswer }; // Beállítjuk a category-t, nem item
      this.selectedRowId = questionAnswer.id;
    },

    onClickCreateButton() {
      this.title = "Új kérdés bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.state = "Create";
      this.questionAnswer = new QuestionAnswer();
    },
  },
  mounted() {
    this.fetchQuestionsAnswers();
    this.fetchCategories();
    this.fetchQuestionTypes();
  },
};
</script>
  
<style scoped>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  /* background-position: center; */
  background-attachment: fixed;
  /* height: 100vh; */
  /* width: 100vw; */
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0;
  padding: 0;
}

.admin-container {
  max-height: 600px;
  max-width: 1000px;
  background: rgba(255, 248, 220, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border: 2px solid #8b5a2b;
  margin-top: 100px;
  margin-bottom: 200px;
  /* transform: translateY(-10%); */
}

.title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  color: #5a3e1b;
  text-align: center;
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
  /* color: #5a3e1b; */
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

.selected-category {
  background-color: #b8a618 !important;   /* Világos sárga háttér a kiválasztott kategóriához */
  cursor: pointer;
}

.selected-category:hover {
  background-color: #d63215;   /* Kattintásra sötétebb árnyalat */
}
</style>