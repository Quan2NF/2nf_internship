<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { apiLogout, apiMe, getErrorMessage } from "./lib/api";

const route = useRoute();
const router = useRouter();

const isAuthPage = computed(() => route.path.startsWith("/auth/"));

const me = ref<{ name?: string; email?: string } | null>(null);
const meError = ref("");

async function loadMe() {
  meError.value = "";
  try {
    const res = await apiMe();
    // res dạng {message, data: {...}}
    me.value = res?.data ?? null;
  } catch (e: unknown) {
    meError.value = getErrorMessage(e);
    me.value = null;
  }
}

async function logout() {
  await apiLogout();
  me.value = null;
  router.push("/auth/login");
}

onMounted(() => {
  if (!isAuthPage.value) loadMe();
});
</script>

<template>
  <!-- AUTH: có logo to -->
  <template v-if="isAuthPage">
    <div class="app-shell">
      <header class="topbar">
        <div class="topbar__inner">
          <img class="topbar__logo" src="/redmine-logo.png" alt="REDMINE" />
        </div>
      </header>
      <main class="page">
        <router-view />
      </main>
    </div>
  </template>
  <template v-else>
    <div class="main">
      <aside class="sidebar">
        <div class="sidebar__brand">
          <img class="sidebar__logo" src="/redmine-logo.png" alt="REDMINE" />
          <div class="sidebar__tag">flexible project management</div>
        </div>

        <nav class="menu">
          <router-link class="menu__item" to="/app/projects">Projects</router-link>
          <router-link class="menu__item" to="/app/tasks">Tasks</router-link>
          <router-link class="menu__item" to="/app/work-logs">Work Logs</router-link>
          <router-link class="menu__item" to="/app/performance">Performance</router-link>
          <div class="menu__sep"></div>
          <router-link class="menu__item" to="/app/users">Users</router-link>
          <router-link class="menu__item" to="/app/positions">Positions</router-link>
          <router-link class="menu__item" to="/app/roles">Roles</router-link>
        </nav>
      </aside>

      <div class="content">
        <header class="topbar2">
          <div></div>

          <div class="topbar2__right">
            <button class="icon-btn" title="Notifications">🔔</button>

            <div class="profile">
              <div class="profile__name">{{ me?.name || "—" }}</div>
              <div class="profile__role">{{ me?.email || "" }}</div>
            </div>

            <div class="avatar">🙂</div>

            <button class="link-btn" @click="logout">Logout</button>
          </div>
        </header>

        <main class="content__inner">
          <router-view />
        </main>
      </div>
    </div>
  </template>
</template>
