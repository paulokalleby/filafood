import { URL, fileURLToPath } from "node:url";
import vuetify, { transformAssetUrls } from "vite-plugin-vuetify";

import { VitePWA } from "vite-plugin-pwa";
// Utilities
import { defineConfig } from "vite";
// Plugins
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue({
      template: { transformAssetUrls },
    }),
    // https://github.com/vuetifyjs/vuetify-loader/tree/next/packages/vite-plugin
    vuetify({
      autoImport: true,
    }),
    VitePWA({
      registerType: "autoUpdate",
    }),
  ],
  define: { "process.env": {} },
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
    extensions: [".js", ".json", ".jsx", ".mjs", ".ts", ".tsx", ".vue"],
  },
  server: {
    port: 3001,
    host: "0.0.0.0",
    watch: {
      usePolling: true,
    },
  },
});
