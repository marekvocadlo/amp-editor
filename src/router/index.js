import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import About from "../views/About";
import Templates from "../views/Templates";
import Login from "../views/Login";
import Register from "../views/Register";
import Editor from "../views/Editor";
import Help from "../views/Help";
import Settings from "../views/Settings";
import Campaign from "../views/Campaign";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/about",
    name: "about",
    component: About,
  },
  {
    path: "/editor",
    name: "editor",
    component: Editor,
  },
  {
    path: "/templates",
    name: "templates",
    component: Templates,
  },
  {
    path: "/login",
    name: "login",
    component: Login,
  },
  {
    path: "/register",
    name: "register",
    component: Register,
  },
  {
    path: "/help",
    name: "help",
    component: Help,
  },
  {
    path: "/settings",
    name: "settings",
    component: Settings,
  },
  {
    path: "/campaign",
    name: "campaign",
    component: Campaign,
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
