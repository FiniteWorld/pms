/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import $ from "jquery";

window.Vue = require("vue");
import { Form, HasError, AlertError } from "vform";
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

import moment from "moment";
import swal from "sweetalert2";
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

Vue.component("pagination", require("laravel-vue-pagination"));

import VueRouter from "vue-router";
Vue.use(VueRouter);

import BootstrapVue from "bootstrap-vue";
import "bootstrap-vue/dist/bootstrap-vue.css";
import BProgress from "bootstrap-vue/es/components/progress/progress";
import BProgressBar from "bootstrap-vue/es/components/progress/progress-bar";
Vue.component("b-progress-bar", BProgressBar);
Vue.component("b-progress", BProgress);

import VueProgressBar from "vue-progressbar";
Vue.use(VueProgressBar, {
    color: "rgb(143,255,199)",
    failedColor: "red",
    height: "10px"
});

let routes = [
    {
        path: "/dashboard",
        component: require("./components/Dashboard.vue").default
    },
    {
        path: "/users",
        component: require("./components/Users.vue").default
    },
    {
        path: "/profile",
        component: require("./components/Profile.vue").default
    },
    {
        path: "/clients",
        component: require("./components/Clients.vue").default
    },
    {
        path: "/projects",
        component: require("./components/Projects.vue").default
    },
    {
        path: "/team",
        component: require("./components/Team.vue").default
    },
    {
        path: "/myprojects",
        component: require("./components/Member_project.vue").default
    }

    // {
    //     path: "*",
    //     component: require("./components/NotFound.vue").default
    // }
];
const router = new VueRouter({
    mode: "history",
    routes
});

Vue.filter("upText", function(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});
Vue.filter("dateFormat", function(created) {
    return moment(created).format("MM/DD/YYYY");
});

let Fire = new Vue();

window.Fire = Fire;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import VueChatScroll from "vue-chat-scroll";
import Echo from "laravel-echo";
import Axios from "axios";
Vue.use(VueChatScroll);
Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue")
);
Vue.component("message", require("./components/Message.vue").default);
Vue.component("not-found", require("./components/NotFound.vue"));
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router,
    data: {
        search: "",
        message: "",
        chat: {
            message: [],
            user: [],
            color: []
        }
    },
    methods: {
        searchit() {
            //console.log("searching..");
            Fire.$emit("searching");
        }
    }
});
