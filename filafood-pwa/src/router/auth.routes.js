export default {
  path: "/auth",
  component: () => import("@/layouts/AuthLayout.vue"),
  redirect: { name: "auth.login" },
  children: [
    {
      path: "/auth/login",
      name: "auth.login",
      component: () => import("@/pages/auth/Login.vue"),
    },
    {
      path: "/auth/register",
      name: "auth.register",
      component: () => import("@/pages/auth/Register.vue"),
    },
    {
      path: "/auth/recover-password",
      name: "auth.recover-password",
      component: () => import("@/pages/auth/RecoverPassword.vue"),
    },
  ],
};
