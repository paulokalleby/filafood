import { createRouter, createWebHistory } from "vue-router";

import adminRoutes from "./admin.routes";
import authRoutes from "./auth.routes";
import { hasPermission } from "@/plugins/helpers";
import { useAuth } from "@/store/auth.js";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  linkActiveClass: "active",
  linkExactActiveClass: "active",
  routes: [
    authRoutes,
    adminRoutes,
    // {
    //   path: "/:pathMatch(.*)*",
    //   component: () => import("@/pages/error/PageNotFound.vue"),
    // },
  ],
});

router.beforeEach(async (to, from, next) => {
  const auth = useAuth();
  if (to.meta?.auth) {
    if (auth.token && auth.user) {
      const isAuthenticated = await auth.checkToken();

      if (!isAuthenticated) return next({ name: "auth.login" });

      auth.setUser(isAuthenticated.data);
    } else {
      return next({ name: "auth.login" });
    }
  }

  if (to.meta?.permission) {
    if (!hasPermission(to.meta.permission)) {
      return next({ name: "home.index" });
    }
  }

  next();
});

export default router;
