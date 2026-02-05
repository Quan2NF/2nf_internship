<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { apiGetUsers, apiGetRoles, apiAssignMembers, getErrorMessage } from "../../lib/api";

/**
 * Props:
 * - projectId: current project id
 */
const props = defineProps<{
  projectId: number;
}>();

const emit = defineEmits<{
  (e: "close"): void;
  (e: "applied"): void;
}>();

type User = {
  id: number;
  name: string;
  email: string;
};

type Role = {
  id: number;
  code: string;
  name: string;
};

type SelectedMember = {
  user_id: number;
  role_id: number | null; // IMPORTANT: must not be undefined
};

const users = ref<User[]>([]);
const roles = ref<Role[]>([]);
const keyword = ref("");
const loading = ref(false);
const error = ref("");

const selected = ref<SelectedMember[]>([]);

/**
 * Extract array from many possible Laravel response shapes.
 */
function extractArray(res: any): any[] {
  const candidates = [
    res?.data?.items?.data,
    res?.data?.items,
    res?.data?.users,
    res?.data?.roles,
    res?.data?.data,
    res?.data,
    res?.items?.data,
    res?.items,
    res,
  ];
  for (const c of candidates) {
    if (Array.isArray(c)) return c;
  }
  return [];
}

async function loadData() {
  loading.value = true;
  error.value = "";
  try {
    const [uRes, rRes] = await Promise.all([apiGetUsers(), apiGetRoles()]);

    console.log("GET /users raw:", uRes);
    console.log("GET /roles raw:", rRes);

    users.value = (extractArray(uRes) as any[]).map((u) => ({
      id: Number(u.id),
      name: String(u.name ?? ""),
      email: String(u.email ?? ""),
    }));

    roles.value = (extractArray(rRes) as any[]).map((r) => ({
      id: Number(r.id),
      code: String(r.code ?? ""),
      name: String(r.name ?? ""),
    }));
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
    users.value = [];
    roles.value = [];
  } finally {
    loading.value = false;
  }
}

function toggleUser(u: User, checked: boolean) {
  if (checked) {
    // add if not exists
    const exists = selected.value.some((m) => m.user_id === u.id);
    if (!exists) selected.value.push({ user_id: u.id, role_id: null });
  } else {
    selected.value = selected.value.filter((m) => m.user_id !== u.id);
  }
}

function isChecked(userId: number) {
  return selected.value.some((m) => m.user_id === userId);
}

function selectedRoleId(userId: number) {
  return selected.value.find((m) => m.user_id === userId)?.role_id ?? null;
}

function setRole(userId: number, roleId: number | null) {
  const m = selected.value.find((x) => x.user_id === userId);
  if (!m) return;
  m.role_id = roleId;
}

const filteredUsers = computed(() => {
  const k = keyword.value.trim().toLowerCase();
  if (!k) return users.value;
  return users.value.filter((u) => `${u.name} ${u.email}`.toLowerCase().includes(k));
});

async function apply() {
  error.value = "";

  if (selected.value.length === 0) {
    error.value = "Please select at least one member.";
    return;
  }

  // Validate role selection
  const missingRole = selected.value.find((m) => m.role_id === null);
  if (missingRole) {
    error.value = "Please select role for all selected members.";
    return;
  }

  const payload = {
    members: selected.value.map((m) => ({
      user_id: m.user_id,
      role_id: m.role_id as number,
    })),
  };

  try {
    await apiAssignMembers(props.projectId, payload);
    emit("applied");
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  }
}

onMounted(loadData);
</script>

<template>
  <div class="overlay" @click.self="emit('close')">
    <div class="modal">
      <div class="modal-title">Add member</div>

      <div v-if="error" class="error-box">{{ error }}</div>

      <div class="row">
        <input class="input" v-model="keyword" placeholder="Search user..." />
        <button class="btn-danger" @click="loadData" :disabled="loading">Search</button>
      </div>

      <div class="table">
        <div class="thead">
          <div>User</div>
          <div>Role</div>
        </div>

        <div v-if="loading" class="empty">Loading...</div>
        <div v-else-if="filteredUsers.length === 0" class="empty">No data</div>

        <div v-else class="tbody">
          <div v-for="u in filteredUsers" :key="u.id" class="tr">
            <div class="td user-col">
              <input
                type="checkbox"
                :checked="isChecked(u.id)"
                @change="toggleUser(u, ($event.target as HTMLInputElement).checked)"
              />
              <div class="user-info">
                <div class="user-name">{{ u.name }}</div>
                <div class="user-email">{{ u.email }}</div>
              </div>
            </div>

            <div class="td">
              <select
                class="input"
                :disabled="!isChecked(u.id) || roles.length === 0"
                :value="selectedRoleId(u.id) ?? ''"
                @change="setRole(u.id, ($event.target as HTMLSelectElement).value ? Number(($event.target as HTMLSelectElement).value) : null)"
              >
                <option value="" disabled>Select role</option>
                <option v-for="r in roles" :key="r.id" :value="r.id">
                  {{ r.name }}
                </option>
              </select>
              <div v-if="roles.length === 0" class="hint">No roles loaded</div>
            </div>
          </div>
        </div>
      </div>

      <div class="actions">
        <button class="btn-gray" @click="emit('close')">Cancel</button>
        <button class="btn-danger" @click="apply" :disabled="loading">Apply</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.overlay{
  position:fixed;
  inset:0;
  background: rgba(0,0,0,.35);
  display:flex;
  align-items:center;
  justify-content:center;
  z-index: 50;
}
.modal{
  width: 520px;
  background:#fff;
  border-radius: 16px;
  border:1px solid var(--border);
  padding: 18px;
  box-shadow: 0 14px 30px rgba(10,30,60,.18);
}
.modal-title{
  font-size: 26px;
  font-weight: 800;
  text-align:center;
  margin-bottom: 12px;
}
.row{
  display:flex;
  gap: 10px;
  align-items:center;
  margin-bottom: 12px;
}
.table{
  border:1px solid var(--border);
  border-radius: 12px;
  overflow:hidden;
}
.thead{
  display:grid;
  grid-template-columns: 1fr 180px;
  background:#f3f5f7;
  font-weight: 800;
  padding: 10px 12px;
}
.tbody .tr{
  display:grid;
  grid-template-columns: 1fr 180px;
  padding: 10px 12px;
  border-top: 1px solid var(--border);
}
.user-col{
  display:flex;
  gap: 10px;
  align-items:center;
}
.user-info{
  display:flex;
  flex-direction:column;
}
.user-name{ font-weight: 800; }
.user-email{ color:#666; font-size: 12px; }

.empty{
  padding: 12px;
  color:#666;
}
.hint{
  margin-top: 6px;
  color:#999;
  font-size: 12px;
}
.actions{
  display:flex;
  justify-content:center;
  gap: 14px;
  margin-top: 14px;
}
.btn-gray{
  padding: 10px 18px;
  border-radius: 999px;
  border:1px solid var(--border);
  background:#ddd;
  font-weight: 800;
  cursor:pointer;
}
.error-box{
  margin: 10px 0;
  padding:10px;
  border:1px solid var(--border);
  border-radius: 10px;
}
</style>
