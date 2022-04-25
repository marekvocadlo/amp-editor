import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    user: {
      email: "",
      name: "",
      created_at: "",
      updated_at: "",
      admin: "",
    },
  },
  mutations: {
    GET_NAME(state, user) {
      state.user.email = user[1];
      state.user.name = user[2];
      state.user.created_at = user[3];
      state.user.updated_at = user[4];
      state.user.admin = user[6];
    },
  },
  actions: {
    getUser({ commit }) {
      axios.get("/app/user.php").then((response) => {
        commit("GET_NAME", response.data);
      });
    },
  },
  modules: {},
});
