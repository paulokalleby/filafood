<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import useVuelidate from "@vuelidate/core";
import { useRouter } from "vue-router";
import { userStoreRules } from "@/validations/user.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Cadastrar Usuário"));

onMounted(async () => {
  await fetchRoles();
});

const toast = useToast();

const router = useRouter();

const state = reactive({
  data: {
    name: "",
    email: "",
    password: "",
    roles: [],
    active: true,
  },
  roles: [],
  creating: false,
});

const v$ = useVuelidate(userStoreRules, state);

// Carregar Papéis
const fetchRoles = async () => {
  try {
    const response = await http.get("/roles?active=1");
    state.roles = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar papéis");
  }
};

//Cadastrar Usuário
const handlerStore = async () => {
  if (!(await v$.value.$validate())) return;
  const dataToSend = {
    ...state.data,
    roles: state.data.roles.map((role) => role.id),
  };
  state.creating = true;
  try {
    const response = await http.post("/users", dataToSend);
    toast.success("Usuário cadastrado com sucesso!");
    router.push({ name: "users.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao criar usuário");
  } finally {
    state.creating = false;
  }
};
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">Cadastrar Usuário</h1>
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

        <v-col cols="12" md="6" class="pb-0">
          <v-text-field
            v-model="state.data.email"
            :error-messages="v$.data.email.$errors.map((e) => e.$message)"
            @input="v$.data.email.$touch"
            @blur="v$.data.email.$touch"
            label="Email"
            variant="outlined"
            density="compact"
          >
          </v-text-field>
        </v-col>

        <v-col cols="12" md="6" class="pb-0">
          <v-text-field
            v-model="state.data.password"
            :error-messages="v$.data.password.$errors.map((e) => e.$message)"
            @input="v$.data.password.$touch"
            @blur="v$.data.password.$touch"
            label="Senha"
            variant="outlined"
            density="compact"
            type="password"
          >
          </v-text-field>
        </v-col>

        <v-col cols="12" md="6" class="pb-0">
          <v-combobox
            v-model="state.data.roles"
            label="Papéis"
            :items="state.roles"
            item-title="name"
            item-value="id"
            clearable
            multiple
            variant="outlined"
            density="compact"
            chips
          ></v-combobox>
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
            v-can="'users.index'"
            :to="{ name: 'users.index' }"
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
