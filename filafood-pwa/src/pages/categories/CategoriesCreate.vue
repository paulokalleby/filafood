<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import useVuelidate from "@vuelidate/core";
import { categoryRules } from "@/validations/category.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Cadastrar Categoria"));

const toast = useToast();

const router = useRouter();

const state = reactive({
  data: {
    name: "",
    active: true,
  },
  creating: false,
});

const v$ = useVuelidate(categoryRules, state);

// Cadastrar Categoria
const handlerStore = async () => {
  if (!(await v$.value.$validate())) return;
  state.creating = true;
  try {
    const response = await http.post("/categories", state.data);
    toast.success("Categoria cadastrada com sucesso!");
    router.push({ name: "categories.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao criar categoria");
  } finally {
    state.creating = false;
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

    <v-form @submit.prevent="handlerStore">
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
            :loading="state.creating"
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
