<template>
  <div v-if="getUser.email" class="editor">
    <v-container class="container-editor">
      <v-row no-gutters>
        <v-col class="text-center py-5" cols="12" sm="6" md="8">
          <div class="card-email">
            <!-- Component logo -->
            <div
              id="galleryLogo"
              class="component"
              @click="galleryLogoDisplay"
              :style="{
                paddingTop: gallery.logo.paddingTop + 'px',
                paddingBottom: gallery.logo.paddingBottom + 'px',
                paddingLeft: gallery.logo.paddingLeft + 'px',
                paddingRight: gallery.logo.paddingRight + 'px',
                textAlign: gallery.logo.align,
              }"
            >
              <img
                :src="gallery.logo.src"
                alt=""
                :width="gallery.logo.width"
                :height="gallery.logo.height"
              />
            </div>
            <!-- Component carousel -->
            <div
              id="galleryCarousel"
              class="component"
              @click="galleryCarouselDisplay"
            >
              <v-card
                :width="gallery.carousel.width"
                style="margin: 0 auto"
                tile
                elevation="0"
              >
                <v-carousel :height="gallery.carousel.height" hide-delimiters>
                  <v-carousel-item
                    v-for="(item, i) in gallery.carousel.img"
                    :key="i"
                    :src="item.src"
                  ></v-carousel-item>
                </v-carousel>
              </v-card>
            </div>
            <!-- Component title -->
            <div
              id="galleryTitle"
              class="component"
              :style="{
                textAlign: gallery.title.align,
                paddingTop: gallery.title.paddingTop + 'px',
                paddingBottom: gallery.title.paddingBottom + 'px',
                paddingLeft: gallery.title.paddingLeft + 'px',
                paddingRight: gallery.title.paddingRight + 'px',
                color: gallery.title.color,
                fontSize: gallery.title.font_size + 'px',
                lineHeight: gallery.title.line_height + 'px',
              }"
              @click="galleryTitleDisplay"
            >
              {{ gallery.title.text }}
            </div>
            <!-- Component text -->
            <div
              id="galleryText"
              class="component"
              :style="{
                textAlign: gallery.text.align,
                paddingTop: gallery.text.paddingTop + 'px',
                paddingRight: gallery.text.paddingRight + 'px',
                paddingBottom: gallery.text.paddingBottom + 'px',
                paddingLeft: gallery.text.paddingLeft + 'px',
                color: gallery.text.color,
                fontSize: gallery.text.font_size + 'px',
                lineHeight: gallery.text.line_height + 'px',
              }"
              @click="galleryTextDisplay"
            >
              {{ gallery.text.text }}
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
            <h3
              class="mt-10"
              :style="{
                display: introDisplay,
              }"
            >
              Začněte kliknutí do obsahu...
            </h3>
            <!-- Settings logo -->
            <v-form
              id="logo"
              class="mt-5"
              ref="form"
              :style="{
                display: gallery.logo.display,
              }"
            >
              <h4 class="mb-3">Logo</h4>
              <v-radio-group
                v-model="gallery.logo.align"
                row
                label="Pozice loga: "
              >
                <v-radio label="Vlevo" value="left"></v-radio>
                <v-radio label="Na střed" value="center"></v-radio>
                <v-radio label="Vpravo" value="right"></v-radio>
              </v-radio-group>
              <v-text-field
                v-model="gallery.logo.src"
                type="text"
                label="URL loga"
              ></v-text-field>
              <v-text-field
                v-model="gallery.logo.alt"
                type="text"
                label="Alternativní text"
              ></v-text-field>
              <v-row>
                <v-col cols="6"
                  ><v-text-field
                    v-model="gallery.logo.width"
                    type="number"
                    label="Šířka loga (px)"
                  ></v-text-field
                ></v-col>
                <v-col cols="6"
                  ><v-text-field
                    v-model="gallery.logo.height"
                    type="number"
                    label="Výška loga (px)"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-row>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.logo.paddingTop"
                    label="Horní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.logo.paddingRight"
                    label="Pravé odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.logo.paddingBottom"
                    label="Spodní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.logo.paddingLeft"
                    label="Levé odsazení"
                  ></v-text-field
                ></v-col>
              </v-row>
            </v-form>
            <!-- Settings carousel -->
            <v-form
              id="carousel"
              class="mt-5"
              ref="form"
              :style="{
                display: gallery.carousel.display,
              }"
            >
              <h4 class="mb-3">Carousel</h4>
              <v-row>
                <v-col cols="6"
                  ><v-text-field
                    v-model="gallery.carousel.width"
                    type="number"
                    label="Šířka (px)"
                  ></v-text-field
                ></v-col>
                <v-col cols="6"
                  ><v-text-field
                    v-model="gallery.carousel.height"
                    type="number"
                    label="Výška (px)"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-switch
                v-model="gallery.carousel.loop"
                label="Opakování obrázků"
              ></v-switch>
              <v-text-field
                v-for="(item, i) in gallery.carousel.img"
                :key="i"
                :src="item.src"
                type="text"
                v-model="gallery.carousel.img[i].src"
                label="URL obrázku"
              ></v-text-field>
              <v-btn
                small
                class="mr-3 mb-5"
                color="primary"
                @click="galleryCarouselAddImg"
                >Přidat obrázek</v-btn
              >
              <v-btn
                v-if="gallery.carousel.img.length > 1"
                small
                class="mr-3 mb-5 white--text"
                color="red"
                @click="galleryCarouselRemoveImg"
                >Odebrat obrázek</v-btn
              >
            </v-form>
            <!-- Settings title -->
            <v-form
              id="title"
              class="mt-5"
              ref="form"
              :style="{
                display: gallery.title.display,
              }"
            >
              <h4 class="mb-3">Nadpis</h4>
              <v-text-field
                v-model="gallery.title.text"
                label="Text"
              ></v-text-field>
              <v-radio-group
                v-model="gallery.title.align"
                row
                label="Zarovnání nadpisu"
              >
                <v-radio label="Vlevo" value="left"></v-radio>
                <v-radio label="Na střed" value="center"></v-radio>
                <v-radio label="Vpravo" value="right"></v-radio>
              </v-radio-group>
              <v-row>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.title.paddingTop"
                    label="Horní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.title.paddingRight"
                    label="Pravé odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.title.paddingBottom"
                    label="Spodní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.title.paddingLeft"
                    label="Levé odsazení"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="gallery.title.font_size"
                    label="Velikost fontu"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="gallery.title.line_height"
                    label="Výška řádku"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="color"
                    v-model="gallery.title.color"
                    label="Barva nadpisu"
                    height="30"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-form>
            <!-- Settings text -->
            <v-form
              id="text"
              class="mt-5"
              ref="form"
              :style="{
                display: gallery.text.display,
              }"
            >
              <h4 class="mb-3">Textový blok</h4>
              <v-textarea v-model="gallery.text.text" label="Text"></v-textarea>
              <v-radio-group
                v-model="gallery.text.align"
                row
                label="Zarovnání textu"
              >
                <v-radio label="Vlevo" value="left"></v-radio>
                <v-radio label="Na střed" value="center"></v-radio>
                <v-radio label="Vpravo" value="right"></v-radio>
                <v-radio label="Do bloku" value="justify"></v-radio>
              </v-radio-group>
              <v-row>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.text.paddingTop"
                    label="Horní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.text.paddingRight"
                    label="Pravé odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.text.paddingBottom"
                    label="Spodní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="gallery.text.paddingLeft"
                    label="Levé odsazení"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="gallery.text.font_size"
                    label="Velikost fontu"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="gallery.text.line_height"
                    label="Výška řádku"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="color"
                    v-model="gallery.text.color"
                    label="Barva textu"
                    height="30"
                  ></v-text-field>
                </v-col>
              </v-row>
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
            Zavřít
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
    introDisplay: "block",
    gallery: {
      logo: {
        display: "none",
        paddingTop: 10,
        paddingRight: 50,
        paddingBottom: 10,
        paddingLeft: 50,
        align: "center",
        width: 150,
        height: 150,
        alt: "Logo",
        src:
          "https://img.freepik.com/free-vector/illustration-circle-stamp-banner-vector_53876-27183.jpg?t=st=1650813257~exp=1650813857~hmac=cee7b6217f8190ead609263d99e2ef872c2b29d02c6fb5f6fe54964d0d138481&w=826",
      },
      carousel: {
        display: "none",
        width: 600,
        height: 200,
        autoplay: "",
        delay: "",
        loop: true,
        img: [
          {
            src: "https://picsum.photos/600/200?random=1",
          },
        ],
      },
      title: {
        display: "none",
        align: "center",
        paddingTop: 30,
        paddingRight: 50,
        paddingBottom: 30,
        paddingLeft: 50,
        text: "Galerie obrázků",
        font_size: "22",
        line_height: "28",
        color: "#000000",
      },
      text: {
        display: "none",
        align: "justify",
        paddingTop: 0,
        paddingRight: 50,
        paddingBottom: 30,
        paddingLeft: 50,
        text:
          "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.",
        font_size: "14",
        line_height: "22",
        color: "#000000",
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
    closeTemplate() {
      this.updateUserTemplate();
      window.location.href = "/templates";
    },
    galleryLogoDisplay() {
      this.introDisplay = "none";
      this.gallery.logo.display = "block";
      this.gallery.carousel.display = "none";
      this.gallery.title.display = "none";
      this.gallery.text.display = "none";
    },
    galleryCarouselDisplay() {
      this.introDisplay = "none";
      this.gallery.logo.display = "none";
      this.gallery.carousel.display = "block";
      this.gallery.title.display = "none";
      this.gallery.text.display = "none";
    },
    galleryCarouselAddImg() {
      let carouselImg = {};
      this.gallery.carousel.img.push(carouselImg);
    },
    galleryCarouselRemoveImg() {
      this.gallery.carousel.img.pop();
    },
    galleryTitleDisplay() {
      this.introDisplay = "none";
      this.gallery.logo.display = "none";
      this.gallery.carousel.display = "none";
      this.gallery.title.display = "block";
      this.gallery.text.display = "none";
    },
    galleryTextDisplay() {
      this.introDisplay = "none";
      this.gallery.logo.display = "none";
      this.gallery.carousel.display = "none";
      this.gallery.title.display = "none";
      this.gallery.text.display = "block";
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
  min-height: calc(100vh - 110px);
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
</style>
