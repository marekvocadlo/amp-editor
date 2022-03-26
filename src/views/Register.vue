<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-xs-center" mt-5>
        <h1>Vytvořit nový účet</h1>
      </v-flex>
      <v-flex xs12 sm6 offset-sm3 mt-3>
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
            <v-flex>
              <v-text-field
                v-model="confirmPassword"
                name="confirmPassword"
                label="Potvrzení hesla"
                type="password"
                required
                :rules="confirmPasswordRules.concat(passwordConfirmationRule)"
              ></v-text-field>
            </v-flex>
            <v-flex class="text-xs-center" mt-5>
              <v-btn :disabled="!valid" color="success" @click="registerUser">
                Registrovat se pomocí e-mailu
              </v-btn>
              <v-btn
                color="secondary"
                outlined
                class="ml-5"
                to="/login"
              >
                Zpět na přihlášení
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>

export default {
  name: "Register",
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
  }),
  methods: {
    registerUser(e) {
      if (this.$refs.form.validate()) {
        e.preventDefault();
        let currentObj = this;
        console.log(this.email)
        this.axios
          .post(
            "https://ampeditor.dev/script/register.php",
            {
              email: this.email,
              password: this.password,
              request: 2,
            },
            { withCredentials: true }
          )
          .then(function (response) {
            currentObj.output = response.data;
            if (response.data.status === "alreadyExists") {
              alert("Tento email je již zaregistrovaný.");
            } else {
              //currentObj.$router.push('/')
              alert("Úspěšně zaregistrován!");
              window.location.href = "/";
            }
          })
          .catch(function (error) {
            currentObj.output = error;
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

<style scoped>
</style>
