<template>
  <div class="text-center">
    <h1>Editor</h1>
    <div class="container">
      <iframe
        :src="`https://ampeditor.dev/files/amp.html`"
        width="100%"
        height="100"
        frameborder="0"
      >
      </iframe>
    </div>
  </div>
</template>

<script>
export default {
  name: "Editor",
  data: () => ({
    templateAMP: "",
    templateHTML: "",
  }),
  created() {
    this.displayTemplate();
  },
  methods: {
    displayTemplate() {
      this.axios
        .post("https://ampeditor.dev/template.php", {
          request: 2,
        })
        .then((response) => {
          console.log(response.data);
          this.templateHTML = response.data[2];
          this.templateAMP = response.data[3];
        });
    },
  },
};
</script>

<style scoped></style>
