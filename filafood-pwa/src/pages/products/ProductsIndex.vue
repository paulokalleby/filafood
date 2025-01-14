<script setup>
import { reactive, onMounted, onBeforeMount, ref } from "vue";
import PreLoader from "@/components/PreLoader.vue";
import dayjs from "@/plugins/dayjs";
import { useToast } from "vue-toastification";
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import _ from "lodash";
import http from "@/http";

onBeforeMount(() => {
  document.title = "Produtos";
});

onMounted(async () => {
  await fetchCategories();
  await fetchProducts(1);
});

const toast = useToast();
const itemIdToDelete = ref(null);
const deleteModal = ref(null);

const state = reactive({
  products: {
    data: {},
    meta: {
      last_page: 0,
      current_page: 1,
      total: 0,
      per_page: 8,
    },
  },
  categories: [],
  search: {
    name: "",
    category: "",
    status: "",
  },
  status: [
    {
      name: "Todos",
      value: "",
    },
    {
      name: "Ativos",
      value: 1,
    },
    {
      name: "Inativos",
      value: 0,
    },
  ],
  loading: false,
});

const doResearch = _.debounce(async () => {
  fetchProducts(1);
}, 500);

const clearSearch = () => {
  state.search = {
    name: "",
    category: "",
    status: "",
  };
  fetchProducts(1);
};

const fetchCategories = async () => {
  try {
    const response = await http.get("/categories?active=1");
    state.categories = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao carregar categorias");
  }
};

const fetchProducts = async (page = 1) => {
  state.loading = true;
  try {
    const response = await http.get(
      `/products?paginate=${state.products.meta.per_page}&page=${page}&name=${state.search.name}&category_id=${state.search.category}&active=${state.search.status}`
    );
    state.products = response.data;
  } catch (error) {
    toast.error("Erro ao buscar registros");
  } finally {
    state.loading = false;
  }
};

// Abrir modal para deletar produto
const showDeleteModal = (id) => {
  itemIdToDelete.value = id;
  deleteModal.value.openModal();
};

//Excluir produto
const handlerDelete = async (id) => {
  try {
    const response = await http.delete("/products/" + id);
    clearSearch();
    toast.success("Registro excluido com sucesso!");
  } catch (error) {
    toast.error("Erro ao excluir registro");
  }
};
</script>

<template>
  <v-responsive>
    <PreLoader :show="state.loading" />

    <ConfirmDeleteModal
      :itemId="itemIdToDelete"
      ref="deleteModal"
      @confirm-delete="handlerDelete"
    />

    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">Produtos</h1>
      </v-col>
    </v-row>

    <v-row class="mb-4">
      <v-col cols="12" md="3" class="py-0">
        <v-text-field
          v-model="state.search.name"
          @keyup="doResearch"
          label="Pesquisar"
          variant="outlined"
          prepend-inner-icon="mdi-magnify"
          density="compact"
        >
        </v-text-field>
      </v-col>

      <v-col cols="12" md="2" class="py-0">
        <v-select
          v-model="state.search.category"
          @update:model-value="doResearch"
          label="Categorias"
          :items="state.categories"
          item-title="name"
          item-value="id"
          variant="outlined"
          density="compact"
        >
        </v-select>
      </v-col>

      <v-col cols="12" md="2" class="py-0">
        <v-select
          v-model="state.search.status"
          @update:model-value="doResearch"
          label="Status"
          :items="state.status"
          item-title="name"
          item-value="value"
          variant="outlined"
          density="compact"
        >
        </v-select>
      </v-col>

      <v-col cols="12" md="5" class="py-0">
        <v-btn variant="tonal" color="primary" icon="mdi-refresh" @click="clearSearch">
        </v-btn>

        <v-btn
          v-can="'products.index'"
          icon="mdi-plus"
          :to="{ name: 'products.create' }"
          color="primary"
          variant="flat"
          class="float-end"
        >
        </v-btn>
      </v-col>
    </v-row>

    <div v-if="state.products.data.length <= 0" class="text-center mt-3">
      <p>Nenhum registro encontrado</p>
    </div>

    <v-table v-else density="default" class="mt-3">
      <thead>
        <tr>
          <th class="text-left">
            <b>Imagem</b>
          </th>
          <th class="text-left">
            <b>Nome</b>
          </th>
          <th class="text-left">
            <b>Categoria</b>
          </th>
          <th class="text-left">
            <b>Atualizado</b>
          </th>
          <th class="text-left">
            <b>Status</b>
          </th>
          <th class="text-right"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in state.products.data" :key="product.id">
          <td>
            <v-img
              v-if="product.image"
              :aspect-ratio="1"
              class="bg-white my-2"
              :src="product.image"
              width="38"
              cover
              rounded
            ></v-img>
            <v-img
              v-else
              :aspect-ratio="1"
              class="bg-white my-2"
              src="./../../assets/img/placeholder.png"
              width="38"
              cover
              rounded
            ></v-img>
          </td>
          <td>{{ product.name }}</td>
          <td>{{ product.category.name }}</td>
          <td>{{ dayjs(product.updated).fromNow() }}</td>
          <td>
            <v-chip
              :color="product.active ? 'green' : 'red'"
              :text="product.active ? 'Ativo' : 'Inativo'"
              size="x-small"
              label
            ></v-chip>
          </td>
          <td class="text-right">
            <v-btn
              v-can="'products.show'"
              :to="{ name: 'products.show', params: { id: product.id } }"
              variant="tonal"
              color="primary"
              size="x-small"
              icon="mdi-eye"
            >
            </v-btn>
            <v-btn
              class="ms-2"
              v-can="'products.update'"
              :to="{ name: 'products.edit', params: { id: product.id } }"
              variant="tonal"
              color="primary"
              size="x-small"
              icon="mdi-pencil"
            >
            </v-btn>
            <v-btn
              class="ms-2"
              @click="showDeleteModal(product.id)"
              v-can="'products.destroy'"
              variant="tonal"
              color="primary"
              size="x-small"
              icon="mdi-trash-can"
            ></v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>

    <div class="text-center mt-3">
      <v-pagination
        v-if="state.products.meta.total > state.products.meta.per_page"
        @update:model-value="fetchProducts"
        v-model="state.products.meta.current_page"
        :length="state.products.meta.last_page"
        :total-visible="state.products.meta.per_page"
        prev-icon="mdi-menu-left"
        next-icon="mdi-menu-right"
        size="small"
      >
      </v-pagination>
    </div>
  </v-responsive>
</template>
