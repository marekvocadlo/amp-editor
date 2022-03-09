<template>
  <v-app>
    <!--Menu-->
    <v-app-bar app dark absolute elevate-on-scroll color="primary">
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
          <v-list-item class="mx-2">Homepage</v-list-item>
        </router-link>
        <router-link to="/editor">
          <v-list-item class="mx-2">Editor</v-list-item>
        </router-link>
        <router-link to="/templates">
          <v-list-item class="mx-2">Templates</v-list-item>
        </router-link>
        <router-link to="/contacts">
          <v-list-item class="mx-2">Contacts</v-list-item>
        </router-link>
        <router-link to="/login">
          <v-list-item class="mx-2">Přihlášení</v-list-item>
        </router-link>
      </div>
      <v-spacer></v-spacer>
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

  data: () => ({
    //
  }),
};
</script>

<style>
.v-application {
  font-family: Poppins, sans-serif !important;
}
</style>
