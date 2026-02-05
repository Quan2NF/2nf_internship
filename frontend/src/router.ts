import { createRouter, createWebHistory } from "vue-router";

export default createRouter({
  history: createWebHistory(),
  routes: [
    // AUTH
    { path: "/auth/login", component: () => import("./pages/LoginPage.vue") },
    { path: "/auth/forgot-password", component: () => import("./pages/ForgotPasswordPage.vue") },
    { path: "/auth/reset-password/:token", component: () => import("./pages/ResetPasswordPage.vue") },

    // APP (UI)
    { path: "/", redirect: "/app/users" },
    { path: "/app/users", component: () => import("./pages/UsersPage.vue") },

    // stub để sidebar không lỗi
    { path: "/app/projects", component: { template: "<div>Projects</div>" } },
    { path: "/app/tasks", component: { template: "<div>Tasks</div>" } },
    { path: "/app/work-logs", component: { template: "<div>Work Logs</div>" } },
    { path: "/app/performance", component: { template: "<div>Performance</div>" } },
    { path: "/app/positions", component: { template: "<div>Positions</div>" } },
    {
  path: "/app/roles",
  name: "roles",
  component: () => import("./pages/RolesPage.vue"),
},
{
  path: "/app/users/:id/roles",
  name: "user-roles",
  component: () => import("./pages/UserRolesPage.vue"),
},

    // fallback
    { path: "/:pathMatch(.*)*", redirect: "/app/users" },
  ],
});
