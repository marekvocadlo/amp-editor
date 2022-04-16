<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-center" mt-10>
        Přihlášený uživatel:
        <h2>{{ getUser.email }}</h2>
        Zaregistrován dne: {{ getUser.created_at }}
      </v-flex>
      <v-flex xs12 sm4 offset-sm4 mt-3>
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
                v-model="name"
                name="name"
                label="Jméno"
                type="text"
                required
                :rules="nameRules"
              >
              </v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="surname"
                surname="surname"
                label="Příjmení"
                type="text"
                required
                :rules="surnameRules"
              >
              </v-text-field>
            </v-flex>
            <v-flex class="text-center" mt-5>
              <v-btn :disabled="!valid" color="success" @click="updateUser">
                Aktualizovat údaje
              </v-btn>
              <v-btn class="ml-5 white--text" color="red" @click="deleteUser">
                Smazat účet
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-flex>
    </v-layout>
    <v-snackbar
      top
      v-model="snackbar"
      :color="snackbar_color"
      :timeout="timeout"
    >
      {{ snackbar_text }}
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
    nameRules: [(v) => !!v || "Jméno je požadováno!"],
    surnameRules: [(v) => !!v || "Příjmení je požadováno!"],
    snackbar: false,
    snackbar_text: "",
    snackbar_color: "red darken-2",
    timeout: 8000,
  }),
  created() {
    this.$store.dispatch("getUser");
  },
  computed: {
    getUser() {
      return this.$store.state.user;
    },
  },
  methods: {
    updateUser() {
      if (this.$refs.form.validate()) {
        this.axios
          .post(
            "https://ampeditor.dev/app/user.php",
            {
              email: this.email,
              name: this.name,
              surname: this.surname,
              request: 3,
            },
            { withCredentials: true }
          )
          .then((data) => {
            if (data.data === "Update successfully") {
              this.snackbar_color = "green darken-2";
              this.snackbar_text = "Uživatelské údaje úspěšně aktualizovány.";
              this.snackbar = true;
            } else {
              this.snackbar_text = "Něco se nepovedlo.";
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
        .post(
          "https://ampeditor.dev/app/user.php",
          {
            request: 4,
          },
          { withCredentials: true }
        )
        .then(() => {
          alert("Váš účet byl smazaný!");
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
