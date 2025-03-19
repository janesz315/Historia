<template>
  <div ref="editor" class="editor"></div>
</template>

<script>
import { onMounted, ref } from "vue";
import Quill from "quill";
import "quill/dist/quill.snow.css";  // CSS importálása

export default {
  setup() {
    const editor = ref(null);

    onMounted(() => {
      new Quill(editor.value, {
        theme: "snow",
      });
    });

    return {
      editor,
    };
  },
};
</script>

<style scoped>
.editor {
  height: 300px;
}
</style>