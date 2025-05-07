<template>
  <div>
    <div ref="editor" class="editor"></div>
  </div>
</template>

<script>
import { onMounted, ref, watch } from "vue";
import Quill from "quill";
import "quill/dist/quill.snow.css";

export default {
  props: {
    modelValue: String, // 🔹 A v-model értékét fogadja
  },
  emits: ["update:modelValue"], // 🔹 Kibocsátja a frissített értéket
  data() {
    return {
      editor: null, // Quill példány tárolása
      htmlContent: "", // HTML formátumú tartalom tárolása
      htmlViewVisible: false, // HTML nézet megjelenítése
    };
  },
  mounted() {
    this.editor = new Quill(this.$refs.editor, {
      theme: "snow",
      modules: {
        toolbar: [
          [{ header: "1" }, { header: "2" }, { font: [] }],
          [{ list: "ordered" }, { list: "bullet" }],
          ["bold", "italic", "underline"],
          [{ align: [] }],
          ["link"],
          ["image"],
          [{ color: [] }, { background: [] }],
        ],
      },
    });

    // Beállítjuk a kezdeti tartalmat
    this.editor.root.innerHTML = this.modelValue || "";

    // Figyeljük a szerkesztőt és frissítjük a v-model értékét
    this.editor.on("text-change", () => {
      this.$emit("update:modelValue", this.editor.root.innerHTML);
    });
  },

  watch: {
    modelValue(newValue) {
      if (this.editor && newValue !== this.editor.root.innerHTML) {
        this.editor.root.innerHTML = newValue;
      }
    },
  },
};
</script>

<style scoped>
.editor {
  height: 300px;
}
</style>
