import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import Templates from "../views/Templates";
import Login from "../views/Login";
import Register from "../views/Register";
import Editor from "../views/Editor";
import Help from "../views/Help";
import Settings from "../views/Settings";
import Campaigns from "../views/Campaigns";
import Contacts from "../views/Contacts";
import Send from "../views/Send";
import Users from "../views/Users";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
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
    path: "/campaigns",
    name: "campaigns",
    component: Campaigns,
  },
  {
    path: "/contacts",
    name: "contacts",
    component: Contacts,
  },
  {
    path: "/send",
    name: "send",
    component: Send,
  },
  {
    path: "/users",
    name: "users",
    component: Users,
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
