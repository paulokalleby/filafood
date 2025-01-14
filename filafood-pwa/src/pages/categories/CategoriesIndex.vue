<script setup>
import { reactive, onMounted, onBeforeMount, ref } from "vue";
import PreLoader from "@/components/PreLoader.vue";
import dayjs from "@/plugins/dayjs";
import { useToast } from "vue-toastification";
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import _ from "lodash";
import http from "@/http";

onBeforeMount(() => {
  document.title = "Categorias";
});

onMounted(async () => {
  await fetchCategories(1);
});

const toast = useToast();
const itemIdToDelete = ref(null);
const deleteModal = ref(null);

const state = reactive({
  categories: {
    data: {},
    meta: {
      last_page: 0,
      current_page: 1,
      total: 0,
      per_page: 8,
    },
  },
  search: {
    name: "",
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
  fetchCategories(1);
}, 500);

const clearSearch = () => {
  state.search = {
    name: "",
    status: "",
  };
  fetchCategories(1);
};

// Carregar Categorias
const fetchCategories = async (page = 1) => {
  state.loading = true;
  try {
    const response = await http.get(
      `/categories?paginate=${state.categories.meta.per_page}&page=${page}&name=${state.search.name}&active=${state.search.status}`
    );
    state.categories = response.data;
  } catch (error) {
    toast.error("Erro ao buscar registros");
  } finally {
    state.loading = false;
  }
};

// Abrir modal para deletar categoria
const showDeleteModal = (id) => {
  itemIdToDelete.value = id;
  deleteModal.value.openModal();
};

//Excluir categoria
const handlerDelete = async (id) => {
  try {
    const response = await http.delete("/categories/" + id);
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
        <h1 class="mb-3">Categorias</h1>
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

      <v-col cols="12" md="7" class="py-0">
        <v-btn variant="tonal" color="primary" icon="mdi-refresh" @click="clearSearch">
        </v-btn>

        <v-btn
          v-can="'categories.index'"
          icon="mdi-plus"
          :to="{ name: 'categories.create' }"
          color="primary"
          variant="flat"
          class="float-end"
        >
        </v-btn>
      </v-col>
    </v-row>

    <div v-if="state.categories.data.length <= 0" class="text-center mt-3">
      <p>Nenhum registro encontrado</p>
    </div>

    <v-table v-else density="default" class="mt-3">
      <thead>
        <tr>
          <th class="text-left">
            <b>Nome</b>
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
        <tr v-for="category in state.categories.data" :key="category.id">
          <td>{{ category.name }}</td>
          <td>{{ dayjs(category.updated).fromNow() }}</td>
          <td>
            <v-chip
              :color="category.active ? 'green' : 'red'"
              :text="category.active ? 'Ativo' : 'Inativo'"
              size="x-small"
              label
            ></v-chip>
          </td>
          <td class="text-right">
            <v-btn
              v-can="'categories.show'"
              :to="{ name: 'categories.show', params: { id: category.id } }"
              variant="tonal"
              color="primary"
              size="x-small"
              icon="mdi-eye"
            >
            </v-btn>
            <v-btn
              class="ms-2"
              v-can="'categories.update'"
              :to="{ name: 'categories.edit', params: { id: category.id } }"
              variant="tonal"
              color="primary"
              size="x-small"
              icon="mdi-pencil"
            >
            </v-btn>
            <v-btn
              class="ms-2"
              @click="showDeleteModal(category.id)"
              v-can="'categories.destroy'"
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
        v-if="state.categories.meta.total > state.categories.meta.per_page"
        @update:model-value="fetchCategories"
        v-model="state.categories.meta.current_page"
        :length="state.categories.meta.last_page"
        :total-visible="state.categories.meta.per_page"
        prev-icon="mdi-menu-left"
        next-icon="mdi-menu-right"
        size="small"
      >
      </v-pagination>
    </div>
  </v-responsive>
</template>
