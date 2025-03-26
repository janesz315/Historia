<template>
  <div
    class="modal fade"
    id="topicModal"
    tabindex="-1"
    aria-labelledby="topicModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="topicModalLabel">
            Új témakör hozzáadása
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Bezárás"
          ></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="handleSubmit">
            <div class="mb-3">
              <label for="categoryName" class="form-label">Témakör neve</label>
              <input
                v-model="newTopic.category"
                type="text"
                id="categoryName"
                class="form-control"
                required
              />
            </div>

            <div class="mb-3">
              <label for="topicLevel" class="form-label">Szint</label>
              <select
                v-model="newTopic.level"
                id="topicLevel"
                class="form-select"
                required
              >
                <option value="közép">Közép</option>
                <option value="emelt">Emelt</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="topicDescription" class="form-label"
                >Leírás (opcionális)</label
              >
              <textarea
                v-model="newTopic.text"
                id="topicDescription"
                class="form-control"
              ></textarea>
            </div>

            <button type="submit" class="btn btn-success">Mentés</button>
          </form>
        </div>
      </div>
    </div>
  </div>
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
