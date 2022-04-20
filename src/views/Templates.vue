<template>
  <div v-if="getUser.email" class="templates">
    <h1>This is templates page</h1>
  </div>
</template>

<script>
export default {
  name: "Templates",
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
