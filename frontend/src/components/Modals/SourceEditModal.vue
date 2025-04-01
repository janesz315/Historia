<template>
  <form
    @submit.prevent="onClickSubmit"
    class="modal fade show d-block needs-validation was-validated"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Forrás szerkesztése</h5>
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
              required
            />
          </div>
          <div class="mb-3">
            <label for="note" class="form-label">Megjegyzés:</label>
            <textarea
              id="note"
              v-model="localSource.note"
              class="form-control"
              required
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
          <button type="submit" class="btn btn-success">Mentés</button>
        </div>
      </div>
    </div>
  </form>
</template>
  
  <script>
export default {
  props: ["localSource"],
  emits: ["saveItem", "close"],
  data() {
    return {
      localSource: { ...this.localSource }, // Klónozva a prop, hogy ne módosítsa közvetlenül az eredetit
    };
  },
  methods: {
    onClickSubmit() {
      console.log("Mentés indítása:", this.localSource); // Debugging, hogy lásd az adatokat
      this.$emit("saveItem", this.localSource); // Esemény küldése a szülő komponensnek
    },
  },
};
</script>
  