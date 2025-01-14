<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import http from "@/http";

import dayjs from "@/plugins/dayjs";

onBeforeMount(() => (document.title = "Detalhes do Produto"));

onMounted(async () => {
  await fetchProduct();
});

const toast = useToast();
const props = defineProps({ id: String });

const state = reactive({
  data: {
    image: "",
    name: "",
    category: "",
    price: "",
    description: "",
    active: false,
  },
  updating: false,
});

const fetchProduct = async () => {
  state.loading = true;
  try {
    const response = await http.get("/products/" + props.id);
    state.data = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar produto");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">Detalhes do Produto</h1>
      </v-col>
    </v-row>
    <v-card class="border mb-3" :loading="state.loading" variant="outlined">
      <v-list lines="one">
        <v-img
          v-if="state.data.image"
          :aspect-ratio="1"
          class="bg-white my-2 ml-4"
          :src="state.data.image"
          width="72"
          cover
          rounded
        ></v-img>
        <v-img
          v-else
          :aspect-ratio="1"
          class="bg-white my-2 ml-4"
          src="./../../assets/img/placeholder.png"
          width="72"
          cover
          rounded
        ></v-img>
        <v-list-item title="Nome:" :subtitle="state.data.name"></v-list-item>
        <v-list-item
          title="Categoria:"
          :subtitle="state.data.category.name"
        ></v-list-item>
        <v-list-item title="Preço:">
          {{
            state.data.price.toLocaleString("pt-BR", {
              style: "currency",
              currency: "BRL",
            })
          }}
        </v-list-item>
        <v-list-item title="Descrição:" :subtitle="state.data.description"></v-list-item>
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
          v-can="'products.index'"
          :to="{ name: 'products.index' }"
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
