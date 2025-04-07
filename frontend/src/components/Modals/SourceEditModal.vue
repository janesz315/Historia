<template>
  <form
    @submit.prevent="onClickSubmit"
    class="modal fade show d-block needs-validation was-validated"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Forrás hozzáadása</h5>
          <button
            type="button"
            class="btn-close"
            @click="$emit('close')"
          ></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="sourceLink" class="form-label">Forrás URL:</label>
            <input
              type="text"
              id="sourceLink"
              class="form-control"
              v-model="localSource.sourceLink"
            />
          </div>
          <div class="mb-3">
            <label for="note" class="form-label">Megjegyzés:</label>
            <textarea
              id="note"
              v-model="localSource.note"
              class="form-control"
            ></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            @click="$emit('close')"
          >
            Mégse
          </button>
          <button @click="onClickSubmit" type="submit" class="btn btn-success">
            Mentés
          </button>
        </div>
      </div>
    </div>
  </form>
</template>
  
  <script>
export default {
  props: ["localSource"],
  emits: ["saveItem", "close"],
  methods: {
    onClickSubmit() {
      console.log("Mentés gombra kattintottam!");
      console.log("Küldött adat:", this.localSource);

      if (!this.localSource || typeof this.localSource !== "object") {
        console.error(
          "Hiba: A küldött adat nem létezik vagy érvénytelen!",
          this.localSource
        );
        return;
      }

      this.$emit("saveItem", { ...this.localSource }); // Esemény kibocsátása
      console.log("saveItem esemény elküldve!");
    },
  },
};
</script>
  