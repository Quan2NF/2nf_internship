import { createRouter, createWebHistory } from "vue-router";

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/auth/login", component: () => import("./pages/LoginPage.vue") },
    { path: "/auth/forgot-password", component: () => import("./pages/ForgotPasswordPage.vue") },
    { path: "/auth/reset-password/:token", component: () => import("./pages/ResetPasswordPage.vue") },

    // mặc định vào login
    { path: "/", redirect: "/auth/login" },
  ],
});
