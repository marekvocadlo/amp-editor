<template>
  <v-container v-if="getUser.email">
    <v-row no-gutters class="justify-center">
      <v-col cols="7">
        <v-btn
          class="mt-10"
          color="success"
          @click="dialogCreateTemplate = true"
          >Vytvořit nový obsah e-mailu</v-btn
        >
        <h3 class="mt-10 mb-5">Vytvořené obsahy e-mailu</h3>
        <!-- User templates table -->
        <v-simple-table class="mb-10 elevation-1" dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Název</th>
                <th class="text-left">Vytvořeno</th>
                <th class="text-left">Poslední úprava</th>
                <th class="text-left">Akce</th>
                <th class="text-left">Export e-mailu</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in userTemplates" :key="item.id">
                <td>
                  {{ item.name }}
                </td>
                <td>
                  {{ item.created }}
                </td>
                <td>
                  {{ item.updated }}
                </td>
                <td class="pa-2">
                  <v-btn
                    outlined
                    class="mr-3"
                    small
                    title="Editovat e-mail"
                    color="primary"
                    @click="openEditor(item.id)"
                    >Editovat</v-btn
                  >
                  <v-btn
                    outlined
                    small
                    title="Editovat e-mail"
                    color="red"
                    @click="deleteUserTemplate(item.id)"
                    >Smazat</v-btn
                  >
                </td>
                <td class="pa-2">
                  <v-btn
                    outlined
                    class="mr-3"
                    small
                    title="Exportovat AMP e-mail"
                    color="secondary"
                    :href="item.ampURL"
                    download
                    >AMP</v-btn
                  >
                  <v-btn
                    outlined
                    class="mr-3"
                    small
                    title="Exportovat HTML e-mail"
                    color="secondary"
                    :href="item.htmlURL"
                    download
                    >HTML</v-btn
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
    <!-- Form to create new group -->
    <v-dialog v-model="dialogCreateTemplate" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Nový obsah e-mailu</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="myTemplateName"
                    label="Název obsahu"
                    required
                    :rules="templateNameRules"
                  ></v-text-field>
                  <v-select
                    v-model="templateId"
                    :items="templates"
                    item-text="name"
                    item-value="id"
                    label="Vzor"
                    required
                    :rules="templatesRules"
                  ></v-select>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="dialogCreateTemplate = false"
          >
            Zavřít
          </v-btn>
          <v-btn
            :disabled="!valid"
            color="blue darken-1"
            text
            @click="createUserTemplate()"
          >
            Vytvořit
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import moment from "moment";

export default {
  name: "Templates",
  data: () => ({
    myTemplateName: "",
    templateId: 0,
    templateName: "",
    templates: [],
    userTemplateId: 0,
    userTemplateName: "",
    userTemplates: [],
    valid: true,
    dialogCreateTemplate: false,
    templateNameRules: [(v) => !!v || "Musíte zvolit název šablony."],
    templatesRules: [(v) => !!v || "Musíte zvolit šablonu."],
  }),
  created() {
    this.$store.dispatch("getUser");
    this.readTemplates();
    this.readUserTemplates();
  },
  methods: {
    createUserTemplate() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("/app/user_template.php", {
            name: this.myTemplateName,
            template_id: this.templateId,
          })
          .then((response) => {
            if (response.data === 1) {
              this.snackbarText = "Šablona úspěšně vytvořena.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              this.dialogCreateTemplate = false;
              this.myTemplateName = "";
              this.readUserTemplates();
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
    readTemplates() {
      this.axios.get("/app/template.php").then((response) => {
        this.templates = [];
        for (let i = 0; i < response.data.length; i++) {
          let tempTemplate = {};
          tempTemplate.id = response.data[i][0];
          tempTemplate.name = response.data[i][1];
          this.templates.push(tempTemplate);
        }
      });
    },
    readUserTemplates() {
      this.axios.get("/app/user_template.php").then((response) => {
        this.userTemplates = [];
        for (let i = 0; i < response.data.length; i++) {
          let tempTemplate = {};
          tempTemplate.id = response.data[i][0];
          tempTemplate.ampURL =
            "/files/index_amp" + response.data[i][0] + ".html";
          tempTemplate.htmlURL =
            "/files/index_html" + response.data[i][0] + ".html";
          tempTemplate.name = response.data[i][1];
          tempTemplate.created = moment(response.data[i][4]).format(
            "DD. MM. YYYY HH:mm"
          );
          tempTemplate.updated = moment(response.data[i][5]).format(
            "DD. MM. YYYY HH:mm"
          );
          this.userTemplates.push(tempTemplate);
        }
      });
    },
    openEditor(id) {
      window.location.href = "/editor?id=" + id;
    },
    deleteUserTemplate(id) {
      this.axios
        .delete("/app/user_template.php", {
          data: {
            id: id,
          },
        })
        .then((response) => {
          if (response.data === 1) {
            this.snackbarText = "Šablona úspěšně smazána.";
            this.snackbarColor = "green darken-2";
            this.snackbar = true;
            this.readUserTemplates();
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
  },
  computed: {
    getUser() {
      return this.$store.state.user;
    },
  },
};
</script>

<style scoped></style>
