<template>
  <div class="form-container">
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label for="question">Kérdés:</label>
        <input type="text" id="question" v-model="formData.question" required />
      </div>
      <div class="form-group">
        <label for="questionTypeId">Kérdés típus ID:</label>
        <input
          type="number"
          id="questionTypeId"
          v-model="formData.questionTypeId"
          required
        />
      </div>
      <div class="form-group">
        <label for="categoryId">Kategória ID:</label>
        <input
          type="number"
          id="categoryId"
          v-model="formData.categoryId"
          required
        />
      </div>

      <div class="form-group">
        <label for="answer">Válasz:</label>
        <input type="text" id="answer" v-model="formData.answer" required />
      </div>
      <div class="form-group">
        <label for="questionId">Kérdés ID:</label>
        <input
          type="number"
          id="questionId"
          v-model="formData.questionId"
          required
        />
      </div>
      <div class="form-group">
        <label for="rightAnswer">Helyes válasz:</label>
        <select id="rightAnswer" v-model="formData.rightAnswer" required>
          <option value="0">Nem</option>
          <option value="1">Igen</option>
        </select>
      </div>

      <button type="submit">Mentés</button>
    </form>
  </div>
</template>
  
  <script>
import axios from "axios";

export default {
    props: ["formData"],
  emits: ["saveItem"],
  methods: {
    onClickSubmit() {
      this.$emit("saveItem", this.itemForm);
    },
  },
};
</script>
  
  <style scoped>
.form-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input,
select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  background-color: #4caf50;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>