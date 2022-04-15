<template>
  <v-container class="grey lighten-5">
    <v-row no-gutters>
      <v-col cols="12" sm="6" md="8">
        <v-card class="pa-2" outlined tile>
          <h2>Obsah</h2>
          <div
            id="cTextComponent"
            :style="{
              color: cText.color,
              fontSize: cText.font_size + 'px',
              lineHeight: cText.line_height + 'px',
            }"
            @click="displaycText"
          >
            {{ cText.text }}
          </div>
          <div
            id="cTextSmallComponent"
            :style="{
              color: cTextSmall.color,
              fontSize: cTextSmall.font_size + 'px',
              lineHeight: cTextSmall.line_height + 'px',
            }"
            @click="displaycTextSmall"
          >
            {{ cTextSmall.text }}
          </div>
        </v-card>
      </v-col>
      <v-col cols="6" md="4">
        <v-card class="pa-2" outlined tile>
          <h2>Nastavení</h2>
          <v-form
            id="cText"
            ref="form"
            :style="{
              display: cText.display,
            }"
          >
            <v-text-field v-model="cText.text" label="Text"></v-text-field>
            <v-text-field
              type="number"
              v-model="cText.font_size"
              label="Velikost fontu"
            ></v-text-field>
            <v-text-field
              type="number"
              v-model="cText.line_height"
              label="Výška řádku"
            ></v-text-field>
            <v-text-field
              type="color"
              v-model="cText.color"
              label="Barva"
            ></v-text-field>
          </v-form>
          <v-form
            id="cTextSmall"
            ref="form"
            :style="{
              display: cTextSmall.display,
            }"
          >
            <v-text-field v-model="cTextSmall.text" label="Text"></v-text-field>
            <v-text-field
              type="number"
              v-model="cTextSmall.font_size"
              label="Velikost fontu"
            ></v-text-field>
            <v-text-field
              type="number"
              v-model="cTextSmall.line_height"
              label="Výška řádku"
            ></v-text-field>
            <v-text-field
              type="color"
              v-model="cTextSmall.color"
              label="Barva"
            ></v-text-field>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters>
      <v-col cols="12" sm="6" md="8">
        <v-btn color="primary" @click="saveTemplate">Uložit</v-btn>
      </v-col>
    </v-row>
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
  name: "Editor",
  data: () => ({
    templateAMP: ``,
    templateHTML: "",
    cText: {
      text: "Komponenta text",
      font_size: "16",
      line_height: "22",
      color: "#000000",
      display: "none",
    },
    cTextSmall: {
      text: "Komponenta text small",
      font_size: "12",
      line_height: "18",
      color: "#000000",
      display: "none",
    },
    carousel: {
      width: 0,
      height: 0,
      img: {
        src: "img/logo-blue.svg",
        width: 0,
      },
    },
  }),
  created() {
    //this.displayTemplate();
  },
  methods: {
    displaycText() {
      this.cTextSmall.display = "none";
      this.cText.display = "block";
    },
    displaycTextSmall() {
      this.cText.display = "none";
      this.cTextSmall.display = "block";
    },
    saveTemplate() {
      this.axios
        .post("https://ampeditor.dev/saveTemplate.php", {
          cText: this.cText,
        })
        .then((response) => {
          if (response.data === "Template saved") {
            this.snackbar_color = "green darken-2";
            this.snackbar_text = "Šablona úspěšně uložena.";
            this.snackbar = true;
          } else {
            this.snackbar_text = "Došlo k chybě.";
            this.snackbar = true;
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped>
#cText,
#cTextSmall {
  display: none;
}
#cTextComponent:hover,
#cTextSmallComponent:hover {
  border: #1976d2 1px solid;
  cursor: pointer;
}
</style>
