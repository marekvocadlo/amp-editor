<template>
  <v-app>
    <!--Menu-->
    <v-app-bar
      v-if="getUser.name"
      app
      dark
      absolute
      elevate-on-scroll
      color="primary"
    >
      <div class="d-flex align-center">
        <router-link to="/">
          <v-img
            alt="AMP editor Logo"
            class="shrink mr-2"
            contain
            :src="require('@/assets/logo-white.svg')"
            transition="scale-transition"
            width="120"
          />
        </router-link>
        <v-spacer></v-spacer>
        <router-link to="/">
          <v-list-item class="mx-2">Domů</v-list-item>
        </router-link>
        <!--        <router-link to="/editor">-->
        <!--          <v-list-item class="mx-2">Editor</v-list-item>-->
        <!--        </router-link>-->
        <router-link to="/templates">
          <v-list-item class="mx-2">Šablony</v-list-item>
        </router-link>
        <router-link to="/contacts">
          <v-list-item class="mx-2">Kontakty</v-list-item>
        </router-link>
        <router-link to="/help">
          <v-list-item class="mx-2">Návod</v-list-item>
        </router-link>
        <!--        <router-link to="/login">-->
        <!--          <v-list-item class="mx-2">Přihlášení</v-list-item>-->
        <!--        </router-link>-->
      </div>
      <v-spacer></v-spacer>
      <router-link to="/settings">
        <v-list-item class="mx-2">Nastavení</v-list-item>
      </router-link>
      <v-btn @click="logout" class="mx-2">Odhlásit se</v-btn>
      <div class="d-flex align-center">
        <v-avatar color="white" size="35">
          <span class="text--primary">MV</span>
        </v-avatar>
      </div>
    </v-app-bar>
    <!--Dynamic content-->
    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "App",
  components: {},
  methods: {
    logout() {
      this.axios
        .post("https://ampeditor.dev/script/logout.php")
        .then(() => {
          window.location.href = "/";
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
  },
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

<style>
.v-application {
  font-family: Poppins, sans-serif !important;
}
</style>
