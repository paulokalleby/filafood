import { computed, ref } from "vue";

import { defineStore } from "pinia";
import http from "@/http";

export const useAuth = defineStore("auth", () => {
  const token = ref(localStorage.getItem("token"));
  const user = ref(JSON.parse(localStorage.getItem("user")));

  const setToken = (tokenValue) => {
    localStorage.setItem("token", tokenValue);
    token.value = tokenValue;
  };

  const setUser = (userValue) => {
    localStorage.setItem("user", JSON.stringify(userValue));
    user.value = userValue;
  };

  const checkToken = async () => {
    try {
      const { data } = await http.get("/auth/me");
      return data;
    } catch (error) {
      return false;
    }
  };

  async function logout() {
    try {
      const { response } = await http.post("/auth/logout");
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      token.value = "";
      user.value = "";
      window.location = "/auth/login";
    } catch (error) {
      return false;
    }
  }

  const isAuthenticated = computed(() => {
    return token.value && user.value;
  });

  return {
    token,
    user,
    setToken,
    setUser,
    checkToken,
    isAuthenticated,
    logout,
  };
});
