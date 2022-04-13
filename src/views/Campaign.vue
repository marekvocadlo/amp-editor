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
              ></v-text-field>
            </v-flex>
            <v-flex>
              <v-select
                v-model="contactGroup"
                :items="contactGroups"
                label="Skupina kontaktů"
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
    <v-snackbar top color="red darken-2" v-model="snackbar" :timeout="timeout">
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
    timeout: 4000,
    contactGroup: 0,
    contactGroups: [],
  }),
  methods: {
    sendCampaign() {
      if (this.$refs.form.validate()) {
        this.axios
          .post("https://ampeditor.dev/sendCampaign.php", {
            subject: this.subject,
            contactGroup: this.contactGroup,
          })
          .then((data) => {
            if (data.data === "Successfully sent") {
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
        this.contactGroups = response.data;
        console.log(response.data);

        // if (data.data === "Skupina 1") {
        //   this.contactGroups = ["Skupina 1"];
        // }
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
