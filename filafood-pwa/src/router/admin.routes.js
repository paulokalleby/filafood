export default {
  component: () => import("@/layouts/DefaultLayout.vue"),
  redirect: { name: "home.index" },
  children: [
    {
      path: "/",
      name: "home.index",
      component: () => import("@/pages/home/HomeIndex.vue"),
    },

    {
      path: "/categories",
      name: "categories.index",
      component: () => import("@/pages/categories/CategoriesIndex.vue"),
      meta: { permission: "categories.index" },
    },
    {
      path: "/categories/create",
      name: "categories.create",
      component: () => import("@/pages/categories/CategoriesCreate.vue"),
      meta: { permission: "categories.store" },
    },
    {
      path: "/categories/:id/edit",
      name: "categories.edit",
      component: () => import("@/pages/categories/CategoriesEdit.vue"),
      meta: { permission: "categories.update" },
      props: true,
    },
    {
      path: "/categories/:id/show",
      name: "categories.show",
      component: () => import("@/pages/categories/CategoriesShow.vue"),
      meta: { permission: "categories.show" },
      props: true,
    },

    {
      path: "/products",
      name: "products.index",
      component: () => import("@/pages/products/ProductsIndex.vue"),
      meta: { permission: "products.index" },
    },
    {
      path: "/products/create",
      name: "products.create",
      component: () => import("@/pages/products/ProductsCreate.vue"),
      meta: { permission: "products.store" },
    },
    {
      path: "/products/:id/edit",
      name: "products.edit",
      component: () => import("@/pages/products/ProductsEdit.vue"),
      meta: { permission: "products.update" },
      props: true,
    },
    {
      path: "/products/:id/show",
      name: "products.show",
      component: () => import("@/pages/products/ProductsShow.vue"),
      meta: { permission: "products.show" },
      props: true,
    },

    {
      path: "/roles",
      name: "roles.index",
      component: () => import("@/pages/roles/RolesIndex.vue"),
      meta: { permission: "roles.index" },
    },
    {
      path: "/roles/create",
      name: "roles.create",
      component: () => import("@/pages/roles/RolesCreate.vue"),
      meta: { permission: "roles.store" },
    },
    {
      path: "/roles/:id/edit",
      name: "roles.edit",
      component: () => import("@/pages/roles/RolesEdit.vue"),
      meta: { permission: "roles.update" },
      props: true,
    },
    {
      path: "/roles/:id/show",
      name: "roles.show",
      component: () => import("@/pages/roles/RolesShow.vue"),
      meta: { permission: "roles.show" },
      props: true,
    },

    {
      path: "/users",
      name: "users.index",
      component: () => import("@/pages/users/UsersIndex.vue"),
      meta: { permission: "users.index" },
    },
    {
      path: "/users/create",
      name: "users.create",
      component: () => import("@/pages/users/UsersCreate.vue"),
      meta: { permission: "users.store" },
    },
    {
      path: "/users/:id/edit",
      name: "users.edit",
      component: () => import("@/pages/users/UsersEdit.vue"),
      meta: { permission: "users.update" },
      props: true,
    },
    {
      path: "/users/:id/show",
      name: "users.show",
      component: () => import("@/pages/users/UsersShow.vue"),
      meta: { permission: "users.show" },
      props: true,
    },

    {
      path: "/profile",
      name: "profile",
      component: () => import("@/pages/auth/Profile.vue"),
    },
  ],
  meta: { auth: true },
};
