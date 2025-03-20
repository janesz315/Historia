<template>
  <div ref="editor" class="editor"></div>
</template>

<script>
import { onMounted, ref, watch } from "vue";
import Quill from "quill";
import "quill/dist/quill.snow.css";

export default {
  props: {
    value: String,
  },
  setup(props, { emit }) {
    const editor = ref(null);
    let quill = null;

    onMounted(() => {
      quill = new Quill(editor.value, {
        theme: "snow",
      });

      quill.on('text-change', () => {
        emit('update:value', quill.root.innerHTML);
      });

      // Inicializáljuk a Quill-t a props.value értékkel
      if (props.value) {
        quill.root.innerHTML = props.value;
      }
    });

    // Ha a props.value változik, frissítjük a Quill-t
    watch(() => props.value, (newValue) => {
      if (quill && quill.root.innerHTML !== newValue) {
        quill.root.innerHTML = newValue;
      }
    });

    return {
      editor,
      quill,
    };
  },
};
</script>

<style scoped>
.editor {
  height: 300px;
}
</style>
