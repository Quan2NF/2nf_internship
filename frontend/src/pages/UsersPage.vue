<script setup lang="ts">
import { onMounted, ref } from "vue";
import { apiGetUsers, getErrorMessage } from "../lib/api";
import CreateUserModal from "../components/user/CreateUserModal.vue";
import EditUserModal from "../components/user/EditUserModal.vue";
import { apiDeleteUser } from "../lib/api";
const showCreate = ref(false);

type UserRow = {
  id: number;
  code?: string;
  name: string;
  email: string;
  phone_number: string;
  created_at?: string;
  is_active?: boolean;
};

const keyword = ref("");
const role = ref("");

const loading = ref(false);
const error = ref("");

const rows = ref<UserRow[]>([]);
const total = ref(0);

const page = ref(1);
const perPage = ref(10);

function normalize(res: any) {
  const payload = res?.data ?? res;

  // backend của bạn: { items: [...] }
  if (Array.isArray(payload?.items)) {
    rows.value = payload.items;
    total.value = payload.items.length;
    return;
  }

  rows.value = [];
  total.value = 0;
}

async function fetchUsers() {
  loading.value = true;
  error.value = "";
  try {
    const res = await apiGetUsers({
      keyword: keyword.value || undefined,
      role: role.value || undefined,
      page: page.value,
      per_page: perPage.value,
    });
    normalize(res);
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  } finally {
    loading.value = false;
  }
}

const showEdit = ref(false);
const editingUser = ref<any>(null);

function openEdit(u: any) {
  editingUser.value = u;
  showEdit.value = true;
}

async function onDelete(u: any) {
  const ok = window.confirm(`Delete user "${u.name}" ?`);
  if (!ok) return;

  try {
    await apiDeleteUser(u.id);
    await fetchUsers();
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  }
}

function onSearch() {
  page.value = 1;
  fetchUsers();
}

function go(p: number) {
  if (p < 1) return;
  page.value = p;
  fetchUsers();
}

onMounted(fetchUsers);
</script>

<template>
  <div>
    <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px;">
      <div class="page-title">Users</div>
      <button class="btn-create" @click="showCreate = true">+ Create User</button>
        <CreateUserModal
        v-if="showCreate"
        @close="showCreate = false"
        @created="() => { showCreate = false; fetchUsers(); }"
        />
    </div>

    <div class="card">
      <div class="filter-row">
        <div style="font-weight:600;">Filter</div>

        <input class="input" v-model="keyword" placeholder="Search by keyword..." />

        <select class="input" v-model="role">
          <option value="">All roles</option>
          <option value="admin">Admin</option>
          <option value="pm">PM</option>
          <option value="staff">Staff</option>
        </select>

        <button class="btn-danger" @click="onSearch">Search</button>
      </div>

      <div v-if="error" class="error" style="text-align:left; margin-top:10px;">
        {{ error }}
      </div>

      <table class="table">
        <thead>
          <tr>
            <th style="width:60px;">No</th>
            <th style="width:100px;">Code</th>
            <th>Name</th>
            <th>Email</th>
            <th style="width:160px;">Phone number</th>
            <th style="width:120px;">Join date</th>
            <th style="width:110px;">Status</th>
            <th style="width:120px;">Action</th>
          </tr>
        </thead>

        <tbody>
          <tr v-if="loading">
            <td colspan="8" style="padding:18px;">Loading...</td>
          </tr>

          <tr v-else-if="rows.length === 0">
            <td colspan="8" style="padding:18px;">No data</td>
          </tr>

          <tr v-else v-for="(u, idx) in rows" :key="u.id">
            <td>{{ (page - 1) * perPage + idx + 1 }}</td>
            <td>{{ u.code || `US-${String(u.id).padStart(2, "0")}` }}</td>
            <td>{{ u.name }}</td>
            <td>{{ u.email }}</td>
            <td>{{ u.phone_number || "-" }}</td>
            <td>{{ (u.created_at || "").slice(0, 10) || "-" }}</td>

            <td>
              <span :class="u.is_active === false ? 'status-inactive' : 'status-active'">
                {{ u.is_active === false ? "Inactive" : "Active" }}
              </span>
            </td>

            <td>
            <div class="actions">
                <span class="action" title="Edit" @click="openEdit(u)">✏️</span>
                <span class="action danger" title="Delete" @click="onDelete(u)">🗑️</span>
                <span class="action" title="Roles" @click="$router.push(`/app/users/${u.id}/roles`)">🧩</span>
            </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="paging">
        <div>Total {{ total }} items</div>

        <div class="paging__nums">
          <button class="paging__btn" @click="go(page - 1)">‹</button>

          <button class="paging__btn is-active">{{ page }}</button>

          <button class="paging__btn" @click="go(page + 1)">›</button>
        </div>

        <div style="display:flex; align-items:center; gap: 10px;">
          <select class="select" v-model.number="perPage" @change="onSearch">
            <option :value="10">10 / page</option>
            <option :value="20">20 / page</option>
            <option :value="50">50 / page</option>
          </select>
        </div>
      </div>
    </div>
  </div>
    <EditUserModal
    v-if="showEdit && editingUser"
    :user="editingUser"
    @close="showEdit = false"
    @updated="() => { showEdit = false; fetchUsers(); }"
    />
    
</template>
