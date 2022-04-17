<template>
  <v-container fluid>
    <v-row no-gutters class="justify-center">
      <v-col cols="4" class="mr-16">
        <h2 class="mb-5">Skupiny kontaktů</h2>
        <v-btn class="mb-5 mr-5" color="success" @click="dialog = true"
          >Přidat skupinu kontaktů</v-btn
        >
        <v-simple-table class="mb-10 elevation-1" dense>
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
                  <v-icon
                    title="Smazat skupinu"
                    color="red"
                    @click="deleteGroup(item.id)"
                    >{{ icons.mdiDelete }}</v-icon
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
      <v-col cols="4">
        <h2 class="mb-5">Kontakty</h2>
        <v-btn class="mb-5" color="success" @click="dialog2 = true"
          >Přidat kontakty</v-btn
        >
        <v-simple-table class="mb-10 elevation-1" dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Email</th>
                <th class="text-left">Skupina</th>
                <th class="text-left">Akce</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in allContacts" :key="item.id">
                <td>
                  {{ item.email }}
                </td>
                <td>
                  {{ item.groupId }}
                </td>
                <td>
                  <v-icon
                    title="Smazat kontakt"
                    color="red"
                    @click="deleteContact(item.id)"
                    >{{ icons.mdiDelete }}</v-icon
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
    <v-dialog v-model="dialog" max-width="600px">
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
    <v-dialog v-model="dialog2" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Nahrávání nových kontaktů</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-layout column>
                <v-flex>
                  <v-select
                    v-model="group_id"
                    :items="groups"
                    item-text="name"
                    item-value="id"
                    label="Skupina kontaktů"
                    required
                    :rules="groupRules"
                  ></v-select>
                </v-flex>
                <v-flex>
                  <v-textarea
                    v-model="contacts"
                    name="contacts"
                    label="Kontakty"
                    type="text"
                    required
                    :rules="contactsRules"
                    hint="Vložte emailové adresy oddělené čárkou."
                  ></v-textarea>
                </v-flex>
              </v-layout>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog2 = false">
            Zavřít
          </v-btn>
          <v-btn
            :disabled="!valid"
            color="blue darken-1"
            text
            @click="createContact()"
          >
            Vložit kontakty
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar
      top
      :color="snackbarColor"
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
import { mdiDelete } from "@mdi/js";
export default {
  name: "Contacts",
  data: () => ({
    valid: true,
    dialog: false,
    dialog2: false,
    name: "",
    contacts: "",
    group_id: 0,
    groups: [],
    contactId: 0,
    allContacts: [],
    snackbar: false,
    snackbarText: "",
    snackbarColor: "red darken-2",
    snackbarTimeout: 10000,
    nameRules: [(v) => !!v || "Název musí být vyplněný."],
    groupRules: [(v) => !!v || "Musíte zvolit skupinu kontaktů."],
    contactsRules: [(v) => !!v || "Musíte nahrát alespoň jeden kontakt."],
    icons: {
      mdiDelete,
    },
  }),
  computed: {
    headers() {
      return [
        {
          text: "Název skupiny",
          align: "start",
          value: "groupName",
        },
        {
          text: "Akce",
          value: "action",
        },
      ];
    },
  },
  methods: {
    loadGroup() {
      this.axios
        .post("https://ampeditor.dev/app/group.php", {
          request: "readGroup",
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
    loadContacts() {
      this.axios
        .post("https://ampeditor.dev/app/contact.php", {
          request: "readContacts",
        })
        .then((response) => {
          this.allContacts = [];
          for (let j = 0; j < response.data.length; j++) {
            for (let i = 0; i < response.data[j].length; i++) {
              let contactLocal = {};
              contactLocal.id = response.data[j][i][0];
              contactLocal.groupId = response.data[j][i][1];
              contactLocal.email = response.data[j][i][2];
              this.allContacts.push(contactLocal);
            }
          }
        });
    },
    createGroup() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("https://ampeditor.dev/app/group.php", {
            request: "createGroup",
            name: this.name,
          })
          .then((response) => {
            if (response.data === 1) {
              this.snackbarText = "Skupina kontaktů úspěšně vytvořena.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              this.dialog = false;
              this.loadGroup();
            } else {
              this.snackbarText = "Skupina s tímto názvem již existuje.";
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
        .post("https://ampeditor.dev/app/group.php", {
          request: "deleteGroup",
          group_id: id,
        })
        .then((response) => {
          if (response.data === 1) {
            this.snackbarText = "Skupina úspěšně smazána.";
            this.snackbarColor = "green darken-2";
            this.snackbar = true;
            this.dialog = false;
            this.loadGroup();
            this.loadContacts();
          } else {
            this.snackbarText = "Došlo k chybě.";
            this.snackbar = true;
          }
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
    createContact() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("https://ampeditor.dev/app/contact.php", {
            request: "createContact",
            group_id: this.group_id,
            contacts: this.contacts,
          })
          .then((response) => {
            if (response.data === 1) {
              this.snackbarText = "Kontakty úspěšně vloženy.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              this.dialog2 = false;
              this.loadContacts();
            } else {
              this.snackbarText = "Došlo k chybě.";
              this.snackbar = true;
            }
          })
          .catch(function () {
            console.log("FAILURE!!");
          });
      }
    },
    deleteContact(id) {
      this.axios
        .post("https://ampeditor.dev/app/contact.php", {
          request: "deleteContact",
          contactId: id,
        })
        .then((response) => {
          if (response.data === 1) {
            this.snackbarText = "Kontakt úspěšně smazán.";
            this.snackbarColor = "green darken-2";
            this.snackbar = true;
            this.dialog = false;
            this.loadContacts();
          } else {
            this.snackbarText = "Došlo k chybě.";
            this.snackbar = true;
          }
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
  },
  created() {
    this.loadGroup();
    this.loadContacts();
  },
};
</script>

<style scoped></style>
