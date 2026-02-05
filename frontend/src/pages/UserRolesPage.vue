<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { apiGetUserRoles, getErrorMessage } from "../lib/api";

type Role = { id: number; code: string; name: string };

const route = useRoute();
const router = useRouter();

const userId = Number(route.params.id);

const roles = ref<Role[]>([]);
const loading = ref(false);
const error = ref("");

function extractArray(res: any): any[] {
  const candidates = [
    res?.data?.roles,
    res?.data?.items?.data,
    res?.data?.items,
    res?.data?.data,
    res?.data,
    res?.items?.data,
    res?.items,
    res?.roles?.data,
    res?.roles,
    res,
  ];
  for (const c of candidates) {
    if (Array.isArray(c)) return c;
  }
  return [];
}

async function fetchUserRoles() {
  loading.value = true;
  error.value = "";
  try {
    const res = await apiGetUserRoles(userId);
    roles.value = extractArray(res) as Role[];
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
    roles.value = [];
  } finally {
    loading.value = false;
  }
}

onMounted(fetchUserRoles);
</script>

<template>
  <div class="content-card">
    <div class="header-row">
      <div class="title">User Roles</div>
      <button class="btn-danger" style="background:#cfcfcf;" @click="router.back()">Back</button>
    </div>

    <div v-if="error" class="error-box">{{ error }}</div>

    <table class="table">
      <thead>
        <tr>
          <th style="width:80px;">No</th>
          <th style="width:220px;">Code</th>
          <th>Name</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="loading"><td colspan="3" style="padding:14px;">Loading...</td></tr>
        <tr v-else-if="roles.length === 0"><td colspan="3" style="padding:14px;">No data</td></tr>
        <tr v-else v-for="(r, idx) in roles" :key="r.id">
          <td>{{ idx + 1 }}</td>
          <td>{{ r.code }}</td>
          <td>{{ r.name }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.content-card{
  background:#fff;
  border:1px solid var(--border);
  border-radius: 14px;
  padding: 18px;
}
.header-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom: 14px;
}
.title{ font-size: 32px; font-weight: 800; }
.error-box{ margin: 10px 0; padding:10px; border:1px solid var(--border); border-radius: 10px; }
</style>
