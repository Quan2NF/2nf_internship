<script setup lang="ts">
import { ref, watch } from "vue";
import { apiUpdateProject, getErrorMessage, type Project } from "../../lib/api";
import AddMembersModal from "./AddMemberModal.vue";

const props = defineProps<{ project: Project }>();
const emit = defineEmits<{ (e: "close"): void; (e: "updated"): void }>();

const loading = ref(false);
const error = ref("");

const showAddMembers = ref(false);

const form = ref({
  project_code: "",
  title: "",
  start_date: "",
  end_date: "",
  description: "",
  status: "active",
  is_public: false,
});

watch(
  () => props.project,
  (p) => {
    form.value = {
      project_code: (p.project_code ?? p.code ?? "") as string,
      title: (p.title ?? p.name ?? "") as string,
      start_date: (p.start_date ?? "").slice(0, 10),
      end_date: (p.end_date ?? "").slice(0, 10),
      description: (p.description ?? "") as string,
      status: (p.status ?? "active") as any,
      is_public: Boolean(p.is_public ?? false),
    };
  },
  { immediate: true }
);

async function save() {
  loading.value = true;
  error.value = "";
  try {
    await apiUpdateProject(props.project.id, { ...form.value });
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
      <div class="modal-title">Edit Project</div>
      <div v-if="error" class="error-box">{{ error }}</div>

      <div class="form-grid">
        <div class="form-group">
          <div class="label">ID *</div>
          <input class="input" v-model="form.project_code" />
        </div>

        <div class="form-group">
          <div class="label">Title *</div>
          <input class="input" v-model="form.title" />
        </div>

        <div class="form-group">
          <div class="label">Start date</div>
          <input class="input" type="date" v-model="form.start_date" />
        </div>

        <div class="form-group">
          <div class="label">End date</div>
          <input class="input" type="date" v-model="form.end_date" />
        </div>

        <div class="form-group full">
          <div class="label">Description</div>
          <textarea class="input" rows="4" v-model="form.description"></textarea>
        </div>
      </div>

      <div class="members-block">
        <div class="members-title">
          <div>Members</div>
          <a class="link" @click="showAddMembers = true">+ Add member</a>
        </div>
        <div class="hint">
          Add members will load from <b>/users</b> and roles from <b>/roles</b>, then save by <b>POST /projects/{id}/members</b>.
        </div>
      </div>

      <div class="modal-actions">
        <button class="btn-cancel" @click="emit('close')">Cancel</button>
        <button class="btn-save" :disabled="loading" @click="save">
          {{ loading ? "Saving..." : "Save" }}
        </button>
      </div>
    </div>

    <AddMembersModal
      v-if="showAddMembers"
      :projectId="props.project.id"
      @close="showAddMembers = false"
      @applied="
        showAddMembers = false;
        emit('updated');
      "
    />
  </div>
</template>

<style scoped>
.modal-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.25); display:flex; align-items:center; justify-content:center; z-index:9999; }
.modal{ width:860px; max-width:calc(100vw - 24px); background:#fff; border-radius:12px; border:1px solid var(--border); padding:16px; max-height:calc(100vh - 48px); overflow:auto; }
.modal-title{ font-size:20px; font-weight:900; margin-bottom:10px; }
.form-grid{ display:grid; grid-template-columns: 1fr 1fr; gap:14px; }
.form-group.full{ grid-column: 1 / -1; }
.label{ font-weight:800; margin-bottom:6px; }
.input{ width:100%; border:1px solid var(--border); border-radius:10px; padding:10px 12px; }
.members-block{ margin-top:14px; padding-top:10px; border-top:1px solid var(--border); }
.members-title{ display:flex; justify-content:space-between; align-items:center; font-weight:900; }
.link{ color:#ef3b3b; cursor:pointer; font-weight:800; }
.hint{ margin-top:6px; opacity:.7; font-size:12px; }
.modal-actions{ display:flex; justify-content:center; gap:14px; margin-top:16px; }
.btn-cancel{ border:0; padding:10px 22px; border-radius:22px; background:#cfcfcf; color:#fff; font-weight:800; cursor:pointer; }
.btn-save{ border:0; padding:10px 22px; border-radius:22px; background:#ef3b3b; color:#fff; font-weight:800; cursor:pointer; }
.btn-save:disabled{ opacity:.6; cursor:not-allowed; }
.error-box{ margin:10px 0; padding:10px; border:1px solid var(--border); border-radius:10px; }
</style>
