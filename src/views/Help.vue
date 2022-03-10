<template>
  <v-container fluid>
    <v-layout row wrap justify-center>
      <v-flex xs6 mt-10>
        <h1 class="mb-10">Postup pro úspěšné rozeslání AMP e-mailu</h1>
        <h2 class="mb-5">Registrace u společnosti Google</h2>
        <div>
          <ol>
            <li>
              Kontrola požadavků dle
              <a
                href="https://developers.google.com/gmail/ampemail/register#registration_guidelines"
                target="_blank"
                >Google guidelines</a
              >
            </li>
            <li>
              Poslat skutečný produkční email z produkčních serverů se správně
              nastavenými DNS záznamy (SPF, DKIM, DMARC) na adresu
              <a href="mailto:ampforemail.whitelisting@gmail.com"
                >ampforemail.whitelisting@gmail.com</a
              >
            </li>
            <li>
              Vyplnit
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on"
                    ><a
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
                href="https://developers.google.com/gmail/ampemail/register#registration_guidelines"
                target="_blank"
              >
                Google guidelines
              </a>
            </li>
            <li>
              Poslat skutečný produkční email z produkčních serverů se správně
              nastavenými DNS záznamy (SPF, DKIM, DMARC) na adresu
              <a href="mailto:ampverification@yahoo.com">
                ampverification@yahoo.com
              </a>
            </li>
            <li>
              Vyplnit
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <a
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
  name: "About",

  components: {},

  data: () => ({
    //
  }),
};
</script>
<style scoped>
li {
  margin-bottom: 10px;
}
</style>
