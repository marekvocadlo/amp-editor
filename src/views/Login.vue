<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-xs-center" mt-5>
        <h1>Přihlásit se</h1>
      </v-flex>
      <v-flex xs12 sm6 offset-sm3 mt-3>
        <v-form @submit="loginForm" ref="form" v-model="valid" lazy-validation>
          <v-layout column>
            <v-flex>
              <v-text-field
                v-model="email"
                name="email"
                label="E-mail"
                type="email"
                :rules="emailRules"
                required
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="password"
                name="password"
                label="Heslo"
                type="password"
                :rules="passwordRules"
                required
              ></v-text-field>
            </v-flex>
            <v-flex class="text-xs-center" mt-5>
              <v-btn :disabled="!valid" color="primary" type="submit"
                >Přihlásit se</v-btn
              >

              <v-btn
                color="secondary"
                outlined
                class="registerBtn"
                to="/register"
              >
                Registrovat
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
  name: "Login",
  data: () => ({
    valid: true,
    name: "",
    email: "",
    password: "",
    emailRules: [
      (v) => !!v || "E-mail je požadován!",
      (v) => /.+@.+/.test(v) || "E-mail musí být validní!",
    ],
    passwordRules: [(v) => !!v || "Heslo je požadováno!"],
  }),
  created() {
    this.getUser();
  },
  methods: {},
};
</script>

<style scoped>
h1 {
  text-align: center;
  margin-top: 3%;
}
.title,
.v-list-item__subtitle {
  text-align: center;
}
.registerBtn {
  margin-left: 3%;
}
.signup-buttons {
  margin-top: 15px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  position: relative;
}
.facebook-signup,
.google-signup {
  color: #031b4e;
  background: #f2f8ff;
  border: 1px solid rgba(0, 105, 255, 0.2);
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border-radius: 3px;
  display: inline-block;
  margin-top: 0;
  width: 47.5%;
  padding: 15px;
  text-align: center;
  position: inherit;
  vertical-align: middle;
  text-decoration: none;
}
.signup-buttons svg {
  left: 16px;
  position: absolute;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}
</style>
