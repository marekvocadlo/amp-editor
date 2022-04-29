<template>
  <v-container v-if="getUser.email" fluid>
    <v-row no-gutters class="justify-center">
      <!-- Contact groups column -->
      <v-col cols="6">
        <h2 class="mt-10 mb-5">Skupiny kontaktů</h2>
        <v-btn
          class="mb-10 mr-5"
          color="success"
          @click="dialogCreateGroup = true"
          >Přidat skupinu kontaktů</v-btn
        >
        <v-btn class="mb-10" color="success" @click="dialogAddContact = true"
          >Přidat kontakty</v-btn
        >
        <!-- Contact groups table -->
        <v-simple-table class="mb-10 elevation-1" dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Název skupiny</th>
                <th class="text-left">Počet kontaktů</th>
                <th class="text-left actionCellWidth">Akce</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in groups" :key="item.id">
                <td>
                  {{ item.name }}
                </td>
                <td>
                  {{ item.numberOfContacts }}
                </td>
                <td class="pa-2">
                  <v-btn
                    outlined
                    class="mr-3"
                    small
                    title="Zobrazit skupinu"
                    color="primary"
                    @click="readContacts(item.id)"
                    >Zobrazit</v-btn
                  >
                  <v-btn
                    outlined
                    class="mr-3"
                    small
                    title="Editovat skupinu"
                    color="secondary"
                    @click="
                      dialogUpdateGroup = true;
                      updateGroupId = item.id;
                    "
                    >Editovat</v-btn
                  >
                  <v-btn
                    outlined
                    class="white--text"
                    small
                    title="Smazat skupinu"
                    color="red"
                    @click="deleteGroup(item.id)"
                    >Smazat</v-btn
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
    <!-- Form to create new group -->
    <v-dialog v-model="dialogCreateGroup" max-width="600px">
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
                    v-model="groupName"
                    label="Název skupiny"
                    required
                    :rules="groupNameRules"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialogCreateGroup = false">
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
    <!-- Form to update group -->
    <v-dialog v-model="dialogUpdateGroup" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Přejmenovat skupinu</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="form3" v-model="valid3" lazy-validation>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="groupNameUpdate"
                    label="Název skupiny"
                    required
                    :rules="groupNameRules"
                    :placeholder="groupName"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialogUpdateGroup = false">
            Zavřít
          </v-btn>
          <v-btn
            :disabled="!valid"
            color="blue darken-1"
            text
            @click="updateGroup()"
          >
            Přejmenovat
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- Modal with contacts in group -->
    <v-dialog v-model="dialogReadGroup" max-width="800px">
      <v-card class="pa-5">
        <v-card-title>
          <span class="text-h5">Kontakty ve skupině</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-data-table
              v-model="selected"
              :headers="headers"
              :items="allContacts"
              item-key="id"
              class="elevation-1"
              :search="search"
              :custom-filter="filterText"
              show-select
              :footer-props="{
                'items-per-page-text': 'Počet záznamů na stránce',
              }"
            >
              <template v-slot:top>
                <v-text-field
                  v-model="search"
                  label="Hledat"
                  class="mx-4"
                ></v-text-field>
                <v-btn
                  :disabled="selected.length === 0"
                  class="white--text ma-3"
                  small
                  title="Smazat vybrané kontakty"
                  color="red"
                  @click="deleteContacts()"
                  >Smazat</v-btn
                >
              </template>
            </v-data-table>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="blue darken-1"
            text
            @click="
              dialogAddContact = true;
              dialogReadGroup = false;
            "
          >
            Přidat kontakty
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialogReadGroup = false">
            Zavřít
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- Add new contacts -->
    <v-dialog v-model="dialogAddContact" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Nahrávání nových kontaktů</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="form2" v-model="valid2" lazy-validation>
              <v-layout column>
                <v-flex>
                  <v-select
                    v-model="groupId"
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
                    hint="Vložte e-mailové adresy oddělené čárkou, středníkem nebo každý e-mail na samostatném řádku"
                  ></v-textarea>
                </v-flex>
              </v-layout>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialogAddContact = false">
            Zavřít
          </v-btn>
          <v-btn
            :disabled="!valid2"
            color="blue darken-1"
            text
            @click="createContact()"
          >
            Vložit kontakty
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- Snackbar -->
    <v-snackbar
      top
      :color="snackbarColor"
      v-model="snackbar"
      :timeout="snackbarTimeout"
    >
      {{ snackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Zavřít
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import { mdiDelete, mdiEye } from "@mdi/js";
export default {
  name: "Contacts",
  data: () => ({
    selected: [],
    search: "",
    groupName: "",
    groupNameUpdate: "",
    groupId: 0,
    groups: [],
    contacts: "",
    contactId: 0,
    allContacts: [],
    updateGroupId: 0,
    valid: true,
    valid2: true,
    valid3: true,
    dialogCreateGroup: false,
    dialogReadGroup: false,
    dialogAddContact: false,
    dialogUpdateGroup: false,
    snackbar: false,
    snackbarText: "",
    snackbarColor: "red darken-2",
    snackbarTimeout: 10000,
    groupNameRules: [(v) => !!v || "Název musí být vyplněný."],
    groupRules: [(v) => !!v || "Musíte zvolit skupinu kontaktů."],
    contactsRules: [(v) => !!v || "Musíte nahrát alespoň jeden kontakt."],
    icons: {
      mdiDelete,
      mdiEye,
    },
  }),
  computed: {
    getUser() {
      return this.$store.state.user;
    },
    headers() {
      return [
        {
          text: "Email",
          align: "start",
          sortable: false,
          value: "email",
        },
      ];
    },
  },
  methods: {
    filterText(value, search) {
      return (
        value != null &&
        search != null &&
        typeof value === "string" &&
        value.toString().indexOf(search) !== -1
      );
    },
    createGroup() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("/app/group.php", {
            groupName: this.groupName,
          })
          .then((response) => {
            if (response.data === 1) {
              this.snackbarText = "Skupina kontaktů úspěšně vytvořena.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              this.dialogCreateGroup = false;
              this.readGroup();
            } else if (response.data === 0) {
              this.snackbarText = "Skupina s tímto názvem již existuje.";
              this.snackbar = true;
            } else {
              this.snackbarText = "Došlo k neočekávané chybě.";
              this.snackbar = true;
            }
          })
          .catch(function () {
            console.log("FAILURE!!");
          });
      }
    },
    createContact() {
      if (this.$refs.form2.validate()) {
        this.axios
          .post("/app/contact.php", {
            group_id: this.groupId,
            contacts: this.contacts,
          })
          .then((response) => {
            if (response.data === 1) {
              this.snackbarText = "Validní e-mailové adresy úspěšně vloženy.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              this.dialogAddContact = false;
              this.contacts = "";
              this.readGroup();
            } else if (response.data === 0) {
              this.snackbarText =
                "Validní e-mailové adresy úspěšně vloženy, duplicity ignorovány.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              this.dialogAddContact = false;
              this.contacts = "";
              this.readGroup();
            } else {
              this.snackbarText = "Došlo k neočekávané chybě.";
              this.snackbarColor = "red darken-2";
              this.snackbar = true;
            }
          })
          .catch(function () {
            console.log("FAILURE!!");
          });
      }
    },
    readGroup() {
      this.axios.get("/app/group.php").then((response) => {
        this.groups = [];
        for (let i = 0; i < response.data.length; i++) {
          let groupLocal = {};
          groupLocal.id = response.data[i][0];
          groupLocal.name = response.data[i][1];
          groupLocal.numberOfContacts = response.data[i][3];
          this.groups.push(groupLocal);
        }
      });
    },
    readContacts(id) {
      this.axios.get("/app/contact.php?id=" + id).then((response) => {
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
        this.dialogReadGroup = true;
      });
    },
    updateGroup() {
      if (this.$refs.form3.validate()) {
        this.axios
          .put(
            "/app/group.php",
            {
              id: this.updateGroupId,
              name: this.groupNameUpdate,
            },
            { withCredentials: true }
          )
          .then((response) => {
            if (response.data === 1) {
              this.snackbarText = "Skupina úspěšně přejmenována.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              this.dialogUpdateGroup = false;
              this.readGroup();
            } else {
              this.snackbarText = "Došlo k neočekávané chybě.";
              this.snackbarColor = "red darken-2";
              this.snackbar = true;
            }
          });
      }
    },
    deleteGroup(id) {
      this.axios
        .delete("/app/group.php", {
          data: {
            group_id: id,
          },
        })
        .then((response) => {
          if (response.data === 1) {
            this.snackbarText = "Skupina úspěšně smazána.";
            this.snackbarColor = "green darken-2";
            this.snackbar = true;
            this.dialogCreateGroup = false;
            this.readGroup();
          } else {
            this.snackbarText = "Nastala neočekávaná chyba.";
            this.snackbarColor = "red darken-2";
            this.snackbar = true;
          }
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
    deleteContacts() {
      console.log(this.selected);
      this.axios
        .delete("/app/contact.php", {
          data: {
            contacts: this.selected,
          },
        })
        .then((response) => {
          if (response.data === 1) {
            this.snackbarText = "Kontakty úspěšně smazány.";
            this.snackbarColor = "green darken-2";
            this.snackbar = true;
            this.dialogReadGroup = false;
            this.readGroup();
            this.selected = [];
          } else {
            this.snackbarText = "Došlo k chybě.";
            this.snackbarColor = "red darken-2";
            this.snackbar = true;
          }
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
  },
  created() {
    this.$store.dispatch("getUser");
    this.readGroup();
  },
};
</script>

<style scoped>
.actionCellWidth {
  width: 310px;
}
</style>
