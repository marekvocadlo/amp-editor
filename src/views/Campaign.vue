<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 class="text-center" mt-10>
        <h1>Rozeslat kampaň</h1>
      </v-flex>
      <v-flex xs12 sm4 offset-sm4 mt-3>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-layout column>
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
                Rozeslat
              </v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-flex>
    </v-layout>
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
  name: "Campaign",
  data: () => ({
    valid: true,
    subject: "",
    snackbar: false,
    snackbar_text: "",
    snackbar_color: "red darken-2",
    timeout: 4000,
    group_id: 0,
    groups: [],
    template_id: 0,
    templates: [],
    subjectRules: [(v) => !!v || "Předmět musí být vyplněný."],
    groupRules: [(v) => !!v || "Musíte zvolit skupinu kontaktů."],
    templatesRules: [(v) => !!v || "Musíte zvolit šablonu."],
  }),
  methods: {
    sendCampaign() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("https://ampeditor.dev/sendCampaign.php", {
            subject: this.subject,
            group_id: this.group_id,
            template_id: this.template_id,
          })
          .then((data) => {
            if (data.data === "Successfully sent") {
              this.snackbar_color = "green darken-2";
              this.snackbar_text = "Kampaň úspěšně rozeslána.";
              this.snackbar = true;
            } else if (data.data === "Contact list is empty") {
              this.snackbar_text = "Kontaktní skupina neobsahuje žádné emaily.";
              this.snackbar = true;
            } else {
              this.snackbar_text = "Došlo k chybě na rozesílači.";
              this.snackbar = true;
            }
          })
          .catch(function () {
            console.log("FAILURE!!");
          });
      }
    },
  },
  created() {
    this.$store.dispatch("getUser");
    this.axios
      .post("https://ampeditor.dev/contact.php", {
        request: 1,
      })
      .then((response) => {
        for (let i = 0; i < response.data.length; i++) {
          let groupLocal = {};
          groupLocal.id = response.data[i][0];
          groupLocal.name = response.data[i][1];
          this.groups.push(groupLocal);
        }
      });
    this.axios
      .post("https://ampeditor.dev/template.php", {
        request: 1,
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
  computed: {
    getUser() {
      return this.$store.state.user;
    },
  },
};
</script>

<style scoped></style>
