<template>
  <div v-if="getUser.email" class="editor">
    <v-container class="container-editor">
      <v-row no-gutters>
        <v-col class="text-center py-5" cols="12" sm="6" md="8">
          <div class="card-email">
            <!-- Component carousel -->
            <div
              id="cCarouselComponent"
              class="component"
              @click="displayCCarousel"
            >
              <v-card
                :width="gallery.cCarousel.width"
                style="margin: 0 auto"
                tile
                elevation="0"
              >
                <v-carousel :height="gallery.cCarousel.height" hide-delimiters>
                  <v-carousel-item
                    v-for="(item, i) in gallery.cCarousel.img"
                    :key="i"
                    :src="item.src"
                  ></v-carousel-item>
                </v-carousel>
              </v-card>
            </div>
            <!-- Component text -->
            <div
              id="cTextComponent"
              class="component"
              :style="{
                paddingTop: gallery.cText.padding + 'px',
                paddingBottom: gallery.cText.padding + 'px',
                color: gallery.cText.color,
                fontSize: gallery.cText.font_size + 'px',
                lineHeight: gallery.cText.line_height + 'px',
              }"
              @click="displayCText"
            >
              {{ gallery.cText.text }}
            </div>
          </div>
        </v-col>
        <!-- Settings -->
        <v-col class="pt-5" cols="6" md="4">
          <v-card class="pa-5 editor-settings" shaped>
            <v-row no-gutters class="justify-space-between align-center">
              <v-col>
                <h3>Nastavení</h3>
              </v-col>
              <v-col class="text-right">
                <v-btn class="mr-3" color="primary" @click="updateUserTemplate"
                  >Uložit</v-btn
                >
                <v-btn color="secondary" @click="closeTemplate">Zavřít</v-btn>
              </v-col>
            </v-row>
            <!-- Settings text -->
            <v-form
              id="cText"
              class="mt-5"
              ref="form"
              :style="{
                display: gallery.cText.display,
              }"
            >
              <h4>Textový blok</h4>
              <v-text-field
                v-model="gallery.cText.text"
                label="Text"
              ></v-text-field>
              <v-text-field
                type="number"
                v-model="gallery.cText.padding"
                label="Horní a spodní odsazení"
              ></v-text-field>
              <v-text-field
                type="number"
                v-model="gallery.cText.font_size"
                label="Velikost fontu"
              ></v-text-field>
              <v-text-field
                type="number"
                v-model="gallery.cText.line_height"
                label="Výška řádku"
              ></v-text-field>
              <v-text-field
                type="color"
                v-model="gallery.cText.color"
                label="Barva"
              ></v-text-field>
            </v-form>
            <!-- Settings carousel -->
            <v-form
              id="cCarousel"
              class="mt-5"
              ref="form"
              :style="{
                display: gallery.cCarousel.display,
              }"
            >
              <h4>Carousel</h4>
              <v-text-field
                v-model="gallery.cCarousel.width"
                type="number"
                label="Šířka"
              ></v-text-field>
              <v-text-field
                v-model="gallery.cCarousel.height"
                type="number"
                label="Výška"
              ></v-text-field>
              <v-switch
                v-model="gallery.cCarousel.loop"
                label="Opakování obrázků"
              ></v-switch>
              <v-text-field
                v-for="(item, i) in gallery.cCarousel.img"
                :key="i"
                :src="item.src"
                type="text"
                v-model="gallery.cCarousel.img[i].src"
                label="URL obrázku"
              ></v-text-field>
              <v-btn
                small
                class="mr-3 mb-5"
                color="primary"
                @click="addCarouselImg"
                >Přidat obrázek</v-btn
              >
              <v-btn
                v-if="gallery.cCarousel.img.length > 1"
                small
                class="mr-3 mb-5 white--text"
                color="red"
                @click="removeCarouselImg"
                >Odebrat obrázek</v-btn
              >
            </v-form>
          </v-card>
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
  </div>
</template>

<script>
export default {
  name: "Editor",
  data: () => ({
    userTemplateId: 0,
    galleryFromDB: {},
    gallery: {
      cText: {
        display: "none",
        padding: 40,
        text: "Komponenta text",
        font_size: "16",
        line_height: "22",
        color: "#000000",
      },
      cCarousel: {
        display: "block",
        width: 700,
        height: 200,
        autoplay: "",
        delay: "",
        loop: true,
        img: [
          {
            src: "https://picsum.photos/700/200?random=1",
          },
        ],
      },
    },
    snackbar: false,
    snackbar_text: "",
    snackbar_color: "red darken-2",
    timeout: 10000,
  }),
  created() {
    this.$store.dispatch("getUser");
    this.getTemplateID();
    this.readUserTemplate();
  },
  methods: {
    getTemplateID() {
      this.userTemplateId = this.$route.query.id;
    },
    readUserTemplate() {
      this.axios
        .get("/app/user_template.php?id=" + this.userTemplateId)
        .then((response) => {
          this.gallery = response.data;
        });
    },
    displayCCarousel() {
      this.gallery.cText.display = "none";
      this.gallery.cCarousel.display = "block";
    },
    addCarouselImg() {
      let carouselImg = {};
      this.gallery.cCarousel.img.push(carouselImg);
    },
    removeCarouselImg() {
      this.gallery.cCarousel.img.pop();
    },
    displayCText() {
      this.gallery.cCarousel.display = "none";
      this.gallery.cText.display = "block";
    },
    saveTemplate() {
      this.axios
        .post("https://ampeditor.dev/app/saveTemplate.php", {
          cText: this.cText,
          cCarousel: this.cCarousel,
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
    closeTemplate() {
      this.updateUserTemplate();
      window.location.href = "/templates";
    },
    updateUserTemplate() {
      this.axios
        .put("/app/user_template.php", {
          id: this.userTemplateId,
          settings: this.gallery,
        })
        .then((response) => {
          if (response.data === "Template saved") {
            this.snackbar_color = "green darken-2";
            this.snackbar_text = "Šablona úspěšně uložena.";
            this.snackbar = true;
          } else {
            this.snackbar_text = "Došlo k neočekávané chybě.";
            this.snackbar_color = "red darken-2";
            this.snackbar = true;
          }
        })
        .catch(function (error) {
          console.log(error);
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

<style scoped>
.editor {
  background-color: #f3f5f7;
  min-height: calc(100vh - 64px);
}
.editor-settings {
  min-height: calc(90vh - 64px);
}
.container {
  padding: 0;
  margin-right: unset;
}
.card-email {
  width: 700px;
  margin: 0 auto;
  background-color: #ffffff;
}
.component:hover {
  border: #1976d2 1px solid;
  cursor: pointer;
}
#cText,
#cCarousel {
  display: none;
}
</style>
