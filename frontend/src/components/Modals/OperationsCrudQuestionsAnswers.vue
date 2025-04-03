<template>
  <div>
    <!-- Csak a törlés és módosítás gombok a kártya címénél -->
    <div v-if="questionAnswer" class="d-flex flex-column">
      <button
        type="button"
        class="btn btn-outline-danger d-block mb-2"
        data-bs-toggle="modal"
        data-bs-target="#modal"
        @click="onClickDeleteButton"
      >
        <i class="bi bi-trash3"></i>
      </button>
      <button
        type="button"
        class="btn btn-outline-primary d-block"
        data-bs-toggle="modal"
        data-bs-target="#modal"
        @click="onClickUpdateButton"
      >
        <i class="bi bi-pencil"></i>
      </button>
    </div>

    <!-- Csak a "Create" gomb az oldal tetején -->
    <div v-else>
      <button
        type="button"
        class="btn btn-outline-success"
        data-bs-toggle="modal"
        data-bs-target="#modal"
        @click="onClickCreateButton"
      >
        <i class="bi bi-plus-lg"></i> Új kérdés létrehozása
      </button>
    </div>
  </div>
</template>
<script>
export default {
  props: { questionAnswer: Object }, // Ezt már jól adod át
  emits: ["onClickDeleteButton", "onClickUpdateButton", "onClickCreateButton"],
  methods: {
    onClickDeleteButton() {
      this.$emit("onClickDeleteButton", this.questionAnswer);
    },
    onClickUpdateButton() {
      this.$emit("onClickUpdateButton", this.questionAnswer);
    },
    onClickCreateButton() {
      this.$emit("onClickCreateButton");
    },
  },
};
</script>
