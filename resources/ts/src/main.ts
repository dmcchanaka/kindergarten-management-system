import { createApp } from 'vue';
import { createPinia } from "pinia";

import App from './App.vue';

import router from "./router";
import ApiService from "@/core/services/ApiService";
import i18n from "@/core/plugins/i118n";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
library.add(fas, far, fab)

// Import Pusher
import Pusher from 'pusher-js';

// Define Pusher keys and cluster
const pusherAppKey = '95992be99b2cbbede7a4';
const pusherCluster = 'ap2';

// Create Pusher instance
const pusher = new Pusher(pusherAppKey, {
    cluster: pusherCluster,
});

const app = createApp(App);
app.use(createPinia());

app.use(router);
app.component("fa", FontAwesomeIcon)

// Make Pusher instance available globally
app.provide('pusher', pusher);

ApiService.init(app);
app.use(i18n);

app.mount("#app");