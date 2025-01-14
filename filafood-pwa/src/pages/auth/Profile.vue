<script setup>
import { reactive, onBeforeMount, onMounted, ref, computed } from "vue";
import { useToast } from "vue-toastification";
import useVuelidate from "@vuelidate/core";
import { profileRules } from "@/validations/auth/profile.rules";
import { useAuth } from "@/store/auth";
import http from "@/http";

onBeforeMount(() => (document.title = "Meus Dados"));

onMounted(async () => {
  await fetchMe();
});

const auth = useAuth();

const toast = useToast();

const locked = ref(true);

const state = reactive({
  data: {
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
  },
  passwordFieldType: "password",
  confirmPasswordFieldType: "password",
  loading: false,
  updating: false,
});

const rules = computed(() => profileRules(state.data.password));
const v$ = useVuelidate(rules, state);

const toggleLocked = () => {
  locked.value = !locked.value;
};

const fetchMe = async () => {
  state.loading = true;
  try {
    const response = await http.get("/auth/me");
    state.data = response.data.data;
  } catch (error) {
    toast.error("Erro ao carregar os dados do usuário");
  } finally {
    state.loading = false;
  }
};

const handlerUpdate = async () => {
  if (!(await v$.value.$validate())) return;
  state.updating = true;
  try {
    const response = await http.put("/auth/profile", state.data);
    toast.success("Conta atualizada com sucesso!");
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao atualizar usuário");
  } finally {
    state.updating = false;
  }
};
</script>

<template>
  <v-responsive>
    <v-row>
      <v-col cols="12">
        <h1 class="mb-3">
          Meus Dados
          <v-btn
            variant="flat"
            :icon="locked ? 'mdi-lock' : 'mdi-lock-open'"
            class="float-end"
            @click.prevent="toggleLocked"
            :ripple="false"
          >
          </v-btn>
        </h1>
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
            :disabled="locked"
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
            :loading="state.loading"
            :disabled="locked"
          >
          </v-text-field>
        </v-col>

        <v-col cols="12" md="6" class="pb-0">
          <v-text-field
            v-model="state.data.password"
            :error-messages="v$.data.password.$errors.map((e) => e.$message)"
            @input="v$.data.password.$touch"
            @blur="v$.data.password.$touch"
            @click:append-inner="state.passwordFieldType = !state.passwordFieldType"
            :append-inner-icon="state.passwordFieldType ? 'mdi-eye' : 'mdi-eye-off'"
            :type="state.passwordFieldType ? 'password' : 'text'"
            label="Senha"
            density="compact"
            variant="outlined"
            :disabled="locked"
          />
        </v-col>

        <v-col cols="12" md="6" class="pb-0">
          <v-text-field
            v-model="state.data.confirmPassword"
            :error-messages="v$.data.confirmPassword.$errors.map((e) => e.$message)"
            @input="v$.data.confirmPassword.$touch"
            @blur="v$.data.confirmPassword.$touch"
            @click:append-inner="
              state.confirmPasswordFieldType = !state.confirmPasswordFieldType
            "
            :append-inner-icon="
              state.confirmPasswordFieldType ? 'mdi-eye' : 'mdi-eye-off'
            "
            :type="state.confirmPasswordFieldType ? 'password' : 'text'"
            label="Confirmar Senha"
            density="compact"
            variant="outlined"
            :disabled="locked"
          />
        </v-col>

        <v-col cols="12">
          <v-btn
            :loading="state.updating"
            type="submit"
            color="primary"
            variant="flat"
            class="float-start mr-2"
            :disabled="locked"
          >
            Alterar
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-responsive>
</template>
