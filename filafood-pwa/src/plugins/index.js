/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

import "vue-toastification/dist/index.css";
import "../assets/css/app.css";

import Toast from "vue-toastification";
import VueTheMask from "vue-the-mask";
import { createPinia } from "pinia";
import { directiveCan } from "./helpers";
// Plugins
import { loadFonts } from "./webfontloader";
import router from "../router";
import vuetify from "./vuetify";

export function registerPlugins(app) {
  loadFonts();
  app
    .directive("can", directiveCan)
    .use(vuetify)
    .use(router)
    .use(createPinia())
    .use(VueTheMask)
    .use(Toast, { hideProgressBar: false });
}
