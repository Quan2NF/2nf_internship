<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import {
  apiGetProjects,
  apiDeleteProject,
  getErrorMessage,
} from "../lib/api";

// Nếu bạn đã có modal create/edit thì giữ, không có thì comment 2 dòng import này
import CreateProjectModal from "../components/project/CreateProjectModal.vue";
import EditProjectModal from "../components/project/EditProjectModal.vue";

type Project = {
  id: number;
  code: string;
  name: string;
  description?: string | null;

  status: string; // new | in_progress | closed | ...

  planned_start_date?: string | null;
  planned_end_date?: string | null;
  start_date?: string | null;
  end_date?: string | null;

  progress_rate?: number | null;

  is_public?: number | boolean | null;
  is_active?: number | boolean | null;
};

const projects = ref<Project[]>([]);
const keyword = ref("");
const loading = ref(false);
const error = ref("");

const showCreate = ref(false);
const showEdit = ref(false);
const editing = ref<Project | null>(null);

/**
 * Extract array from many possible Laravel response shapes.
 * Supports:
 * - { data: { items: [...] } }
 * - { data: { items: { data: [...] } } } (pagination)
 * - { data: [...] }
 * - { items: [...] }
 */
function extractArray(res: any): any[] {
  const candidates = [
    res?.data?.items?.data,
    res?.data?.items,
    res?.data?.projects,
    res?.data?.data,
    res?.data,
    res?.items?.data,
    res?.items,
    res?.projects,
    res,
  ];
  for (const c of candidates) {
    if (Array.isArray(c)) return c;
  }
  return [];
}

async function fetchProjects() {
  loading.value = true;
  error.value = "";
  try {
    const res = await apiGetProjects({
      keyword: keyword.value || undefined,
    });

    // Debug: nhìn đúng shape response
    console.log("GET /projects raw:", res);

    projects.value = (extractArray(res) as Project[]).map((p: any) => ({
      id: Number(p.id),
      code: String(p.code ?? ""),
      name: String(p.name ?? ""),
      description: p.description ?? null,
      status: String(p.status ?? ""),

      planned_start_date: p.planned_start_date ?? null,
      planned_end_date: p.planned_end_date ?? null,
      start_date: p.start_date ?? null,
      end_date: p.end_date ?? null,

      progress_rate:
        p.progress_rate === null || p.progress_rate === undefined
          ? null
          : Number(p.progress_rate),

      is_public: p.is_public ?? null,
      is_active: p.is_active ?? null,
    }));
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
    projects.value = [];
  } finally {
    loading.value = false;
  }
}

const filtered = computed(() => {
  const k = keyword.value.trim().toLowerCase();
  if (!k) return projects.value;
  return projects.value.filter((p) => {
    return `${p.code} ${p.name}`.toLowerCase().includes(k);
  });
});

function openEdit(p: Project) {
  editing.value = p;
  showEdit.value = true;
}

async function removeProject(p: Project) {
  const ok = window.confirm(`Delete project "${p.code} - ${p.name}" ?`);
  if (!ok) return;

  try {
    await apiDeleteProject(p.id);
    await fetchProjects();
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  }
}

function statusLabel(s: string) {
  if (s === "new") return "New";
  if (s === "in_progress") return "In progress";
  if (s === "closed") return "Closed";
  return s || "-";
}

function boolText(v: any) {
  return v === 1 || v === true ? "Yes" : "No";
}

function timelineText(p: Project) {
  const s = (p.planned_start_date || p.start_date || "").slice(0, 10) || "~";
  const e = (p.planned_end_date || p.end_date || "").slice(0, 10) || "~";
  return `${s} - ${e}`;
}

onMounted(fetchProjects);
</script>

<template>
  <div class="content-card">
    <div class="header-row">
      <div class="title">Projects</div>

      <div style="display:flex; gap:10px; align-items:center;">
        <input class="input" v-model="keyword" placeholder="Search" style="width: 280px;" />
        <button class="btn-danger" @click="fetchProjects">Search</button>
        <button class="btn-danger" @click="showCreate = true">+ Create Project</button>
      </div>
    </div>

    <div v-if="error" class="error-box">{{ error }}</div>

    <div class="project-grid">
      <div v-if="loading" style="padding:14px;">Loading...</div>
      <div v-else-if="filtered.length === 0" style="padding:14px;">No data</div>

      <div v-else v-for="p in filtered" :key="p.id" class="project-card">
        <div class="project-card__top">
          <div>
            <div class="project-title">{{ p.name }}</div>
            <div class="project-code">{{ p.code }}</div>
          </div>

          <div class="project-actions">
            <button class="btn-outline" @click="openEdit(p)">Edit</button>
            <button class="btn-outline danger" @click="removeProject(p)">Delete</button>
          </div>
        </div>

        <div class="project-desc" v-if="p.description">
          {{ p.description }}
        </div>

        <div class="project-meta">
          <div><b>Status:</b> {{ statusLabel(p.status) }}</div>
          <div style="text-align:right;"><b>Progress:</b> {{ p.progress_rate ?? 0 }}%</div>
        </div>

        <div class="project-meta" style="margin-top:8px;">
          <div><b>Public:</b> {{ boolText(p.is_public) }}</div>
          <div style="text-align:right;"><b>Active:</b> {{ boolText(p.is_active) }}</div>
        </div>

        <div class="status-row">
          <span class="pill">{{ statusLabel(p.status) }}</span>
          <div class="timeline">Timeline: {{ timelineText(p) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Nếu bạn chưa làm modal thì comment 2 block dưới -->
  <CreateProjectModal
    v-if="showCreate"
    @close="showCreate = false"
    @created="() => { showCreate = false; fetchProjects(); }"
  />

  <EditProjectModal
    v-if="showEdit && editing"
    :project="editing"
    @close="showEdit = false"
    @updated="() => { showEdit = false; fetchProjects(); }"
  />
</template>

<style scoped>
/* Đồng bộ style với RolesPage.vue của bạn */
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
.title{ font-size: 44px; font-weight: 800; }

.error-box{
  margin: 10px 0;
  padding:10px;
  border:1px solid var(--border);
  border-radius: 10px;
}

.project-grid{
  display:grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 18px;
}
.project-card{
  border:1px solid var(--border);
  border-radius: 16px;
  padding: 18px;
  background: #fff;
  box-shadow: 0 8px 18px rgba(10, 30, 60, 0.06);
}
.project-card__top{
  display:flex;
  justify-content:space-between;
  gap: 12px;
}
.project-title{
  font-size: 28px;
  font-weight: 800;
  line-height: 1.1;
}
.project-code{
  margin-top: 6px;
  color: #666;
  font-weight: 600;
}
.project-actions{
  display:flex;
  flex-direction:column;
  gap: 10px;
  align-items:flex-end;
  min-width: 100px;
}
.project-desc{
  margin-top: 12px;
  color:#444;
  line-height: 1.35;
}

.project-meta{
  display:flex;
  justify-content:space-between;
  margin-top: 14px;
  color:#222;
}

.status-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-top: 18px;
}
.pill{
  display:inline-flex;
  align-items:center;
  padding: 6px 14px;
  border-radius: 999px;
  background: #eaf7ef;
  color: #1e7a3a;
  font-weight: 800;
}
.timeline{
  color:#666;
  font-weight: 600;
}

.btn-outline{
  border:1px solid var(--border);
  background:#fff;
  padding:8px 12px;
  border-radius: 12px;
  cursor:pointer;
  font-weight: 700;
}
.btn-outline.danger{
  border-color: #ffbcbc;
  color:#d72323;
}
</style>
