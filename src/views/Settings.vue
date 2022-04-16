<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-center" mt-10>
        Přihlášený uživatel:
        <h2>{{ getUser.email }}</h2>
      </v-flex>
      <v-flex xs12 sm4 offset-sm4 mt-3>
        <h3 class="mt-10">Aktualizovat osobní údaje</h3>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout column>
            <v-flex>
              <v-text-field
                v-model="email"
                name="email"
                label="E-mail"
                type="email"
                required
                :rules="emailRules"
                :placeholder="email"
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="name"
                name="name"
                label="Jméno"
                type="text"
                :placeholder="name"
              >
              </v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="surname"
                surname="surname"
                label="Příjmení"
                type="text"
                :value="surname"
              >
              </v-text-field>
            </v-flex>
            <v-flex class="text-center" mt-5>
              <v-btn :disabled="!valid" color="primary" @click="updateUser">
                Aktualizovat údaje
              </v-btn>
              <v-btn
                class="ml-5 white--text"
                color="red"
                @click="dialog = true"
              >
                Smazat účet
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-flex>
    </v-layout>
    <v-dialog v-model="dialog" width="300">
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Smazání účtu
        </v-card-title>
        <v-card-text class="pt-3">
          Určitě chcete Váš účet smazat? Přijdete tím o veškerá uložená data.
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" text @click="deleteUser"
            >Smazat trvale účet</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar
      top
      v-model="snackbar"
      :color="snackbarColor"
      :timeout="snackbarTimeout"
    >
      {{ snackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
export default {
  name: "Settings",
  data: () => ({
    valid: true,
    email: "",
    emailRules: [
      (v) => !!v || "E-mail je požadován",
      (v) => /.+@.+/.test(v) || "E-mail musí být ve správném formátu",
    ],
    name: "",
    surname: "",
    snackbar: false,
    snackbarText: "",
    snackbarColor: "red darken-2",
    snackbarTimeout: 10000,
    dialog: false,
  }),
  created() {
    this.$store.dispatch("getUser");
    this.readUser();
  },
  computed: {
    getUser() {
      return this.$store.state.user;
    },
  },
  methods: {
    readUser() {
      this.axios
        .post("https://ampeditor.dev/app/user.php", {
          request: "readUserData",
        })
        .then((response) => {
          this.email = response.data[1];
          this.name = response.data[3];
          this.surname = response.data[4];
        });
    },
    updateUser() {
      if (this.$refs.form.validate()) {
        this.axios
          .post(
            "https://ampeditor.dev/app/user.php",
            {
              request: "updateUser",
              email: this.email,
              name: this.name,
              surname: this.surname,
            },
            { withCredentials: true }
          )
          .then((data) => {
            if (data.data === 1) {
              this.snackbarText = "Uživatelské údaje úspěšně aktualizovány.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
            } else {
              this.snackbarText = "Vámi zadaný email už někdo používá.";
              this.snackbarColor = "red darken-2";
              this.snackbar = true;
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    deleteUser() {
      this.axios
        .post("https://ampeditor.dev/app/user.php", {
          request: "deleteUser",
        })
        .then(() => {
          alert("Váš účet byl smazán!");
          window.location.href = "/";
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped></style>
