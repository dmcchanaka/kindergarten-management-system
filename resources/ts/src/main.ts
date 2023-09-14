import { createApp } from 'vue';
import { createPinia } from "pinia";

import App from './App.vue';

import router from "./router";
import ApiService from "@/core/services/ApiService";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
library.add(fas, far, fab)

const app = createApp(App);
app.use(createPinia());

app.use(router);
app.component("fa", FontAwesomeIcon)

ApiService.init(app);
app.mount("#app");