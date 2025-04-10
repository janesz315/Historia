<template>
  <div class=" my-container">
    <div class="col-10 col-md-8 col-xxl-3 category-container">
      <h2 class="title">Kérdéstípusok</h2>
      <OperationsCrudQuestionTypes
        style="text-align: right"
        class="mb-2 me-2"
        @onClickCreateButton="onClickCreateQuestionTypeButton"
      />
      <!-- Témakörök -->
      <table class="table table-hover user-table">
        <thead>
          <tr>
            <th scope="col">Kérdéstípusok</th>
            <th scope="col">+</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="my-cursor"
            v-for="questionType in questionTypes"
            :key="questionType.id"
            @click="selectQuestionType(questionType.id)"
            :class="{
              'table-danger': selectedQuestionTypeId === questionType.id,
            }"
          >
            <td>{{ questionType.questionCategory }}</td>

            <td style="width: 50px">
              <OperationsCrudQuestionTypes
                :questionType="questionType"
                @onClickDeleteButton="onClickDeleteQuestionTypeButton"
                @onClickUpdateButton="onClickUpdateQuestionTypeButton"
              />
            </td>
          </tr>
        </tbody>
      </table>
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

      <QuestionTypesForm
        v-if="state === 'Create' || state === 'Update'"
        :itemForm="questionType"
        @saveItem="saveQuestionTypeHandler"
      />
    </Modal>
  </div>
</template>

<script>
class QuestionType {
  constructor(id = null, questionCategory = null) {
    this.id = id;
    this.questionCategory = questionCategory;
  }
}
import axios from "axios";
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "../stores/useAuthStore";
import QuestionTypesForm from "@/components/Forms/QuestionTypesForm.vue";
import OperationsCrudQuestionTypes from "@/components/Modals/OperationsCrudQuestionTypes.vue";
import * as bootstrap from "bootstrap";
export default {
  components: { QuestionTypesForm, OperationsCrudQuestionTypes },
  data() {
    return {
      store: useAuthStore(),
      urlApiQuestionType: `${BASE_URL}/questionTypes`,
      questionTypes: [],
      selectedQuestionTypeId: null,
      messageYesNo: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      questionType: new QuestionType(),
    };
  },
  mounted() {
    this.fetchQuestionTypes();
    this.modal = new bootstrap.Modal("#modal", {
      keyboard: false,
    });
  },
  methods: {

     selectQuestionType(questionTypeId) {
      this.selectedQuestionTypeId = questionTypeId;
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

    async createQuestionType() {
      const token = this.store.token;
      const url = this.urlApiQuestionType;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      const data = {
        questionCategory: this.questionType.questionCategory,
      };
      try {
        const response = await axios.post(url, data, { headers });
        this.fetchQuestionTypes();
        // alert("A kérdéstípus sikeresen létrehozva!");
      } catch (error) {
        console.error("Hiba történt a kérdéstípus mentésekor:", error);
      }
      this.state = "Read";

    },

    async updateQuestionType() {
      this.loading = true;
      const id = this.selectedRowId;
      const url = `${this.urlApiQuestionType}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${this.store.token}`,
      };

      const data = {
        questionCategory: this.questionType.questionCategory,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.fetchQuestionTypes();
        // alert("A kérdéstípus sikeresen módosítva!");
      } catch (error) {
        console.error("Hiba történt a kérdéstípus frissítésekor:", error);
      }
      this.state = "Read";

    },

    async deleteQuestionTypeById() {
      try {
        const id = this.selectedRowId;
        const response = await axios.delete(`${BASE_URL}/questionTypes/${id}`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });

        // A sikeres törlés után frissíteni kell a kérdések listáját
        this.fetchQuestionTypes();

      } catch (error) {
        console.error("Törlés hiba:", error);
        alert("A kérdés törlése nem sikerült!");
      }
    },

    onClickDeleteQuestionTypeButton(questionType) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${questionType.questionCategory} nevű kérdéstípust?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
      this.selectedRowId = questionType.id;
      // console.log(this.selectedRowId);
    },

    onClickUpdateQuestionTypeButton(questionType) {
      this.state = "Update";
      this.title = "Kérdéstípus módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.questionType = { ...questionType };
      this.selectedRowId = questionType.id;
      // console.log(this.selectedRowId);
    },

    onClickCreateQuestionTypeButton() {
      this.state = "Create";
      this.title = "Új kérdéstípus bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.questionType = new QuestionType();
    },

    saveQuestionTypeHandler() {
      if (this.state === "Create") {
        this.createQuestionType();
      } else if (this.state === "Update") {
        this.updateQuestionType();
      }

      this.modal.hide();
    },

    yesEventHandler() {
      if (this.state == "Delete") {
        this.deleteQuestionTypeById();
        this.modal.hide(); // A modal bezárása a törlés után
      }
    },

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
  overflow-y: auto;
  /* Görgetés csak a táblázat számára */
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