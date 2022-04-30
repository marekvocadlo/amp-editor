<template>
  <div v-if="getUser.email" class="editor">
    <v-container class="container-editor">
      <v-row no-gutters>
        <v-col class="text-center py-5" cols="12" sm="6" md="8">
          <div class="card-email">
            <!-- Component logo -->
            <div
              v-show="component.logo.used"
              id="componentLogo"
              class="component"
              @click="componentLogoDisplay"
              :style="{
                paddingTop: component.logo.paddingTop + 'px',
                paddingBottom: component.logo.paddingBottom + 'px',
                paddingLeft: component.logo.paddingLeft + 'px',
                paddingRight: component.logo.paddingRight + 'px',
                textAlign: component.logo.align,
              }"
            >
              <img
                :src="component.logo.src"
                alt=""
                :width="component.logo.width"
                :height="component.logo.height"
              />
            </div>
            <!-- Component carousel -->
            <div
              v-show="component.carousel.used"
              id="componentCarousel"
              class="component"
              @click="componentCarouselDisplay"
            >
              <v-card
                :width="component.carousel.width"
                style="margin: 0 auto"
                tile
                elevation="0"
              >
                <v-carousel :height="component.carousel.height" hide-delimiters>
                  <v-carousel-item
                    v-for="(item, i) in component.carousel.img"
                    :key="i"
                    :src="item.src"
                  ></v-carousel-item>
                </v-carousel>
              </v-card>
            </div>
            <!-- Component title -->
            <div
              v-show="component.title.used"
              id="componentTitle"
              class="component"
              :style="{
                textAlign: component.title.align,
                paddingTop: component.title.paddingTop + 'px',
                paddingBottom: component.title.paddingBottom + 'px',
                paddingLeft: component.title.paddingLeft + 'px',
                paddingRight: component.title.paddingRight + 'px',
                color: component.title.color,
                fontSize: component.title.font_size + 'px',
                lineHeight: component.title.line_height + 'px',
              }"
              @click="componentTitleDisplay"
            >
              {{ component.title.text }}
            </div>
            <!-- Component text -->
            <div
              v-show="component.text.used"
              id="componentText"
              class="component"
              :style="{
                textAlign: component.text.align,
                paddingTop: component.text.paddingTop + 'px',
                paddingRight: component.text.paddingRight + 'px',
                paddingBottom: component.text.paddingBottom + 'px',
                paddingLeft: component.text.paddingLeft + 'px',
                color: component.text.color,
                fontSize: component.text.font_size + 'px',
                lineHeight: component.text.line_height + 'px',
              }"
              @click="componentTextDisplay"
            >
              {{ component.text.text }}
            </div>
            <!-- Component accordion -->
            <div
              v-show="component.accordion.used"
              id="componentAccordion"
              class="component"
              @click="componentAccordionDisplay"
            >
              <div
                :style="{
                  textAlign: component.accordion.align,
                  width: 600 + 'px',
                  fontSize: component.accordion.font_size + 'px',
                  lineHeight: component.accordion.line_height + 'px',
                  marginLeft: 'auto',
                  marginRight: 'auto',
                  paddingBottom: 30 + 'px',
                }"
              >
                <v-expansion-panels accordion>
                  <v-expansion-panel
                    v-for="(item, i) in component.accordion.section"
                    :key="i"
                  >
                    <v-expansion-panel-header
                      :style="{ color: component.accordion.color }"
                    >
                      {{ item.title }}</v-expansion-panel-header
                    >
                    <v-expansion-panel-content
                      :style="{ color: component.accordion.color }"
                    >
                      {{ item.text }}
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>
              </div>
            </div>
            <!-- Component timeago -->
            <div
              v-show="component.timeago.used"
              id="componentTimeago"
              class="component"
              :style="{
                textAlign: component.timeago.align,
                paddingTop: component.timeago.paddingTop + 'px',
                paddingRight: component.timeago.paddingRight + 'px',
                paddingBottom: component.timeago.paddingBottom + 'px',
                paddingLeft: component.timeago.paddingLeft + 'px',
                color: component.timeago.color,
                fontSize: component.timeago.font_size + 'px',
                lineHeight: component.timeago.line_height + 'px',
              }"
              @click="componentTimeagoDisplay"
            >
              {{ component.timeago.date }} {{ component.timeago.time }}
            </div>
          </div>
        </v-col>
        <!-- Settings -->
        <v-col class="pt-5" cols="6" md="4">
          <v-card class="pa-5 editor-settings" shaped>
            <v-row no-gutters class="justify-space-between align-center mb-5">
              <v-col cols="3">
                <h3>Nastavení</h3>
              </v-col>
              <v-col cols="9" class="text-right">
                <v-btn class="mr-3" color="primary" @click="updateUserTemplate"
                  >Uložit</v-btn
                >
                <v-btn
                  class="mr-3"
                  color="primary"
                  :href="ampURL"
                  target="_blank"
                  >Náhled</v-btn
                >
                <v-btn color="secondary" @click="closeTemplate">Zavřít</v-btn>
              </v-col>
            </v-row>
            <v-divider></v-divider>
            <!-- Settings general -->
            <v-form id="general" class="mt-5" ref="form" :style="{}">
              <h4 class="mb-3">Dostupné komponenty</h4>
              <v-row>
                <v-col cols="6">
                  <v-switch
                    v-model="component.logo.used"
                    label="Logo"
                  ></v-switch>
                  <v-switch
                    v-model="component.title.used"
                    label="Nadpis"
                  ></v-switch>
                  <v-switch
                    v-model="component.text.used"
                    label="Text"
                  ></v-switch>
                </v-col>
                <v-col cols="6">
                  <v-switch
                    v-model="component.carousel.used"
                    label="Galerie obrázků"
                  ></v-switch>
                  <v-switch
                    v-model="component.accordion.used"
                    label="Skládaný seznam"
                  ></v-switch>
                  <v-switch
                    v-model="component.timeago.used"
                    label="Časovač"
                  ></v-switch>
                </v-col>
              </v-row>
            </v-form>
            <v-divider></v-divider>
            <h4
              class="mt-5"
              :style="{
                display: introDisplay,
              }"
            >
              Úpravy začněte kliknutím na komponentu...
            </h4>
            <!-- Settings logo -->
            <v-form
              id="logo"
              class="mt-5"
              ref="form"
              :style="{
                display: component.logo.display,
              }"
            >
              <h4 class="mb-3">Logo</h4>
              <v-radio-group
                v-model="component.logo.align"
                row
                label="Pozice loga: "
              >
                <v-radio label="Vlevo" value="left"></v-radio>
                <v-radio label="Na střed" value="center"></v-radio>
                <v-radio label="Vpravo" value="right"></v-radio>
              </v-radio-group>
              <v-text-field
                v-model="component.logo.src"
                type="text"
                label="URL loga"
              ></v-text-field>
              <v-text-field
                v-model="component.logo.alt"
                type="text"
                label="Alternativní text"
              ></v-text-field>
              <v-row>
                <v-col cols="6"
                  ><v-text-field
                    v-model="component.logo.width"
                    type="number"
                    label="Šířka loga (px)"
                  ></v-text-field
                ></v-col>
                <v-col cols="6"
                  ><v-text-field
                    v-model="component.logo.height"
                    type="number"
                    label="Výška loga (px)"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-row>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.logo.paddingTop"
                    label="Horní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.logo.paddingRight"
                    label="Pravé odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.logo.paddingBottom"
                    label="Spodní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.logo.paddingLeft"
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
                display: component.carousel.display,
              }"
            >
              <h4 class="mb-3">Galerie obrázků</h4>
              <v-row>
                <v-col cols="6"
                  ><v-text-field
                    v-model="component.carousel.width"
                    type="number"
                    label="Šířka (px)"
                  ></v-text-field
                ></v-col>
                <v-col cols="6"
                  ><v-text-field
                    v-model="component.carousel.height"
                    type="number"
                    label="Výška (px)"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-switch
                v-model="component.carousel.loop"
                label="Opakování obrázků"
              ></v-switch>
              <v-text-field
                v-for="(item, i) in component.carousel.img"
                :key="i"
                :src="item.src"
                type="text"
                v-model="component.carousel.img[i].src"
                label="URL obrázku"
              ></v-text-field>
              <v-btn
                small
                class="mr-3 mb-5"
                color="primary"
                @click="componentCarouselAddImg"
                >Přidat obrázek</v-btn
              >
              <v-btn
                v-if="component.carousel.img.length > 1"
                small
                class="mr-3 mb-5 white--text"
                color="red"
                @click="componentCarouselRemoveImg"
                >Odebrat obrázek</v-btn
              >
            </v-form>
            <!-- Settings title -->
            <v-form
              id="title"
              class="mt-5"
              ref="form"
              :style="{
                display: component.title.display,
              }"
            >
              <h4 class="mb-3">Nadpis</h4>
              <v-text-field
                v-model="component.title.text"
                label="Text"
              ></v-text-field>
              <v-radio-group
                v-model="component.title.align"
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
                    v-model="component.title.paddingTop"
                    label="Horní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.title.paddingRight"
                    label="Pravé odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.title.paddingBottom"
                    label="Spodní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.title.paddingLeft"
                    label="Levé odsazení"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.title.font_size"
                    label="Velikost fontu"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.title.line_height"
                    label="Výška řádku"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="color"
                    v-model="component.title.color"
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
                display: component.text.display,
              }"
            >
              <h4 class="mb-3">Textový blok</h4>
              <v-textarea
                v-model="component.text.text"
                label="Text"
              ></v-textarea>
              <v-radio-group
                v-model="component.text.align"
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
                    v-model="component.text.paddingTop"
                    label="Horní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.text.paddingRight"
                    label="Pravé odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.text.paddingBottom"
                    label="Spodní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.text.paddingLeft"
                    label="Levé odsazení"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.text.font_size"
                    label="Velikost fontu"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.text.line_height"
                    label="Výška řádku"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="color"
                    v-model="component.text.color"
                    label="Barva textu"
                    height="30"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-form>
            <!-- Settings accordion -->
            <v-form
              id="accordion"
              class="mt-5"
              ref="form"
              :style="{
                display: component.accordion.display,
              }"
            >
              <h4 class="mb-3">Skládaný seznam</h4>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.accordion.font_size"
                    label="Velikost fontu obsahu"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.accordion.line_height"
                    label="Výška řádku obsahu"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="color"
                    v-model="component.accordion.color"
                    label="Barva textu"
                    height="30"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-switch
                v-model="component.accordion.animate"
                label="Animace při rozbalování"
              ></v-switch>
              <v-switch
                v-model="component.accordion.expandSingleSection"
                label="Rozbalená jen 1 sekce"
              ></v-switch>
              <v-text-field
                v-for="(item, i) in component.accordion.section"
                :key="i"
                :src="item.src"
                type="text"
                v-model="component.accordion.section[i].title"
                label="Nadpis sekce"
              ></v-text-field>
              <v-text-field
                v-for="(item, i) in component.accordion.section"
                :key="i"
                :src="item.src"
                type="text"
                v-model="component.accordion.section[i].text"
                label="Obsah sekce"
              ></v-text-field>
              <v-btn
                small
                class="mr-3 mb-5"
                color="primary"
                @click="componentAccordionAddSection"
                >Přidat sekci</v-btn
              >
              <v-btn
                v-if="component.accordion.section.length > 1"
                small
                class="mr-3 mb-5 white--text"
                color="red"
                @click="componentAccordionRemoveSection"
                >Odebrat sekci</v-btn
              >
            </v-form>
            <!-- Settings timeago -->
            <v-form
              id="text"
              class="mt-5"
              ref="form"
              :style="{
                display: component.timeago.display,
              }"
            >
              <h4 class="mb-3">Časovač</h4>
              <v-radio-group
                v-model="component.timeago.align"
                row
                label="Zarovnání textu"
              >
                <v-radio label="Vlevo" value="left"></v-radio>
                <v-radio label="Na střed" value="center"></v-radio>
                <v-radio label="Vpravo" value="right"></v-radio>
              </v-radio-group>
              <v-row>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.timeago.paddingTop"
                    label="Horní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.timeago.paddingRight"
                    label="Pravé odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.timeago.paddingBottom"
                    label="Spodní odsazení"
                  ></v-text-field
                ></v-col>
                <v-col cols="3"
                  ><v-text-field
                    type="number"
                    v-model="component.timeago.paddingLeft"
                    label="Levé odsazení"
                  ></v-text-field
                ></v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.timeago.font_size"
                    label="Velikost fontu"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="number"
                    v-model="component.timeago.line_height"
                    label="Výška řádku"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    type="color"
                    v-model="component.timeago.color"
                    label="Barva textu"
                    height="30"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    type="date"
                    v-model="component.timeago.date"
                    label="Datum události"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    type="time"
                    v-model="component.timeago.time"
                    label="Čas události"
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
    ampURL: "",
    component: {
      logo: {
        used: true,
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
        used: true,
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
        used: true,
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
        used: true,
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
      accordion: {
        used: false,
        display: "none",
        align: "left",
        font_size: "14",
        line_height: "22",
        color: "#00000",
        animate: true,
        expandSingleSection: true,
        section: [
          {
            title: "Nadpis sekce 1",
            text: "Obsah v sekci 1",
          },
        ],
      },
      timeago: {
        used: false,
        display: "none",
        align: "center",
        paddingTop: 0,
        paddingRight: 50,
        paddingBottom: 30,
        paddingLeft: 50,
        font_size: "22",
        line_height: "28",
        color: "#000000",
        date: "1995-07-30",
        time: "22:00",
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
    if (this.$route.query.new !== "true") {
      this.readUserTemplate();
    }
  },
  methods: {
    getTemplateID() {
      this.userTemplateId = this.$route.query.id;
      this.ampURL = "/files/index_amp" + this.$route.query.id + ".html";
    },
    readUserTemplate() {
      this.axios
        .get("/app/user_template.php?id=" + this.userTemplateId)
        .then((response) => {
          if (response.data !== null) {
            this.component = response.data;
          }
        });
    },
    updateUserTemplate() {
      this.axios
        .put("/app/user_template.php", {
          id: this.userTemplateId,
          settings: this.component,
        })
        .then((response) => {
          if (response.data === 1) {
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
      this.component.logo.display = "none";
      this.component.title.display = "none";
      this.component.text.display = "none";
      this.component.carousel.display = "none";
      this.component.accordion.display = "none";
      this.component.timeago.display = "none";
      this.updateUserTemplate();
      window.location.href = "/templates";
    },
    componentLogoDisplay() {
      this.introDisplay = "none";
      this.component.logo.display = "block";
      this.component.carousel.display = "none";
      this.component.title.display = "none";
      this.component.text.display = "none";
      this.component.accordion.display = "none";
      this.component.timeago.display = "none";
    },
    componentCarouselDisplay() {
      this.introDisplay = "none";
      this.component.logo.display = "none";
      this.component.carousel.display = "block";
      this.component.title.display = "none";
      this.component.text.display = "none";
      this.component.accordion.display = "none";
      this.component.timeago.display = "none";
    },
    componentCarouselAddImg() {
      let carouselImg = {};
      this.component.carousel.img.push(carouselImg);
    },
    componentCarouselRemoveImg() {
      this.component.carousel.img.pop();
    },
    componentTitleDisplay() {
      this.introDisplay = "none";
      this.component.logo.display = "none";
      this.component.carousel.display = "none";
      this.component.title.display = "block";
      this.component.text.display = "none";
      this.component.accordion.display = "none";
      this.component.timeago.display = "none";
    },
    componentTextDisplay() {
      this.introDisplay = "none";
      this.component.logo.display = "none";
      this.component.carousel.display = "none";
      this.component.title.display = "none";
      this.component.text.display = "block";
      this.component.timeago.display = "none";
    },
    componentAccordionDisplay() {
      this.introDisplay = "none";
      this.component.logo.display = "none";
      this.component.carousel.display = "none";
      this.component.title.display = "none";
      this.component.text.display = "none";
      this.component.accordion.display = "block";
      this.component.timeago.display = "none";
    },
    componentAccordionAddSection() {
      let accordionSection = {};
      this.component.accordion.section.push(accordionSection);
    },
    componentAccordionRemoveSection() {
      this.component.accordion.section.pop();
    },
    componentTimeagoDisplay() {
      this.introDisplay = "none";
      this.component.logo.display = "none";
      this.component.carousel.display = "none";
      this.component.title.display = "none";
      this.component.text.display = "none";
      this.component.accordion.display = "none";
      this.component.timeago.display = "block";
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
.v-input--selection-controls {
  margin-top: 0;
}
</style>
