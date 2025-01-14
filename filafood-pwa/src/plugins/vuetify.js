/**
 * plugins/vuetify.js
 *
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";

// Composables
import { createVuetify } from "vuetify";
import { pt } from "vuetify/locale";

// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
export default createVuetify({
  locale: {
    locale: "pt",
    messages: { pt },
  },
  theme: {
    themes: {
      light: {
        colors: {
          primary: "#FA896B",
          secondary: "#5CBBF6",
        },
      },
      dark: {
        colors: {
          primary: "#FA896B",
          secondary: "#5CBBF6",
        },
      },
    },
  },
});
