<template>
  <v-container v-if="getUser.admin === '1'">
    <v-row no-gutters class="justify-center">
      <v-col cols="6">
        <h3 class="mt-10 mb-5">Uživatelé</h3>
        <!-- User templates table -->
        <v-simple-table class="mb-10 elevation-1" dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">ID</th>
                <th class="text-left">E-mail</th>
                <th class="text-left">Jméno</th>
                <th class="text-left">Vytvořen</th>
                <th class="text-left">Aktualizován</th>
                <th class="text-left">Akce</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in users" :key="item.id">
                <td>
                  {{ item.id }}
                </td>
                <td>
                  {{ item.email }}
                </td>
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
                    small
                    title="Smazat uživatele"
                    color="red"
                    @click="deleteUser(item.id)"
                    >Smazat</v-btn
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import moment from "moment";

export default {
  name: "Users",
  data: () => ({
    users: [],
  }),
  created() {
    this.$store.dispatch("getUser");
    this.readUsers();
  },
  methods: {
    readUsers() {
      this.axios.get("/app/user.php?admin=1").then((response) => {
        this.users = [];
        for (let i = 0; i < response.data.length; i++) {
          let tempUser = {};
          tempUser.id = response.data[i][0];
          tempUser.email = response.data[i][1];
          tempUser.name = response.data[i][3];
          tempUser.created = moment(response.data[i][4]).format(
            "DD. MM. YYYY HH:mm"
          );
          tempUser.updated = moment(response.data[i][5]).format(
            "DD. MM. YYYY HH:mm"
          );
          this.users.push(tempUser);
        }
      });
    },
    deleteUser(id) {
      this.axios
        .delete("/app/user.php", {
          data: {
            id: id,
          },
        })
        .then((response) => {
          if (response.data === 1) {
            this.snackbarText = "Uživatel úspěšně smazán.";
            this.snackbarColor = "green darken-2";
            this.snackbar = true;
            this.readUsers();
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
