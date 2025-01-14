<script setup>
import { reactive, onBeforeMount, onMounted, computed } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import useVuelidate from "@vuelidate/core";
import { categoryRules } from "@/validations/category.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Editar Categoria"));

onMounted(async () => {
  await fetchRole();
});

const toast = useToast();
const router = useRouter();
const props = defineProps({ id: String });

const state = reactive({
  data: {
    name: "",
    active: false,
  },
  loading: false,
  updating: false,
});

const v$ = useVuelidate(categoryRules, state);

// Carregar Categoria
const fetchRole = async () => {
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

// Atualizar Categoria
const handlerUpdate = async () => {
  if (!(await v$.value.$validate())) return;
  state.updating = true;
  try {
    await http.put("/categories/" + props.id, state.data);
    toast.success("Categoria atualizada com sucesso!");
    router.push({ name: "categories.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao atualizar categoria");
  } finally {
    state.updating = false;
  }
};
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">Cadastrar Categoria</h1>
      </v-col>
    </v-row>

    <v-form @submit.prevent="handlerUpdate">
      <v-row>
        <v-col cols="12" md="6" class="pb-0">
          <v-text-field
            v-model="state.data.name"
            :error-messages="v$.data.name.$errors.map((e) => e.$message)"
            @input="v$.data.name.$touch"
            @blur="v$.data.name.$touch"
            label="Nome"
            variant="outlined"
            density="compact"
            :loading="state.loading"
          >
          </v-text-field>
        </v-col>

        <v-col cols="12" md="12" class="py-0 ml-2">
          <v-switch
            v-model="state.data.active"
            label="Ativo"
            hide-details
            color="primary"
            density="compact"
            :ripple="false"
            :flat="true"
          >
          </v-switch>
        </v-col>

        <v-col cols="12">
          <v-btn
            :loading="state.updating"
            type="submit"
            color="primary"
            variant="flat"
            class="float-start mr-2"
          >
            Salvar
          </v-btn>
          <v-btn
            v-can="'categories.index'"
            :to="{ name: 'categories.index' }"
            color="primary"
            variant="tonal"
            class="float-start"
          >
            Cancelar
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-responsive>
</template>
