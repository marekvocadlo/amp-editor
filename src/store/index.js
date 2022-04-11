import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    user: {
      name: "",
      surname: "",
    },
  },
  mutations: {
    GET_NAME(state, user) {
      state.user.name = user[2];
      state.user.surname = user[3];
    },
  },
  actions: {
    getUser({ commit }) {
      axios.get("https://ampeditor.dev/verify.php").then((response) => {
        commit("GET_NAME", response.data);
      });
    },
  },
  modules: {},
});
