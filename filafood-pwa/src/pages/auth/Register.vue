<script setup>
import { useRouter } from "vue-router";
import { onBeforeMount, reactive } from "vue";
import { useToast } from "vue-toastification";
import useVuelidate from "@vuelidate/core";
import registerRules from "@/validations/auth/register.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Registre-se"));

const toast = useToast();
const router = useRouter();
const state = reactive({
  data: {
    company: "",
    name: "",
    email: "",
    password: "",
  },
  passwordFieldType: "password",
  loading: false,
});

const v$ = useVuelidate(registerRules, state);

const handlerRegister = async () => {
  if (!(await v$.value.$validate())) return;
  state.loading = true;
  try {
    const response = await http.post("/auth/register", state.data);
    toast.success("Registro realizada com sucesso!");
    router.push({ name: "auth.login" });
  } catch (error) {
    toast.error(error?.response?.data?.message || "Falha ao realizar registro");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <v-form @submit.prevent="handlerRegister">
    <v-row class="d-flex align-center justify-center">
      <v-col cols="" md="4" class="px-6 py-6">
        <v-row>
          <v-col cols="12" class="text-start pb-4 text-start">
            <h1>Cadastre-se</h1>
          </v-col>
          <v-col cols="12" class="text-start pt-0 pb-2">
            <v-text-field
              v-model="state.data.company"
              :error-messages="v$.data.company.$errors.map((e) => e.$message)"
              @input="v$.data.company.$touch"
              @blur="v$.data.company.$touch"
              label="Empresa"
              density="compact"
              variant="outlined"
            />
          </v-col>
          <v-col cols="12" class="text-start pt-0 pb-2">
            <v-text-field
              v-model="state.data.name"
              :error-messages="v$.data.name.$errors.map((e) => e.$message)"
              @input="v$.data.name.$touch"
              @blur="v$.data.name.$touch"
              label="Nome"
              density="compact"
              variant="outlined"
            />
          </v-col>
          <v-col cols="12" class="text-start pt-0 pb-2">
            <v-text-field
              v-model="state.data.email"
              :error-messages="v$.data.email.$errors.map((e) => e.$message)"
              @input="v$.data.email.$touch"
              @blur="v$.data.email.$touch"
              label="Email"
              density="compact"
              variant="outlined"
            />
          </v-col>
          <v-col cols="12" class="text-start pt-0 pb-0">
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
            />
          </v-col>
          <v-col cols="12" class="text-start pt-0 pb-1">
            <v-btn
              type="submit"
              color="primary"
              flat
              block
              class="mb-3 float-start"
              :loading="state.loading"
            >
              Registrar
            </v-btn>
          </v-col>
          <v-col>
            <v-btn :to="{ name: 'auth.login' }" variant="plain"> Entrar </v-btn>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-form>
</template>
