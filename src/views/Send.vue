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
                :placeholder="senderName"
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-select
                v-model="senderEmail"
                :items="senderEmails"
                item-text="email"
                item-value="email"
                label="Email odesílatele"
                required
                :rules="senderEmailRules"
                hint="V případě, že zatím nemáte u Googlu autorizovanou vlastní rozesílací adresu, můžete pro testovací účely použít adresu amptest@emailkampane.cz"
              ></v-select>
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
              <v-select
                v-model="templateId"
                :items="templates"
                item-text="name"
                item-value="id"
                label="Připravený obsah e-mailu"
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
          Zavřít
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
    groupId: 0,
    groups: [],
    templateId: 0,
    templates: [],
    senderEmails: [],
    nameRules: [(v) => !!v || "Název musí být vyplněný."],
    senderNameRules: [(v) => !!v || "Jméno odesílatele musí být vyplněno."],
    senderEmailRules: [(v) => !!v || "Email odesílatele musí být vyplněn."],
    subjectRules: [(v) => !!v || "Předmět musí být vyplněný."],
    groupRules: [(v) => !!v || "Musíte zvolit skupinu kontaktů."],
    templatesRules: [(v) => !!v || "Musíte zvolit upravenou šablonu."],
    snackbarText: "",
    snackbarColor: "red darken-2",
    snackbarTimeout: 10000,
  }),
  created() {
    this.$store.dispatch("getUser");
    this.readGroups();
    this.readTemplates();
    this.readUserEmails();
    this.readUser();
  },
  methods: {
    sendCampaign() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("/app/campaign.php", {
            action: "send",
            name: this.name,
            senderName: this.senderName,
            senderEmail: this.senderEmail,
            subject: this.subject,
            group_id: this.groupId,
            template_id: this.templateId,
          })
          .then((data) => {
            if (data.data === 1) {
              this.snackbarText = "Kampaň úspěšně rozeslána.";
              this.snackbarColor = "green darken-2";
              this.snackbar = true;
              window.setTimeout(function () {
                window.location.href = "/campaigns";
              }, 2000);
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
    readGroups() {
      this.axios.get("/app/group.php").then((response) => {
        this.groups = [];
        for (let i = 0; i < response.data.length; i++) {
          let groupLocal = {};
          groupLocal.id = response.data[i][0];
          groupLocal.name = response.data[i][1];
          this.groups.push(groupLocal);
        }
      });
    },
    readTemplates() {
      this.axios.get("/app/user_template.php").then((response) => {
        for (let i = 0; i < response.data.length; i++) {
          let templateLocal = {};
          templateLocal.id = response.data[i][0];
          templateLocal.name = response.data[i][1];
          this.templates.push(templateLocal);
        }
      });
    },
    readUserEmails() {
      this.axios.get("/app/user.php").then((response) => {
        this.senderEmails.push({ email: response.data[1] });
        this.senderEmails.push({ email: "amptest@emailkampane.cz" });
      });
    },
    readUser() {
      this.axios.get("/app/user.php").then((response) => {
        this.senderName = response.data[3];
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
