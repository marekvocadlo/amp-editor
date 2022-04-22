<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-center" mt-10>
        <h1>Přihlásit se</h1>
      </v-flex>
      <v-flex xs12 sm2 offset-sm5 mt-5>
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
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="password"
                name="password"
                label="Heslo"
                type="password"
                required
                :rules="passwordRules"
              >
              </v-text-field>
            </v-flex>
            <v-flex class="text-center" mt-5>
              <v-btn :disabled="!valid" color="success" @click="loginUser">
                Přihlásit se
              </v-btn>
              <v-btn color="secondary" outlined class="ml-5" to="/register">
                Registrovat
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-flex>
    </v-layout>
    <v-snackbar
      top
      color="red darken-2"
      v-model="snackbar"
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
  name: "Login",
  data: () => ({
    valid: true,
    email: "",
    emailRules: [
      (v) => !!v || "E-mail je požadován",
      (v) => /.+@.+/.test(v) || "E-mail musí být ve správném formátu",
    ],
    password: "",
    confirmPassword: "",
    passwordRules: [(v) => !!v || "Heslo je požadováno!"],
    confirmPasswordRules: [(v) => !!v || "Potvrzení hesla je požadováno!"],
    snackbar: false,
    snackbarText: "Přihlašovací údaje byly zadány špatně.",
    snackbarTimeout: 10000,
  }),
  methods: {
    loginUser() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("/app/user.php", {
            action: "login",
            email: this.email,
            password: this.password,
          })
          .then((response) => {
            if (response.data === 1) {
              window.location.href = "/";
            } else {
              this.snackbar = true;
            }
          })
          .catch(function () {
            console.log("FAILURE!!");
          });
      }
    },
  },
  computed: {
    passwordConfirmationRule() {
      return () =>
        this.password === this.confirmPassword || "Password must match";
    },
  },
};
</script>

<style scoped></style>
