<template>
  <form
    @submit.prevent="onClickSubmit"
    class="row g-3 needs-validation was-validated"
  >
    <!-- <p v-if="debug">{{ itemForm }}</p> -->
    <div class=" mb-3">
      <label for="testName" class="form-label">Mi legyen a neve?</label>
      <input
        type="text"
        class="form-control"
        id="testName"
        required
        v-model="itemForm.testName"
      />
    </div>

    <!-- Témakör kiválasztása -->
    <div class="mb-3">
      <label for="category" class="form-label">Téma:</label>
      <select
        id="category"
        class="form-select"
        required
        v-model="itemForm.categoryId"
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

    <button type="submit" class="btn btn-success">Mentés</button>
  </form>
</template>

<script>
export default {
  props: ["itemForm", "categories"],
  emits: ["saveItem"],
  methods: {
    onClickSubmit() {
      this.$emit("saveItem", this.itemForm);
    },
  },
};
</script>


<style scoped>
.card {
  transition: all 0.3s ease;
}
.card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.needs-validation {
  margin-top: 20px;
}

.form-label {
  font-weight: bold;
}

.mb-3 {
  margin-bottom: 15px;
}
</style>
