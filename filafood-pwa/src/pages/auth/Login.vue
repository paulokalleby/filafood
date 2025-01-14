<script setup>
import { useAuth } from "@/store/auth.js";
import { useRouter } from "vue-router";
import { onBeforeMount, reactive } from "vue";
import { useToast } from "vue-toastification";
import useVuelidate from "@vuelidate/core";
import loginRules from "@/validations/auth/login.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Entrar"));

const toast = useToast();
const auth = useAuth();
const router = useRouter();
const state = reactive({
  data: {
    email: "",
    password: "",
    device: "webapp",
  },
  passwordFieldType: "password",
  loading: false,
});

const v$ = useVuelidate(loginRules, state);

const handlerLogin = async () => {
  if (!(await v$.value.$validate())) return;
  state.loading = true;
  try {
    const response = await http.post("/auth/login", state.data);
    auth.setToken(response.data.token);
    auth.setUser(response.data.data);
    toast.success(`Olá ${response.data.data.name}, bem vindo!`);
    router.push({ name: "home.index" });
  } catch (error) {
    toast.error(error?.response?.data?.message || "Falha no login");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <v-form @submit.prevent="handlerLogin">
    <v-row class="d-flex align-center justify-center">
      <v-col cols="" md="4" class="px-6 py-6">
        <v-row>
          <v-col cols="12" class="text-start pb-4 text-start">
            <h1>Login</h1>
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
              Entrar
            </v-btn>
          </v-col>
          <v-col>
            <v-btn :to="{ name: 'auth.register' }" variant="plain"> Registre-se </v-btn> |
            <v-btn :to="{ name: 'auth.recover-password' }" variant="plain">
              Esqueci a senha
            </v-btn>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-form>
</template>
