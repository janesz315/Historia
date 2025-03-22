<template>
  <div class="modal fade" :id="modalId" tabindex="-1" aria-hidden="true" ref="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ category.category }}</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <!-- 🔹 v-model-rel kötjük a QuillEditorhoz -->
          <QuillEditor v-model="tempText" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Mégse</button>
          <button type="button" class="btn btn-primary" @click="saveChanges">Mentés</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watch, onMounted } from "vue";
import QuillEditor from "@/components/Editor/QuillEditor.vue";
import { Modal } from "bootstrap";

export default {
  components: { QuillEditor },
  props: {
    category: Object,
    saveCategory: Function,
  },
  setup(props) {
    const tempText = ref(""); // Ideiglenes változó a szöveg szerkesztéséhez
    const modal = ref(null);
    let modalInstance = null;
    const modalId = `editModal-${props.category.id}`;

    onMounted(() => {
      modalInstance = new Modal(modal.value);
    });

    // Ha változik az eredeti kategória szövege, frissítsük a szerkesztő tartalmát
    watch(
      () => props.category.text,
      (newText) => {
        tempText.value = newText;
      },
      { immediate: true }
    );

    const saveChanges = async () => {
      props.category.text = tempText.value; // 🔹 Frissítjük a kategória szövegét
      await props.saveCategory(props.category); // 🔹 Elmentjük az adatbázisba
      modalInstance.hide(); // 🔹 Bezárjuk a modalt
    };

    const closeModal = () => {
      tempText.value = props.category.text; // 🔹 Visszaállítjuk az eredeti értéket
      modalInstance.hide();
    };

    const openModal = () => {
      tempText.value = props.category.text; // 🔹 Szerkesztő inicializálás
      modalInstance.show();
    };

    return { tempText, saveChanges, closeModal, openModal, modalId, modal };
  },
};
</script>
