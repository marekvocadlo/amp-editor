<template>
  <v-container v-if="getUser.email" fluid>
    <v-layout row wrap justify-center>
      <v-flex xs6 mt-10>
        <h1 class="mb-10">Postup pro úspěšné rozeslání AMP e-mailu</h1>
        <h2 class="mb-5">Registrace u společnosti Google</h2>
        <div>
          <ol>
            <li>
              Kontrola požadavků dle
              <a
                class="help-link"
                href="https://developers.google.com/gmail/ampemail/register#registration_guidelines"
                target="_blank"
                >Google guidelines</a
              >
            </li>
            <li>
              Poslat skutečný produkční email z produkčních serverů se správně
              nastavenými DNS záznamy (SPF, DKIM, DMARC) na adresu
              <a
                class="help-link"
                href="mailto:ampforemail.whitelisting@gmail.com"
                >ampforemail.whitelisting@gmail.com</a
              ><br />* V případě, že budete registrační email posílat z tohoto
              nástroje, je potřeba nastavit DNS záznamy dle
              <a
                class="help-link"
                href="https://www.emailkampane.cz/cs/podpora/blog/zlepseni-dorucitelnosti-nastaveni-domeny/"
                >tohoto návodu</a
              >.
            </li>
            <li>
              Vyplnit
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on"
                    ><a
                      class="help-link"
                      href="https://forms.gle/fDqLE1gh4wMB6BeQ6"
                      target="_blank"
                      >registrační formulář</a
                    >.
                  </span>
                </template>
                <span>Standardně schválení trvá 5 pracovních dní.</span>
              </v-tooltip>
              Následně společnost schválí nebo zamítne žádost o autorizaci.
            </li>
          </ol>
        </div>
        <h2 class="mt-10 mb-5">Registrace u společnosti Yahoo</h2>
        <div>
          <ol>
            <li>
              Kontrola požadavků dle
              <a
                class="help-link"
                href="https://developers.google.com/gmail/ampemail/register#registration_guidelines"
                target="_blank"
              >
                Google guidelines
              </a>
            </li>
            <li>
              Poslat skutečný produkční email z produkčních serverů se správně
              nastavenými DNS záznamy (SPF, DKIM, DMARC) na adresu
              <a class="help-link" href="mailto:ampverification@yahoo.com">
                ampverification@yahoo.com </a
              ><br />* V případě, že budete registrační email posílat z tohoto
              nástroje, je potřeba nastavit DNS záznamy dle
              <a
                class="help-link"
                href="https://www.emailkampane.cz/cs/podpora/blog/zlepseni-dorucitelnosti-nastaveni-domeny/"
                >tohoto návodu</a
              >.
            </li>
            <li>
              Vyplnit
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <a
                      class="help-link"
                      href="https://forms.gle/fDqLE1gh4wMB6BeQ6"
                      target="_blank"
                    >
                      registrační formulář
                    </a>
                    .
                  </span>
                </template>
                <span>Standardně schválení trvá 5 pracovních dní.</span>
              </v-tooltip>
              Následně společnost schválí nebo zamítne žádost o autorizaci.
            </li>
          </ol>
        </div>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
export default {
  name: "Help",

  components: {},

  data: () => ({
    //
  }),
  created() {
    this.$store.dispatch("getUser");
  },
  computed: {
    getUser() {
      return this.$store.state.user;
    },
  },
};
</script>
<style scoped>
li {
  margin-bottom: 10px;
}
.help-link {
  color: #1976d2 !important;
  text-decoration: underline !important;
}
</style>
