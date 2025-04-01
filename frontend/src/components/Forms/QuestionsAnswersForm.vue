<template>
  <form @submit.prevent="onClickSubmit" class="needs-validation" novalidate>
    <!-- Kérdés -->
    <div class="mb-3">
      <label for="question" class="form-label">Kérdés:</label>
      <input
        type="text"
        id="question"
        class="form-control"
        v-model="formData.question"
        required
      />
    </div>

    <!-- Kategória választás -->
    <div class="mb-3">
      <label for="category" class="form-label">Téma:</label>
      <select
        id="category"
        class="form-select"
        v-model="formData.categoryId"
        required
      >
        <option disabled value="">Válassz témakört!</option>
        <option
          v-for="category in categories"
          :key="category.id"
          :value="category.id"
        >
          {{ category.category }}
        </option>
      </select>
    </div>

    <div class="mb-3">
      <label for="questionCategory" class="form-label">Típus:</label>
      <select
        id="category"
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

    <button
      type="submit"
      class="btn btn-success"
      @click="onClickSaveQuestionButton"
      :disabled="
        !formData.question || !formData.categoryId || !questionTypeId
      "
    >
      Mentés
    </button>

    <!-- Válaszok -->
    <div
      v-for="(answer, index) in formData.answers"
      :key="answer.answerId"
      class="mb-3"
    >
      <label class="form-label">Válasz {{ index + 1 }}:</label>
      <div class="input-group">
        <input
          type="text"
          class="form-control"
          v-model="answer.answer"
          required
        />
        <div class="input-group-text">
          <input type="checkbox" v-model="answer.rightAnswer" />
        </div>
      </div>
    </div>

    <!-- Új válasz hozzáadás gomb -->
    <div class="mb-3">
      <button type="button" class="btn btn-secondary" @click="addAnswer">
        + Válasz hozzáadása
      </button>
    </div>

    <!-- Mentés és törlés -->
    <div class="mb-3">
      <button
        type="submit"
        class="btn btn-success"
        :disabled="
          !formData.question || !formData.categoryId || !formData.answers.length
        "
      >
        Mentés
      </button>
      <button type="button" class="btn btn-danger" @click="resetForm">
        Törlés
      </button>
    </div>

    <!-- Végleges mentés -->
    <div class="mb-3">
      <button
        type="button"
        class="btn btn-primary"
        @click="submitAll"
        :disabled="
          !formData.question ||
          !formData.categoryId ||
          !formData.answers.some((answer) => answer.rightAnswer)
        "
      >
        Véglegesítés
      </button>
    </div>
  </form>
</template>

<script>
export default {
  props: ["categories", "existingData", "questionTypes"],
  emits: ["saveItem", "resetForm"],

  data() {
    return {
      formData: {
        question: this.existingData?.question || "",
        categoryId: this.existingData?.categoryId || null,
        answers: this.existingData?.answers || [
          { answerId: 1, answer: "", rightAnswer: 0 },
        ],
      },
    };
  },

  methods: {
    addAnswer() {
      this.formData.answers.push({
        answerId: Date.now(),
        answer: "",
        rightAnswer: 0,
      });
    },

    resetForm() {
      this.$emit("resetForm");
    },

    onClickSubmit() {
      // Ellenőrizzük, hogy van-e kérdés és válaszok
      if (
        !this.formData.question ||
        !this.formData.categoryId ||
        !this.formData.answers.length
      ) {
        alert("Kérlek, töltsd ki az összes mezőt!");
        return;
      }
      this.$emit("saveItem", this.formData);
    },

    submitAll() {
      if (
        !this.formData.question ||
        !this.formData.categoryId ||
        !this.formData.answers.some((a) => a.rightAnswer === 1)
      ) {
        alert("Legalább egy válasznak helyesnek kell lennie!");
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
