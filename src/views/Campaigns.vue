<template>
  <v-container v-if="getUser.email" fluid>
    <v-row no-gutters class="justify-center">
      <v-col cols="8">
        <v-row no-gutters class="justify-space-between align-center">
          <v-col>
            <h2 class="my-5">Přehled kampaní</h2>
          </v-col>
          <v-col class="text-right">
            <router-link to="/send">
              <v-btn color="success">Poslat novou kampaň</v-btn>
            </router-link>
          </v-col>
        </v-row>
        <v-simple-table class="mb-10 elevation-1" dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Název kampaně</th>
                <th class="text-left">Jméno odesílatele</th>
                <th class="text-left">Odesílací email</th>
                <th class="text-left">Předmět</th>
                <th class="text-left">Počet příjemců</th>
                <th class="text-left">Datum</th>
                <th class="text-left">Akce</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in campaigns" :key="item.id">
                <td>{{ item.name }}</td>
                <td>{{ item.senderName }}</td>
                <td>{{ item.email }}</td>
                <td>{{ item.subject }}</td>
                <td>{{ item.numberOfRecipients }}</td>
                <td>{{ item.date }}</td>
                <td>
                  <v-icon
                    title="Smazat kampaň"
                    color="red"
                    @click="deleteCampaign(item.id)"
                    >{{ icons.mdiDelete }}</v-icon
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
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
import moment from "moment";
export default {
  name: "Campaigns",
  data: () => ({
    valid: true,
    campaigns: [],
    icons: {
      mdiDelete,
      mdiEye,
    },
    snackbar: false,
    snackbarText: "",
    snackbarColor: "red darken-2",
    snackbarTimeout: 10000,
  }),
  created() {
    this.$store.dispatch("getUser");
    this.readCampaigns();
  },
  methods: {
    readCampaigns() {
      this.axios.get("/app/campaign.php").then((response) => {
        this.campaigns = [];
        for (let i = 0; i < response.data.length; i++) {
          let tempCampaign = {};
          tempCampaign.id = response.data[i][0];
          tempCampaign.name = response.data[i][1];
          tempCampaign.senderName = response.data[i][2];
          tempCampaign.email = response.data[i][3];
          tempCampaign.subject = response.data[i][4];
          tempCampaign.numberOfRecipients = response.data[i][10];
          tempCampaign.date = moment(response.data[i][6]).format(
            "DD. MM. YYYY HH:mm"
          );
          this.campaigns.push(tempCampaign);
        }
      });
    },
    deleteCampaign(id) {
      this.axios
        .delete("/app/campaign.php", {
          data: {
            campaignId: id,
          },
        })
        .then((response) => {
          if (response.data === 1) {
            this.snackbarText = "Kampaň úspěšně smazána.";
            this.snackbarColor = "green darken-2";
            this.snackbar = true;
            this.readCampaigns();
          } else {
            this.snackbarText = "Nastala neočekávaná chyba.";
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
