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
        <router-link to="/templates">
          <v-list-item class="mx-2">Obsahy e-mailu</v-list-item>
        </router-link>
        <router-link to="/contacts">
          <v-list-item class="mx-2">Kontakty</v-list-item>
        </router-link>
        <router-link to="/campaigns">
          <v-list-item class="mx-2">Kampaně</v-list-item>
        </router-link>
        <router-link to="/help">
          <v-list-item class="mx-2">Návod</v-list-item>
        </router-link>
        <router-link v-if="getUser.admin === '1'" to="/users">
          <v-list-item class="mx-2">Správa uživatelů</v-list-item>
        </router-link>
      </div>
      <v-spacer></v-spacer>
      <div class="d-flex align-center">
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-avatar color="white" size="35" v-bind="attrs" v-on="on">
              <v-icon color="#2763b0">{{ icons.mdiAccount }}</v-icon>
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
    <v-footer padless>
      <v-col class="text-center" cols="12">
        Copyright © {{ new Date().getFullYear() }} — <strong>AMP editor</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
import { mdiAccount } from "@mdi/js";
export default {
  name: "App",
  data: () => ({
    icons: {
      mdiAccount,
    },
  }),
  components: {},
  methods: {
    logout() {
      this.axios
        .post("/app/user.php", {
          action: "logout",
        })
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
.v-application a {
  color: #ffffff !important;
  text-decoration: none !important;
}
.v-main {
  background: linear-gradient(45deg, #fff, #eee);
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
.v-avatar svg {
  width: 25px !important;
}
</style>
