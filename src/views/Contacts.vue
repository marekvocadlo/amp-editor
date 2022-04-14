<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 sm4 offset-sm4 mt-3>
        <h1 class="mb-5">Kontakty</h1>
        <v-btn class="mb-5 mr-3" color="success" @click="dialog = true"
          >Přidat skupinu kontaktů</v-btn
        >
        <v-btn class="mb-5" color="success">Přidat kontakty</v-btn>
      </v-flex>
      <v-flex xs12 sm4 offset-sm4 mt-5>
        <h2 class="mb-5">Skupiny kontaktů</h2>
        <v-simple-table dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Název skupiny</th>
                <th class="text-left">Akce</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in groups" :key="item.id">
                <td>
                  {{ item.name }}
                </td>
                <td>
                  <v-btn
                    class="white--text"
                    small
                    color="red"
                    @click="deleteGroup(item.id)"
                    >Smazat</v-btn
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-flex>
    </v-layout>
    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="text-h5">Nová skupina kontaktů</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="name"
                      label="Název skupiny"
                      required
                      :rules="nameRules"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-form>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialog = false">
              Zavřít
            </v-btn>
            <v-btn
              :disabled="!valid"
              color="blue darken-1"
              text
              @click="createGroup()"
            >
              Vytvořit
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <v-snackbar
      top
      :color="snackbar_color"
      v-model="snackbar"
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
  name: "Contacts",
  data: () => ({
    valid: true,
    dialog: false,
    name: "",
    groups: [],
    snackbar: false,
    snackbar_text: "",
    snackbar_color: "red darken-2",
    timeout: 4000,
    nameRules: [(v) => !!v || "Název musí být vyplněný."],
  }),
  methods: {
    refreshTable() {
      this.axios
        .post("https://ampeditor.dev/contact.php", {
          request: 1,
        })
        .then((response) => {
          this.groups = [];
          for (let i = 0; i < response.data.length; i++) {
            let groupLocal = {};
            groupLocal.id = response.data[i][0];
            groupLocal.name = response.data[i][1];
            this.groups.push(groupLocal);
          }
        });
    },
    createGroup() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("https://ampeditor.dev/contact.php", {
            request: 2,
            name: this.name,
          })
          .then((data) => {
            if (data.data === "Insert successfully") {
              this.snackbar_color = "green darken-2";
              this.snackbar_text = "Skupina úspěšně vytvořena.";
              this.snackbar = true;
              this.dialog = false;
              this.refreshTable();
            } else {
              this.snackbar_text = "Došlo k chybě.";
              this.snackbar = true;
            }
          })
          .catch(function () {
            console.log("FAILURE!!");
          });
      }
    },
    deleteGroup(id) {
      this.axios
        .post("https://ampeditor.dev/contact.php", {
          request: 3,
          group_id: id,
        })
        .then((data) => {
          if (data.data === "List deleted") {
            this.snackbar_color = "green darken-2";
            this.snackbar_text = "Skupina úspěšně smazána.";
            this.snackbar = true;
            this.dialog = false;
            this.refreshTable();
          } else {
            this.snackbar_text = "Došlo k chybě.";
            this.snackbar = true;
          }
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
  },
  created() {
    this.refreshTable();
  },
};
</script>

<style scoped></style>
