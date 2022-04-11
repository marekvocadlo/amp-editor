<template>
  <div class="home text-center">
    <h1 v-if="!getUser.email" class="mt-10 mb-5">AMP editor</h1>
    <v-btn
      v-if="!getUser.email"
      to="/login"
      color="success"
      class="hp-button mr-4"
      >Přihlásit se</v-btn
    >
    <v-btn
      v-if="!getUser.email"
      to="/register"
      color="success"
      class="hp-button mr-4"
      >Registrace</v-btn
    >
    <div v-if="getUser.email">
      <h1>Vítejte {{ getUser.name }} {{ getUser.surname }}</h1>
    </div>
  </div>
</template>

<script>
export default {
  name: "Home",
  components: {},
  data: () => ({}),
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
