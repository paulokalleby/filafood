<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import useVuelidate from "@vuelidate/core";
import { roleRules } from "@/validations/role.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Cadastrar Papel"));

onMounted(async () => {
  await fetchResources();
});

const toast = useToast();

const router = useRouter();

const state = reactive({
  data: {
    name: "",
    permissions: [],
    active: true,
  },
  switch: false,
  resources: [],
  creating: false,
});

const v$ = useVuelidate(roleRules, state);

//Carregar Recursos e Permissões
const fetchResources = async () => {
  try {
    const response = await http.get("/resources");
    state.resources = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar recursos");
  }
};

// Cadastrar Papel
const handlerStore = async () => {
  if (!(await v$.value.$validate())) return;
  state.creating = true;
  try {
    const response = await http.post("/roles", state.data);
    toast.success("Papel cadastrado com sucesso!");
    router.push({ name: "roles.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao criar papel");
  } finally {
    state.creating = false;
  }
};
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">Cadastrar Papel</h1>
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

        <v-col cols="12" md="12">
          <v-row>
            <v-col
              cols="12"
              md="2"
              v-for="resource in state.resources"
              :key="resource.id"
            >
              <h4>{{ resource.name }}</h4>
              <div
                class="px-0 mb-3"
                v-for="permission in resource.permissions"
                :key="permission.id"
              >
                <v-checkbox
                  :ripple="false"
                  :value="permission.id"
                  v-model="state.data.permissions"
                  :id="'permission-' + permission.id"
                  :label="permission.name"
                  hide-details
                  color="primary"
                  density="compact"
                >
                </v-checkbox>
              </div>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12" md="3" class="py-0 ml-2">
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
            v-can="'roles.index'"
            :to="{ name: 'roles.index' }"
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
