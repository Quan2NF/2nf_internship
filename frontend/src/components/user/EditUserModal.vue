<script setup lang="ts">
import { ref, watch } from "vue";
import { apiUpdateUser, getErrorMessage } from "../../lib/api";

type UserRow = {
  id: number;
  employee_code: string;
  name: string;
  email: string;
  phone_number: string | null;
  gender: number | null;
  birthday: string | null;
  join_date: string | null;
  resign_date: string | null;
  is_active: number; // 1/2
};

const props = defineProps<{ user: UserRow }>();

const emit = defineEmits<{
  (e: "close"): void;
  (e: "updated"): void;
}>();

const loading = ref(false);
const error = ref("");

const form = ref({
  employee_code: "",
  name: "",
  email: "",
  phone_number: "",
  gender: 1 as number,
  birthday: "",
  join_date: "",
  resign_date: "",
  is_active: 1 as number,
});

watch(
  () => props.user,
  (u) => {
    form.value.employee_code = u.employee_code ?? "";
    form.value.name = u.name ?? "";
    form.value.email = u.email ?? "";
    form.value.phone_number = u.phone_number ?? "";
    form.value.gender = u.gender ?? 1;
    form.value.birthday = u.birthday ?? "";
    form.value.join_date = u.join_date ?? "";
    form.value.resign_date = u.resign_date ?? "";
    form.value.is_active = u.is_active ?? 1;
  },
  { immediate: true }
);

async function save() {
  loading.value = true;
  error.value = "";
  try {
    // payload đúng theo DB design của bạn
    await apiUpdateUser(props.user.id, {
      employee_code: form.value.employee_code,
      name: form.value.name,
      email: form.value.email,
      phone_number: form.value.phone_number || null,
      gender: form.value.gender || null,
      birthday: form.value.birthday || null,
      join_date: form.value.join_date || null,
      resign_date: form.value.resign_date || null,
      is_active: form.value.is_active,
    });

    emit("updated");
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="modal-overlay" @click.self="emit('close')">
    <div class="modal-wide">
      <div class="modal-title">Edit User</div>

      <div v-if="error" class="error" style="text-align:left;margin:10px 0;">
        {{ error }}
      </div>

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

      <div class="modal-actions">
        <button class="btn-danger" style="background:#cfcfcf;" @click="emit('close')">Cancel</button>
        <button class="btn-danger" :disabled="loading" @click="save">
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
  width: 900px;
  max-width: calc(100vw - 24px);
  background:#fff;
  border-radius: 12px;
  border: 1px solid var(--border);
  padding: 16px;
}
.modal-title{ font-size: 20px; font-weight: 800; text-align:center; }
.form-grid{
  display:grid;
  grid-template-columns: 1fr;
  gap: 12px;
  margin-top: 12px;
}
@media (min-width: 900px){
  .form-grid{ grid-template-columns: 1fr 1fr; }
}
.modal-actions{
  display:flex;
  justify-content:center;
  gap: 14px;
  margin-top: 16px;
}
</style>
