<template>
    <div>
      <!-- class="d-inline d-flex justify-content-between align-items-center" -->
      <!-- Csak a törlés és módosítás gombok a kártya címénél -->
      <div v-if="source">
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
          <i class="bi bi-plus-lg"></i> Forrás hozzáadása
        </button>
      </div>
    </div>
  </template>
  <script>
  export default {
    props: { source: Object }, // Ezt már jól adod át
    emits: ["onClickDeleteButton", "onClickUpdateButton", "onClickCreateButton"],
    methods: {
      onClickDeleteButton() {
        this.$emit("onClickDeleteButton", this.source);
      },
      onClickUpdateButton() {
        this.$emit("onClickUpdateButton", this.source);
      },
      onClickCreateButton() {
        this.$emit("onClickCreateButton");
      },
    },
  };
  </script>

<style> 
</style>
  