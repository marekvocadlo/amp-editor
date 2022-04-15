<template>
  <v-app>
    <!--Menu-->
    <v-app-bar
      v-if="getUser.email"
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
        <router-link to="/editor">
          <v-list-item class="mx-2">Editor</v-list-item>
        </router-link>
        <router-link to="/templates">
          <v-list-item class="mx-2">Šablony</v-list-item>
        </router-link>
        <router-link to="/contacts">
          <v-list-item class="mx-2">Kontakty</v-list-item>
        </router-link>
        <router-link to="/campaign">
          <v-list-item class="mx-2">Rozeslání</v-list-item>
        </router-link>
        <router-link to="/help">
          <v-list-item class="mx-2">Návod</v-list-item>
        </router-link>
      </div>
      <v-spacer></v-spacer>
      <div class="d-flex align-center">
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-avatar color="white" size="35" v-bind="attrs" v-on="on">
              <span class="text--primary">{{ getUser.initials }}</span>
            </v-avatar>
          </template>
          <v-list>
            <v-list-item>
              <router-link to="/settings">
                <v-list-item-title class="avatar-dropdown"
                  >Nastavení</v-list-item-title
                >
              </router-link>
            </v-list-item>
            <v-list-item>
              <v-list-item-title @click="logout" class="avatar-dropdown"
                >Odhlásit se</v-list-item-title
              >
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-app-bar>
    <!--Dynamic content-->
    <v-main>
      <v-container fluid class="pa-0">
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
        .post("https://ampeditor.dev/logout.php")
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
.v-list-item,
.v-list-item a {
  text-decoration: none;
  cursor: pointer;
  color: rgba(0, 0, 0, 0.87) !important;
}
.v-list-item:hover,
.v-list-item a:hover {
  text-decoration: underline !important;
}
.v-avatar span {
  text-transform: uppercase;
}
</style>
