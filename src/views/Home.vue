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
    <div v-if="getUser.email" class="mt-10">
      <h1>Dashboard</h1>
      <v-row no-gutters class="justify-center">
        <!--
        <DashboardCard
          img="help.jpg"
          title="Jak začít?"
          text="Rozeslat AMP email je s touto aplikací hračka. Zjistěte jak na to."
          btnText="Zjistit více"
          btnHref="/help"
        />
        -->
        <DashboardCard
          img="editor.png"
          title="Tvorba e-mailů"
          text="Vytvořte jedinečný interaktivní obsah optimalizovaný pro všechny klienty."
          btnText="Zobrazit obsahy"
          btnHref="/templates"
        />
        <DashboardCard
          img="contacts.jpg"
          title="Kontakty"
          text="Začněte vytvořením skupiny kontaktů nebo nahráním e-mailových adres."
          btnText="Zobrazit kontakty"
          btnHref="/contacts"
        />
        <DashboardCard
          img="campaign.jpg"
          title="Kampaně"
          text="Vymyslete poutavý předmět a pošlete kampaň všem kontaktům."
          btnText="Vytvořit kampaň"
          btnHref="/campaigns"
        />
      </v-row>
    </div>
  </div>
</template>

<script>
import DashboardCard from "../components/DashboardCard";
export default {
  name: "Home",
  components: {
    DashboardCard,
  },
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
