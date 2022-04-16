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
    },
  },
  mutations: {
    GET_NAME(state, user) {
      state.user.email = user[1];
      state.user.name = user[2];
      state.user.surname = user[3];
      state.user.created_at = user[4];
      state.user.updated_at = user[5];
    },
  },
  actions: {
    getUser({ commit }) {
      axios
        .post("https://ampeditor.dev/app/user.php", {
          request: "readUser",
        })
        .then((response) => {
          commit("GET_NAME", response.data);
        });
    },
  },
  modules: {},
});
