<script setup>
import { useDark, useToggle } from "@vueuse/core";

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>

<template>
  <v-app :theme="isDark ? 'dark' : 'light'">
    <v-app-bar flat class="border-b">
      <v-app-bar-title>Filafood</v-app-bar-title>
      <template v-slot:append>
        <v-btn
          variant="text"
          @click="toggleDark()"
          :icon="isDark ? 'mdi-weather-night' : 'mdi-white-balance-sunny'"
        >
        </v-btn>
      </template>
    </v-app-bar>

    <v-container class="fill-height">
      <v-responsive class="d-flex align-center text-center fill-height">
        <router-view />
      </v-responsive>
    </v-container>
  </v-app>
</template>
