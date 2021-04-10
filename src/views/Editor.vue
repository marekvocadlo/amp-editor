<template>
  <div class="templates">
    <div class="container">
      <div id="bar">
        <h1>Vue Email Editor</h1>

        <v-btn v-on:click="saveDesign" depressed color="primary mr-2">
          Save Design
        </v-btn>
        <v-btn v-on:click="exportHtml" depressed class="secondary">
          Export HTML
        </v-btn>
      </div>

      <EmailEditor
        ref="emailEditor"
        v-on:load="editorLoaded"
        :min-height="minHeight"
        :locale="locale"
        class="full-screen"
      />
    </div>
  </div>
</template>

<script>
import { EmailEditor } from "vue-email-editor";

export default {
  name: "Editor",
  components: {
    EmailEditor,
  },
  data() {
    return {
      minHeight: "500px",
      locale: "en",
    };
  },
  methods: {
    editorLoaded() {
      // Pass the template JSON here
      // this.$refs.emailEditor.editor.loadDesign({});
    },
    saveDesign() {
      this.$refs.emailEditor.editor.saveDesign((design) => {
        console.log("saveDesign", design);
      });
    },
    exportHtml() {
      this.$refs.emailEditor.editor.exportHtml((data) => {
        console.log("exportHtml", data);
      });
    },
  },
};
</script>

<style scoped>
.full-screen {
  height: calc(100vh - 200px);
}
</style>
