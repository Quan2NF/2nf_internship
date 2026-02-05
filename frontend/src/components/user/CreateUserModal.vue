<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { apiGetRoles, apiCreateUser, getErrorMessage } from "../../lib/api";

type Role = { id: number; code: string; name: string };

const emit = defineEmits<{
  (e: "close"): void;
  (e: "created"): void;
}>();

const form = ref({
  employee_code: "",
  name: "",
  email: "",
  password: "",
  phone_number: "",
  gender: 1 as 1 | 2 | 3,
  birthday: "",
  join_date: "",
  resign_date: "",
  is_active: 1 as 1 | 2,
});

const roles = ref<Role[]>([]);
const pickedRoleIds = ref<Record<number, boolean>>({});

const loading = ref(false);
const error = ref("");

const selectedRoleIds = computed<number[]>(() =>
  Object.keys(pickedRoleIds.value)
    .filter((k) => pickedRoleIds.value[Number(k)])
    .map((k) => Number(k))
);

async function loadRoles() {
  try {
    const res = await apiGetRoles();
    console.log("apiGetRoles raw:", res);

    const items =
      res?.data?.items ??
      res?.data?.roles ??
      res?.data ??
      res?.items ??
      res;

    console.log("roles items:", items);

    roles.value = Array.isArray(items) ? items : [];
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  }
}

async function save() {
  loading.value = true;
  error.value = "";

  try {
    const payload = {
      employee_code: form.value.employee_code.trim(),
      name: form.value.name.trim(),
      email: form.value.email.trim(),
      password: form.value.password,
      phone_number: form.value.phone_number?.trim() || null,
      gender: form.value.gender || null,
      birthday: form.value.birthday || null,
      join_date: form.value.join_date || null,
      resign_date: form.value.resign_date || null,
      is_active: form.value.is_active,
      role_ids: selectedRoleIds.value,
    };

    await apiCreateUser(payload);
    emit("created");
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  } finally {
    loading.value = false;
  }
}

onMounted(loadRoles);
</script>

<template>
  <div class="modal-overlay" @click.self="emit('close')">
    <div class="modal-wide">
      <div class="modal-title">Create User</div>

      <div v-if="error" class="error-box">
        {{ error }}
      </div>

      <!-- Form -->
      <div class="form-grid">
        <div class="form-group">
          <div class="label">Code <span class="required">*</span></div>
          <input class="input" v-model="form.employee_code" />
        </div>

        <div class="form-group">
          <div class="label">Name <span class="required">*</span></div>
          <input class="input" v-model="form.name" />
        </div>

        <div class="form-group">
          <div class="label">Email <span class="required">*</span></div>
          <input class="input" type="email" v-model="form.email" />
        </div>

        <div class="form-group">
          <div class="label">Password <span class="required">*</span></div>
          <input class="input" type="password" v-model="form.password" />
        </div>

        <div class="form-group">
          <div class="label">Phone number</div>
          <input class="input" v-model="form.phone_number" />
        </div>

        <div class="form-group">
          <div class="label">Gender</div>
          <select class="input" v-model.number="form.gender">
            <option :value="1">Male</option>
            <option :value="2">Female</option>
            <option :value="3">Other</option>
          </select>
        </div>

        <div class="form-group">
          <div class="label">Birthday</div>
          <input class="input" type="date" v-model="form.birthday" />
        </div>

        <div class="form-group">
          <div class="label">Joined Date</div>
          <input class="input" type="date" v-model="form.join_date" />
        </div>

        <div class="form-group">
          <div class="label">Leaved Date</div>
          <input class="input" type="date" v-model="form.resign_date" />
        </div>

        <div class="form-group">
          <div class="label">Status</div>
          <select class="input" v-model.number="form.is_active">
            <option :value="1">Active</option>
            <option :value="2">Inactive</option>
          </select>
        </div>
      </div>

      <!-- Roles -->
      <div class="roles-wrap">
        <div class="label">Roles</div>

        <div v-if="roles.length === 0" class="empty-box">
          No roles loaded
        </div>

        <div v-else class="role-box">
          <label v-for="r in roles" :key="r.id" class="role-item">
            <input type="checkbox" v-model="pickedRoleIds[r.id]" />
            <span><b>{{ r.code }}</b> - {{ r.name }}</span>
          </label>
        </div>
      </div>

      <div class="actions">
        <button class="btn-cancel" @click="emit('close')">Cancel</button>
        <button class="btn-save" :disabled="loading" @click="save">
          {{ loading ? "Saving..." : "Save" }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay{
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.25);
  display:flex;
  align-items:center;
  justify-content:center;
  z-index: 9999;
}

.modal-wide{
  width: 980px;
  max-width: calc(100vw - 24px);
  background:#fff;
  border-radius: 12px;
  border: 1px solid var(--border);
  padding: 16px;

  /* quan trọng: không bị cắt nội dung */
  max-height: calc(100vh - 48px);
  overflow: auto;
}

.modal-title{
  font-size: 20px;
  font-weight: 800;
  text-align: center;
  margin-bottom: 8px;
}

.error-box{
  margin: 10px 0;
  padding: 10px;
  border: 1px solid var(--border);
  border-radius: 10px;
}

.form-grid{
  display:grid;
  grid-template-columns: 1fr;
  gap: 12px;
}

@media (min-width: 900px){
  .form-grid{ grid-template-columns: 1fr 1fr; }
}

.roles-wrap{
  margin-top: 14px;
}

.empty-box{
  padding:10px;
  border:1px solid var(--border);
  border-radius:10px;
}

.role-box{
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 10px;
  display:grid;
  grid-template-columns: 1fr;
  gap: 8px;
}

@media (min-width: 900px){
  .role-box{ grid-template-columns: 1fr 1fr; }
}

.role-item{
  display:flex;
  align-items:center;
  gap: 10px;
  font-size: 13px;
}

.actions{
  display:flex;
  justify-content:center;
  gap: 14px;
  margin-top: 16px;
}

.btn-cancel{
  border: 0;
  padding: 10px 18px;
  border-radius: 22px;
  background:#cfcfcf;
  color:#fff;
  font-weight: 700;
  cursor: pointer;
}

.btn-save{
  border: 0;
  padding: 10px 18px;
  border-radius: 22px;
  background:#ef3b3b;
  color:#fff;
  font-weight: 700;
  cursor: pointer;
}
.btn-save:disabled{
  opacity: .6;
  cursor: not-allowed;
}
</style>
