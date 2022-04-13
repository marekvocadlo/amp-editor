<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex xs12 sm4 offset-sm4 mt-3>
        <h1 class="mb-5">Kontakty</h1>
        <v-btn class="mb-5" color="success">Přidat kontakty</v-btn>
      </v-flex>
      <v-flex xs12 sm4 offset-sm4 mt-5>
        <h2 class="mb-5">Skupiny kontaktů</h2>
        <v-simple-table dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Název skupiny</th>
                <th class="text-left">Počet kontaktů</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in contactGroups" :key="item.name">
                <td>{{ item.name }}</td>
                <td>{{ item.numberOfContacts }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: "Contacts",
  data: () => ({
    contactGroups: [
      {
        name: "Skupina kontaktů 1",
        numberOfContacts: 1,
      },
    ],
  }),
};
</script>

<style scoped></style>
