import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    user: {
      email: "",
      name: "",
      surname: "",
      created_at: "",
      updated_at: "",
      initials: "",
    },
  },
  mutations: {
    GET_NAME(state, user) {
      state.user.email = user[1];
      state.user.name = user[2];
      state.user.surname = user[3];
      state.user.created_at = user[4];
      state.user.updated_at = user[5];
      state.user.initials = user[2].substring(0, 1) + user[3].substring(0, 1);
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
