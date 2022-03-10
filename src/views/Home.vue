<template>
  <div class="home text-center">
    <h1 class="mt-10 mb-5">AMP editor</h1>
    <v-btn to="/help" color="primary" class="hp-button">Jak začít?</v-btn>
    <h2 class="mt-10 mb-5">Příprava kampaně</h2>
    <v-btn to="/templates" color="success" class="hp-button mr-4"
      >Vytvořit AMP email</v-btn
    >
    <v-btn to="/contacts" color="success" class="hp-button mr-4"
      >Přidat kontakty</v-btn
    >
  </div>
</template>

<script>
export default {
  name: "Home",
  components: {},
};
</script>

<style scoped></style>
