<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-center" mt-10>
        <h1>Upravit údaje</h1>
      </v-flex>
      <v-flex xs12 sm4 offset-sm4 mt-3>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout column>
            <v-flex>
              <v-text-field
                v-model="id"
                name="id"
                label="ID"
                type="id"
                required
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="name"
                name="name"
                label="Jméno"
                type="name"
              >
              </v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="surname"
                surname="surname"
                label="Příjmení"
                type="surname"
              >
              </v-text-field>
            </v-flex>
            <v-flex class="text-center" mt-5>
              <v-btn :disabled="!valid" color="success" @click="updateUser">
                Aktualizovat údaje
              </v-btn>
              <v-btn class="ml-5" color="warning" @click="deleteUser">
                Smazat účet
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
  name: "Settings",
  data: () => ({
    valid: true,
    id: "",
    name: "",
    surname: "",
  }),
  methods: {
    updateUser(e) {
      if (this.$refs.form.validate()) {
        e.preventDefault();
        let currentObj = this;
        this.axios
          .post(
            "https://ampeditor.dev/script/register.php",
            {
              id: this.id,
              name: this.name,
              surname: this.surname,
              request: 3,
            },
            { withCredentials: true }
          )
          .then(function (response) {
            currentObj.output = response.data;
            alert("Aktualizace údajů proběhla úspěšně!");
            window.location.href = "/";
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    deleteUser(e) {
      if (this.$refs.form.validate()) {
        e.preventDefault();
        let currentObj = this;
        this.axios
          .post(
            "https://ampeditor.dev/script/register.php",
            {
              id: this.id,
              request: 4,
            },
            { withCredentials: true }
          )
          .then(function (response) {
            currentObj.output = response.data;
            alert("Váš účet byl smazaný!");
            window.location.href = "/";
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
  },
};
</script>

<style scoped></style>
