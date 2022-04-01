<template>
  <div class="home text-center">
    <h1 v-if="!getUser" class="mt-10 mb-5">AMP editor</h1>
    <v-btn v-if="!getUser" to="/login" color="success" class="hp-button mr-4"
      >Přihlásit se</v-btn
    >
    <v-btn v-if="!getUser" to="/register" color="success" class="hp-button mr-4"
      >Registrace</v-btn
    >
  </div>
</template>

<script>
export default {
  name: "Home",
  components: {},
  created() {
    this.$store.dispatch("getUser");
  },
  computed: {
    getUser() {
      return this.$store.state.user;
    },
  },
};
</script>

<style scoped></style>
