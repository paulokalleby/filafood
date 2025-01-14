<script setup>
import { ref } from "vue";
import { useAuth } from "@/store/auth.js";
import { useDark, useToggle } from "@vueuse/core";
import { useDisplay } from "vuetify";

const isDark = useDark();
const toggleDark = useToggle(isDark);
const auth = useAuth();
const isDrawerOpen = ref(true);
const { mobile } = useDisplay();
</script>

<template>
  <v-app :theme="isDark ? 'dark' : 'light'">
    <v-app-bar flat class="border-b" :color="isDark ? '' : 'primary'">
      <v-app-bar-nav-icon
        v-if="mobile"
        :ripple="false"
        @click="isDrawerOpen = !isDrawerOpen"
      >
      </v-app-bar-nav-icon>
      <v-app-bar-title>{{ auth.user.tenant.name }}</v-app-bar-title>
      <div class="pa-2">
        <v-btn
          :ripple="false"
          variant="text"
          @click="toggleDark()"
          :icon="isDark ? 'mdi-weather-night' : 'mdi-white-balance-sunny'"
        >
        </v-btn>
      </div>
    </v-app-bar>

    <v-navigation-drawer
      v-model="isDrawerOpen"
      :rail="!mobile"
      :expand-on-hover="!mobile"
    >
      <v-list nav>
        <v-list-item :ripple="false" prepend-icon="mdi-home" :to="{ name: 'home.index' }">
          Home
        </v-list-item>
        <v-list-item
          v-can="'categories.index'"
          :ripple="false"
          prepend-icon="mdi-layers"
          :to="{ name: 'categories.index' }"
        >
          Categorias
        </v-list-item>
        <v-list-item
          v-can="'products.index'"
          :ripple="false"
          prepend-icon="mdi-food"
          :to="{ name: 'products.index' }"
        >
          Produtos
        </v-list-item>
        <v-list-item
          v-can="'roles.index'"
          :ripple="false"
          prepend-icon="mdi-lock"
          :to="{ name: 'roles.index' }"
        >
          Papéis
        </v-list-item>
        <v-list-item
          v-can="'users.index'"
          :ripple="false"
          prepend-icon="mdi-account-group"
          :to="{ name: 'users.index' }"
        >
          Usuários
        </v-list-item>
      </v-list>
      <template v-slot:append>
        <v-list nav>
          <v-list-item prepend-icon="mdi-account" :to="{ name: 'profile' }">
            {{ auth.user.name }}
          </v-list-item>
          <v-list-item prepend-icon="mdi-power" @click.prevent="auth.logout">
            Sair
          </v-list-item>
        </v-list>
      </template>
    </v-navigation-drawer>

    <v-main>
      <v-container fluid class="px-lg-10 py-lg-6 px-6">
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>
