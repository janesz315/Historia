<template>
  <form @submit.prevent="onClickSubmit" class="needs-validation" novalidate>
    <!-- Kérdés -->

    <div class="d-flex mb-3">
      <div class="me-2 col-8">
        <label for="question" class="form-label">Kérdés:</label>
        <input
          type="text"
          id="question"
          class="form-control"
          v-model="formData.question"
          required
        />
      </div>
      <!-- Kérdéstípus -->
      <div class="col-4">
        <label for="questionType" class="form-label">Típus:</label>
        <select
          id="questionType"
          class="form-select"
          v-model="formData.questionTypeId"
          required
        >
          <option disabled value="">Válassz típust</option>
          <option
            v-for="questionType in questionTypes"
            :key="questionType.id"
            :value="questionType.id"
          >
            {{ questionType.questionCategory }}
          </option>
        </select>
      </div>
    </div>

    <!-- Témakör kiválasztása -->
    <div class="mb-3">
      <label for="category" class="form-label">Téma:</label>
      <select
        id="category"
        class="form-select"
        v-model="formData.categoryId"
        required
        style="max-height: 200px; overflow-y: auto"
      >
        <option disabled value="">Válassz témakört!</option>
        <option
          v-for="category in categories"
          :key="category.id"
          :value="category.id"
          :selected="category.id === selectedCategoryId"
        >
          {{ category.category }}
        </option>
      </select>
    </div>
    <!-- A kérdés létrehozása vagy frissítése -->
    <button
      type="submit"
      class="btn btn-success mb-2"
      v-show="
        formData.question && formData.categoryId && formData.questionTypeId
      "
    >
      {{ isCreate ? "Mentés" : "Frissítés" }}
    </button>

    <!-- Válaszok -->
    <div
      v-for="(answer, index) in formData.answers"
      :key="answer.answerId"
      class="mb-3"
    >
      <label class="form-label">Válasz {{ index + 1 }}: </label>
      <div class="input-group">
        <!-- Checkbox alaphelyzetben -->
        <div
          class="input-group-text"
          v-show="!editing || isEditingField !== answer.answerId"
        >
          <input type="checkbox" v-model="answer.rightAnswer" disabled />
        </div>
        <!-- A válasz alaphelyzetben -->
        <p class="d-flex align-items-center mb-0">
          {{ isEditingField === answer.answerId ? "" : answer.answer }}
        </p>
        <!-- A módosítő mező -->
        <div
          v-if="isEditingField === answer.answerId && editing"
          class="d-flex align-items-center"
        >
          <!-- Checkbox -->
          <div class="input-group-text me-2">
            <input type="checkbox" v-model="answer.rightAnswer" />
          </div>
          <input
            type="text"
            class="form-control me-2"
            style="max-width: 500px"
            v-model="answer.answer"
            placeholder="Írj be egy választ!"
          />
          <!-- Mentés gomb -->
          <button
            class="btn btn-outline-success me-2"
            @click="saveField(answer, answer.answerId)"
          >
            <i class="bi bi-save"></i>
          </button>
          <!-- Szerkesztőből kilépés -->
          <button class="btn btn-outline-info" @click="cancelEdit">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <!-- Módosítás gomb -->
        <button
          v-else
          class="btn btn-outline-warning"
          @click="startEdit(answer.answerId, answer.answer)"
        >
          <i class="bi bi-pencil"></i>
        </button>
        <!-- Válasz törlése -->
        <button
          type="button"
          class="btn btn-outline-danger btn-sm"
          @click="removeAnswer(answer, answer.answerId)"
        >
          <i class="bi bi-trash3"></i>
        </button>
      </div>
    </div>

    <!-- Új válasz hozzáadás gomb -->
    <div class="mb-3">
      <button
        type="button"
        class="btn btn-secondary"
        @click="addAnswer"
        v-show="!isCreate"
        :disabled="
          !formData.question || !formData.categoryId || !formData.questionTypeId
        "
      >
        + Válasz hozzáadása
      </button>
    </div>
  </form>
</template>

<script>
export default {
  props: [
    "categories",
    "formData",
    "questionTypes",
    "isCreate",
    "selectedCategoryId",
  ],
  emits: ["saveItem", "resetForm", "addAnswer", "saveField", "removeAnswer"],

  data() {
    return {
      updatedField: {},
      isEditingField: null,
      editing: false,
      rightAnswerTempValue: false,
    };
  },

  methods: {
    addAnswer() {
      this.$emit("addAnswer");
    },

    removeAnswer(answer, answerId) {
      this.$emit("removeAnswer", answer, answerId);
    },

    resetForm() {
      this.$emit("resetForm");
    },
    saveField(answer, answerId) {
      this.rightAnswerId = answer.rightAnswer;
      if (answer.rightAnswer === false) {
        answer.rightAnswer = 0;
      } else {
        answer.rightAnswer = 1;
      }
      this.$emit("saveField", answer, answerId);
      this.editing = false;
      answer.rightAnswer = this.rightAnswerId;
    },

    startEdit(answerId, answer) {
      this.isEditingField = answerId; // Az éppen szerkesztett válasz ID-ja
      this.updatedField.index = this.formData.answers.findIndex(
        (a) => a.answerId === answerId
      ); // Index alapján
      this.updatedField.answer = answer;
      this.editing = true;
    },

    cancelEdit() {
      this.editing = false;
      this.isEditingField = null;
      this.updatedField = {};
    },

    onClickSubmit() {
      // Ellenőrizzük, hogy van-e kérdés és válaszok
      if (
        !this.formData.question ||
        !this.formData.categoryId ||
        !this.formData.questionTypeId
      ) {
        alert("Kérlek, töltsd ki az összes mezőt!");
        return;
      }
      this.$emit("saveItem", this.formData);
    },
  },
};
</script>

<style scoped>
.needs-validation {
  margin-top: 20px;
}

.form-label {
  font-weight: bold;
}

.mb-3 {
  margin-bottom: 15px;
}

.input-group {
  display: flex;
  gap: 10px;
}

.input-group-text {
  display: flex;
  align-items: center;
}

input[type="text"]:invalid {
  border-color: red;
}

input[type="text"]:valid {
  border-color: green;
}
</style>
