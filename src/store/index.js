import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    user: 0,
  },
  mutations: {
    GET_NAME(state, id) {
      state.user = id;
    },
  },
  actions: {
    getUser({ commit }) {
      axios.get("https://ampeditor.dev/script/verify.php").then((response) => {
        commit("GET_NAME", response.data);
      });
    },
  },
  modules: {},
});
