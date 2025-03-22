<template>
  <div ref="editor" class="editor"></div>
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
    };
  },
  mounted() {
    this.editor = new Quill(this.$refs.editor, {
      theme: "snow",
      // placeholder: "Írj valamit...",
    });

    // 🔹 Beállítjuk a kezdeti tartalmat
    this.editor.root.innerHTML = this.modelValue || "";

    // 🔹 Figyeljük a szerkesztőt és frissítjük a v-model értékét
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
//   data() {
//     return {
//       quill: null,
//       editorContent: ''
//     };
//   },
//   mounted() {
//     this.quill = new Quill(this.$refs.editor, {
//       theme: 'snow',
//       // placeholder: 'Írj valamit...'
//     });
//       this.quill.root.innerHTML = this.editorContent
//   },
//   methods: {
//     saveContent() {
//       this.editorContent = this.quill.root.innerHTML;
//       console.log("Mentett tartalom:", this.editorContent);
//     }
//   }
// };
</script>


<style scoped>
.editor {
  height: 300px;
}
</style>
