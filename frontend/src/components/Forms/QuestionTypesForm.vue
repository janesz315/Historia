<template>
  <form
    @submit.prevent="onClickSubmit"
    class="row g-3 needs-validation was-validated"
  >
    <!-- <p v-if="debug">{{ itemForm }}</p> -->
    <div class="mb-3">
      <label for="questionCategory" class="form-label"
        >A kérdéstípus neve:</label
      >
      <input
        type="text"
        class="form-control"
        id="questionCategory"
        required
        v-model="itemForm.questionCategory"
      />
    </div>

    <button type="submit" class="btn btn-success">Mentés</button>
  </form>
</template>

<script>
export default {
  props: ["itemForm"],
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
</style>
