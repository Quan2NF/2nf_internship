<script setup lang="ts">
import { ref, watch } from "vue";
import { apiUpdateRole, getErrorMessage } from "../../lib/api";

type Role = { id: number; code: string; name: string };

const props = defineProps<{ role: Role }>();
const emit = defineEmits<{ (e: "close"): void; (e: "updated"): void }>();

const form = ref({ code: "", name: "" });
const loading = ref(false);
const error = ref("");

watch(
  () => props.role,
  (r) => {
    form.value.code = r?.code ?? "";
    form.value.name = r?.name ?? "";
  },
  { immediate: true }
);

async function save() {
  loading.value = true;
  error.value = "";
  try {
    await apiUpdateRole(props.role.id, {
      code: form.value.code.trim(),
      name: form.value.name.trim(),
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
    <div class="modal">
      <div class="modal-title">Edit Role</div>

      <div v-if="error" class="error-box">{{ error }}</div>

      <div class="form-grid">
        <div class="form-group">
          <div class="label">Code <span class="required">*</span></div>
          <input class="input" v-model="form.code" />
        </div>

        <div class="form-group">
          <div class="label">Name <span class="required">*</span></div>
          <input class="input" v-model="form.name" />
        </div>
      </div>

      <div class="modal-actions">
        <button class="btn-cancel" @click="emit('close')">Cancel</button>
        <button class="btn-save" :disabled="loading" @click="save">
          {{ loading ? "Saving..." : "Save" }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.25); display:flex; align-items:center; justify-content:center; z-index:9999; }
.modal{ width:560px; max-width:calc(100vw - 24px); background:#fff; border-radius:12px; border:1px solid var(--border); padding:16px; max-height:calc(100vh - 48px); overflow:auto; }
.modal-title{ font-size:20px; font-weight:800; text-align:center; margin-bottom:10px; }
.form-grid{ display:grid; grid-template-columns:1fr; gap:12px; }
.modal-actions{ display:flex; justify-content:center; gap:14px; margin-top:16px; }
.btn-cancel{ border:0; padding:10px 18px; border-radius:22px; background:#cfcfcf; color:#fff; font-weight:700; cursor:pointer; }
.btn-save{ border:0; padding:10px 18px; border-radius:22px; background:#ef3b3b; color:#fff; font-weight:700; cursor:pointer; }
.btn-save:disabled{ opacity:.6; cursor:not-allowed; }
</style>
