<template>
  <div class="home">
    <h1 class="text-center">AMP editor will be ready soon</h1>
    <router-link to="/editor" class="text-decoration-none">
      <v-btn class="hp-button mr-4">Create new email</v-btn>
    </router-link>
    <v-btn class="hp-button mr-4">Edit last email</v-btn>
    <v-btn class="hp-button">Send emails</v-btn>
  </div>
</template>

<script>
export default {
  name: "Home",
  components: {},
};
</script>

<style scoped></style>
