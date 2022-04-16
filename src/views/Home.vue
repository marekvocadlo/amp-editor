<template>
  <div class="home text-center">
    <div v-if="!getUser.email" class="text-center">
      <v-img
        alt="AMP editor Logo"
        class="mt-10 mx-auto"
        contain
        :src="require('@/assets/amp-icon-color.svg')"
        transition="scale-transition"
        width="80"
      />
      <h1 class="mt-3 mb-10">AMP editor</h1>
      <v-btn to="/login" color="primary" class="hp-button mr-4"
        >Přihlásit se</v-btn
      >
      <v-btn to="/register" color="success" class="hp-button mr-4"
        >Registrace</v-btn
      >
    </div>
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
