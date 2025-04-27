<template>
  <form
    @submit.prevent="onClickSubmit"
    class="row g-3 needs-validation was-validated"
  >
    <!-- <p v-if="debug">{{ itemForm }}</p> -->
    <div class="mb-3">
      <label for="sourceLink" class="form-label">Forrás link</label>
      <input
        type="text"
        class="form-control"
        id="sourceLink"
        required
        v-model="itemForm.sourceLink"
      />
    </div>

    <div class="col-md-12 position-relative">
      <label for="note" class="form-label">Megjegyzés</label>
      <input
        type="text"
        class="form-control"
        id="note"
        required
        v-model="itemForm.note"
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