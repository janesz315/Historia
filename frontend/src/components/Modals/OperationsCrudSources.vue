<template>
    <div>
      <!-- Csak a törlés és módosítás gombok a kártya címénél -->
      <div v-if="category" class="d-inline d-flex justify-content-between align-items-center" >
        <button
          type="button"
          class="btn btn-outline-danger"
          data-bs-toggle="modal"
          data-bs-target="#modal"
          @click="onClickDeleteButton"
        >
          <i class="bi bi-trash3"></i>
        </button>
        <button
          type="button"
          class="btn btn-outline-primary ms-2"
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
          <i class="bi bi-plus-lg"></i> Új forrás létrehozása
        </button>
      </div>
    </div>
  </template>
  <script>
  export default {
    props: { category: Object }, // Ezt már jól adod át
    emits: ["onClickDeleteButton", "onClickUpdateButton", "onClickCreateButton"],
    methods: {
      onClickDeleteButton() {
        this.$emit("onClickDeleteButton", this.category);
      },
      onClickUpdateButton() {
        this.$emit("onClickUpdateButton", this.category);
      },
      onClickCreateButton() {
        this.$emit("onClickCreateButton");
      },
    },
  };
  </script>
  