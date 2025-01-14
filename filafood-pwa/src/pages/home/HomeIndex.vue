<script setup>
import { onBeforeMount } from "vue";
import { useAuth } from "@/store/auth.js";

const auth = useAuth();

onBeforeMount(() => {
  document.title = "Home";
});
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1>Home</h1>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="4">
        <v-card class="py-3 px-3 border">
          <v-card-title>
            <v-icon class="me-1 mb-1" icon="mdi-account" size="22"></v-icon>
            {{ auth.user.name }}
          </v-card-title>

          <v-card-text class="py-0">
            <p>{{ auth.user.email }}</p>
            <!-- <p>{{ auth.user.role_label }}</p> -->
          </v-card-text>

          <v-card-actions class="pb-0">
            <v-btn
              color="primary"
              variant="tonal"
              size="x-small"
              prepend-icon="mdi-power"
              text="Sair"
              @click.prevent="auth.logout"
            ></v-btn>
            <!--
            <v-btn
              color="primary"
              variant="tonal"
              size="x-small"
              prepend-icon="mdi-key-variant"
              text="Alterar Senha"
              :to="{ name: 'profile.index' }"
            ></v-btn>
            -->
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-responsive>
</template>
