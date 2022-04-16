<template>
  <v-col cols="auto" class="mx-3">
    <v-card class="mx-auto my-8 text-center" max-width="350" tile>
      <v-img contain height="200" :src="require('@/assets/' + img)"> </v-img>

      <v-card-title class="justify-center">{{ title }}</v-card-title>
      <v-card-text>
        <div>
          {{ text }}
        </div>
      </v-card-text>

      <v-divider class="mx-4"></v-divider>

      <v-card-actions class="pa-5 justify-center">
        <router-link class="mr-3" :to="btnHref">
          <v-btn small outlined color="primary">{{ btnText }} </v-btn>
        </router-link>
      </v-card-actions>
    </v-card>
  </v-col>
</template>

<script>
export default {
  name: "DashboardCard",
  data: () => ({}),
  props: ["img", "title", "text", "btnText", "btnHref", "btn2Text", "btn2Href"],
};
</script>

<style scoped></style>
