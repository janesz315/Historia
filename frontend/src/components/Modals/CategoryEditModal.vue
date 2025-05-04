<template>
  <div
    class="modal fade"
    :id="modalId"
    tabindex="-1"
    aria-hidden="true"
    ref="modal"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ category.category }}</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <QuillEditor v-model="tempText" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">
            Mégse
          </button>
          <button type="button" class="btn btn-primary" @click="saveChanges">
            Mentés
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import QuillEditor from "@/components/Editor/QuillEditor.vue";
import { Modal } from "bootstrap";

export default {
  components: { QuillEditor },
  props: {
    category: Object,
    saveCategory: Function, // A mentési függvény
  },
  data() {
    return {
      tempText: "", // Ideiglenes szöveg a szerkesztőhöz
      modalInstance: null, // Bootstrap modal objektum
    };
  },
  computed: {
    modalId() {
      return `editModal-${this.category.id}`;
    },
  },
  watch: {
    // Ha a kategória szövege változik, frissítsük a tempText-et
    "category.text": {
      handler(newText) {
        this.tempText = newText;
      },
      immediate: true,
    },
  },
  mounted() {
    this.modalInstance = new Modal(this.$refs.modal);
  },
  methods: {
    openModal() {
      this.tempText = this.category.text; // Szöveg beállítása a szerkesztőbe
      this.modalInstance.show();
    },
    closeModal() {
      this.tempText = this.category.text; // Visszaállítás az eredeti értékre
      this.modalInstance.hide();
    },
    async saveChanges() {
      this.category.text = this.tempText; // Frissítés a kategória objektumban
      await this.saveCategory(this.category); // Küldés a szerverre
      this.modalInstance.hide();
    },
  },
};
</script>
