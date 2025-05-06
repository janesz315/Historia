<template>
  <div>
    <!-- Működik, csak nem szép -->
    <!-- <input v-model="searchText" placeholder="Keresés..." />
    <input v-model="replaceText" placeholder="Csere..." />
    <button @click="findAndReplace">Keresés és csere</button> -->
    <div ref="editor" class="editor"></div>

    <!-- Gomb a HTML nézet megjelenítéséhez -->
    <!-- <button @click="showHtmlView">HTML nézet</button> -->

    <!-- HTML nézet megjelenítése -->
    <!-- <div v-if="htmlViewVisible" class="html-view">
      <h3>HTML nézet</h3>
      <pre>{{ htmlContent }}</pre>
      <button @click="closeHtmlView">Bezárás</button>
    </div> -->
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
      // searchText: "",
      // replaceText: "",
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
      // placeholder: "Írj valamit...",
    });

    // 🔹 Beállítjuk a kezdeti tartalmat
    this.editor.root.innerHTML = this.modelValue || "";

    // 🔹 Figyeljük a szerkesztőt és frissítjük a v-model értékét
    this.editor.on("text-change", () => {
      this.$emit("update:modelValue", this.editor.root.innerHTML);
    });
  },

  methods: {
    // undo() {
    //   this.editor.history.undo();
    // },
    // redo() {
    //   this.editor.history.redo();
    // },
    //Működik ez
    // findAndReplace() {
    //   let content = this.editor.root.innerHTML;
    //   const regex = new RegExp(this.searchText, "g");
    //   content = content.replace(regex, this.replaceText);
    //   this.editor.root.innerHTML = content;
    // },
    // showHtmlView() {
    //   // A Quill editor HTML tartalmát beállítjuk
    //   this.htmlContent = this.editor.root.innerHTML;
    //   this.htmlViewVisible = true; // Megjelenítjük a HTML nézetet
    // },
    // closeHtmlView() {
    //   this.htmlViewVisible = false; // Bezárjuk a HTML nézetet
    // },
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
