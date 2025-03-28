<template>
  <form
    @submit.prevent="onClickSubmit"
    class="row g-3 needs-validation was-validated"
  >
    <p v-if="debug">{{ itemForm }}</p>
    <div class="col-md-4 position-relative">
      <label for="category" class="form-label">A témakör neve:</label>
      <input
        type="text"
        class="form-control"
        id="category"
        required
        v-model="itemForm.category"
      />
    </div>

    <div class="mb-3">
      <label for="Level" class="form-label">Szint</label>
      <select
        v-model="itemForm.level"
        id="Level"
        class="form-select"
        required
      >
        <option value="közép">Közép</option>
        <option value="emelt">Emelt</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Mentés</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      newTopic: { category: "", level: "", text: "" },
    };
  },
  methods: {
    handleSubmit() {
      this.$emit("addTopic", { ...this.newTopic });
      this.newTopic = { category: "", level: "", text: "" };
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
</style>
