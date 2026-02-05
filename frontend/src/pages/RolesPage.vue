<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import {
  apiDeleteRole,
  apiGetRoles,
  getErrorMessage,
} from "../lib/api";
import CreateRoleModal from "../components/role/CreateRoleModal.vue";
import EditRoleModal from "../components/role/EditRoleModal.vue";

type Role = { id: number; code: string; name: string };

const roles = ref<Role[]>([]);
const keyword = ref("");
const loading = ref(false);
const error = ref("");

const showCreate = ref(false);
const showEdit = ref(false);
const editing = ref<Role | null>(null);

// ✅ Parse cực rộng: bắt hầu hết format Laravel/pagination
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
    res?.result?.items,
    res?.result?.data,
    res,
  ];
  for (const c of candidates) {
    if (Array.isArray(c)) return c;
  }
  return [];
}

async function fetchRoles() {
  loading.value = true;
  error.value = "";
  try {
    const res = await apiGetRoles();

    // BẬT khi cần debug:
    console.log("GET /roles raw:", res);

    roles.value = extractArray(res) as Role[];
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
    roles.value = [];
  } finally {
    loading.value = false;
  }
}

const filtered = computed(() => {
  const k = keyword.value.trim().toLowerCase();
  if (!k) return roles.value;
  return roles.value.filter((r) =>
    `${r.code} ${r.name}`.toLowerCase().includes(k)
  );
});

function openEdit(r: Role) {
  editing.value = r;
  showEdit.value = true;
}

async function removeRole(r: Role) {
  const ok = window.confirm(`Delete role "${r.code} - ${r.name}"?`);
  if (!ok) return;

  try {
    await apiDeleteRole(r.id);
    await fetchRoles();
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  }
}

onMounted(fetchRoles);
</script>

<template>
  <div class="content-card">
    <div class="header-row">
      <div class="title">Roles</div>
      <button class="btn-danger" @click="showCreate = true">+ Create Role</button>
    </div>

    <div class="filter-row">
      <div class="filter-label">Filter</div>
      <input class="input" v-model="keyword" placeholder="Search by keyword..." />
      <button class="btn-danger" @click="fetchRoles">Search</button>
    </div>

    <div v-if="error" class="error-box">{{ error }}</div>

    <table class="table">
      <thead>
        <tr>
          <th style="width:80px;">No</th>
          <th style="width:220px;">Code</th>
          <th>Name</th>
          <th style="width:140px;">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-if="loading">
          <td colspan="4" style="padding:14px;">Loading...</td>
        </tr>

        <tr v-else-if="filtered.length === 0">
          <td colspan="4" style="padding:14px;">No data</td>
        </tr>

        <tr v-else v-for="(r, idx) in filtered" :key="r.id">
          <td>{{ idx + 1 }}</td>
          <td>{{ r.code }}</td>
          <td>{{ r.name }}</td>
          <td>
            <div class="action-wrap">
              <span class="action" title="Edit" @click="openEdit(r)">✏️</span>
              <span class="action" title="Delete" @click="removeRole(r)">🗑️</span>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <CreateRoleModal
    v-if="showCreate"
    @close="showCreate = false"
    @created="() => { showCreate = false; fetchRoles(); }"
  />

  <EditRoleModal
    v-if="showEdit && editing"
    :role="editing"
    @close="showEdit = false"
    @updated="() => { showEdit = false; fetchRoles(); }"
  />
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
.filter-row{
  display:grid;
  grid-template-columns: 100px 1fr 120px;
  gap: 12px;
  align-items:center;
  margin-bottom: 14px;
  padding: 14px;
  border:1px solid var(--border);
  border-radius: 14px;
  background: #f8fbff;
}
.filter-label{ font-weight: 700; }
.action-wrap{ display:flex; gap: 10px; justify-content:center; }
.action{ cursor:pointer; }
.error-box{ margin: 10px 0; padding:10px; border:1px solid var(--border); border-radius: 10px; }
</style>
