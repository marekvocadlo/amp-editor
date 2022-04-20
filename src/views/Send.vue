<template>
  <v-container v-if="getUser.email" fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-center" mt-10>
        <h1>Rozeslat kampaň</h1>
      </v-flex>
      <v-flex xs12 sm4 offset-sm4 mt-3>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout column>
            <v-flex>
              <v-text-field
                v-model="name"
                name="name"
                label="Název kampaně"
                type="text"
                required
                :rules="nameRules"
                hint="Název kampaně uvidíte pouze vy."
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="senderName"
                name="senderName"
                label="Jméno odesílatele"
                type="text"
                required
                :rules="senderNameRules"
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="senderEmail"
                name="senderEmail"
                label="Email odesílatele"
                type="text"
                required
                :rules="senderEmailRules"
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-text-field
                v-model="subject"
                name="subject"
                label="Předmět emailu"
                type="text"
                required
                :rules="subjectRules"
              ></v-text-field>
            </v-flex>
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
              <v-select
                v-model="template_id"
                :items="templates"
                item-text="name"
                item-value="id"
                label="Šablony"
                required
                :rules="templatesRules"
              ></v-select>
            </v-flex>
            <v-flex class="text-center" mt-5>
              <v-btn :disabled="!valid" color="success" @click="sendCampaign">
                Rozeslat kampaň
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-flex>
    </v-layout>
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
export default {
  name: "Campaigns",
  data: () => ({
    valid: true,
    name: "",
    senderName: "",
    senderEmail: "",
    subject: "",
    snackbar: false,
    group_id: 0,
    groups: [],
    template_id: 0,
    templates: [],
    nameRules: [(v) => !!v || "Název musí být vyplněný."],
    senderNameRules: [(v) => !!v || "Jméno odesílatele musí být vyplněno."],
    senderEmailRules: [(v) => !!v || "Email odesílatele musí být vyplněn."],
    subjectRules: [(v) => !!v || "Předmět musí být vyplněný."],
    groupRules: [(v) => !!v || "Musíte zvolit skupinu kontaktů."],
    templatesRules: [(v) => !!v || "Musíte zvolit šablonu."],
    snackbarText: "",
    snackbarColor: "red darken-2",
    snackbarTimeout: 10000,
  }),
  created() {
    this.$store.dispatch("getUser");
    this.loadGroup();
    this.loadTemplates();
  },
  methods: {
    sendCampaign() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("https://ampeditor.dev/app/campaign.php", {
            request: "sendCampaign",
            name: this.name,
            senderName: this.senderName,
            senderEmail: this.senderEmail,
            subject: this.subject,
            group_id: this.group_id,
            template_id: this.template_id,
          })
          .then((data) => {
            if (data.data === 1) {
              this.snackbarText = "Kampaň úspěšně rozeslána.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
            } else if (data.data === 2) {
              this.snackbarText = "Kontaktní skupina neobsahuje žádné emaily.";
              this.snackbar = true;
            } else {
              this.snackbarText =
                "Chyba. Na jednu nebo více adres se nepovedlo rozeslat.";
              this.snackbar = true;
            }
          })
          .catch(function () {
            console.log("FAILURE!!");
          });
      }
    },
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
    loadTemplates() {
      this.axios
        .post("https://ampeditor.dev/app/template.php", {
          request: "readTemplates",
        })
        .then((response) => {
          for (let i = 0; i < response.data.length; i++) {
            let templateLocal = {};
            templateLocal.id = response.data[i][0];
            templateLocal.name = response.data[i][1];
            this.templates.push(templateLocal);
          }
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
