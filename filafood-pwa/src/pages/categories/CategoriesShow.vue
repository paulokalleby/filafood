<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import http from "@/http";

import dayjs from "@/plugins/dayjs";

onBeforeMount(() => (document.title = "Detalhes do Categoria"));

onMounted(async () => {
  await fetchCategory();
});

const toast = useToast();
const props = defineProps({ id: String });

const state = reactive({
  data: {
    name: "",
    count_users: 0,
    permissions: [],
    active: false,
  },
  updating: false,
});

const fetchCategory = async () => {
  state.loading = true;
  try {
    const response = await http.get("/categories/" + props.id);
    state.data = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar categoria");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">Detalhes do Categoria</h1>
      </v-col>
    </v-row>
    <v-card class="border mb-3" :loading="state.loading" variant="outlined">
      <v-list lines="one">
        <v-list-item title="Nome:" :subtitle="state.data.name"></v-list-item>
        <v-list-item
          title="Status:"
          :subtitle="state.data.active ? 'Ativo' : 'Inativo'"
        ></v-list-item>
        <v-list-item
          title="Atualizado:"
          :subtitle="dayjs(state.data.updated).fromNow()"
        ></v-list-item>
      </v-list>
    </v-card>
    <v-row>
      <v-col cols="12">
        <v-btn
          v-can="'categories.index'"
          :to="{ name: 'categories.index' }"
          color="primary"
          variant="tonal"
          class="float-start"
        >
          Voltar
        </v-btn>
      </v-col>
    </v-row>
  </v-responsive>
</template>
