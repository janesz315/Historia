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

      <div class="col-4">
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
    </div>

    <!-- Kategória választás -->
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
        >
          {{ category.category }}
        </option>
      </select>
    </div>

    <button
      type="submit"
      class="btn btn-success mb-2"
      :disabled="
        !formData.question || !formData.categoryId || !formData.questionTypeId
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
        <button
        type="button"
        class="btn btn-danger btn-sm"
        @click="removeAnswer(index)"
      >
      <i class="bi bi-trash3"></i>
      </button>
      </div>
    </div>

    <!-- Új válasz hozzáadás gomb -->
    <div class="mb-3">
      <button type="button" class="btn btn-secondary" @click="addAnswer" :disabled="
        !formData.question || !formData.categoryId || !formData.questionTypeId
      ">
        + Válasz hozzáadása
      </button>
    </div>
  </form>
</template>

<script>
export default {
  props: ["categories", "formData", "questionTypes", "isCreate"],
  emits: ["saveItem", "resetForm", "addAnswer"],

  data() {
    return {
    };
  },

  methods: {
    addAnswer() {
      this.$emit("addAnswer");
      // this.formData.answers.push({
      //   answerId: Date.now(),
      //   answer: "",
      //   rightAnswer: 0,
      // });
    },

    removeAnswer(index) {
      this.formData.answers.splice(index, 1);  // Eltávolítja a választ a tömbből
    },

    resetForm() {
      this.$emit("resetForm");
    },

    onClickSubmit() {
      // Ellenőrizzük, hogy van-e kérdés és válaszok
      if (!this.formData.question || !this.formData.categoryId || !this.formData.questionTypeId) {
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

/* .form-select {
  max-width: 300px;
  word-wrap: break-word;
  white-space: normal;
} */
</style>
