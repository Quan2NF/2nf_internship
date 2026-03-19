<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/axios'

import BaseTextSearch from '@/components/base/BaseTextSearch.vue'
import PageTitleForEmployee from '@/components/common/PageTitleForEmployee.vue'
import AppHeaderAuth from '@/components/layout/AppHeaderAuth.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import ProjectCard from '@/components/common/ProjectCard.vue'
import BasePagination from '@/components/common/BasePagination.vue'
import DeleteModal from '@/components/modal/DeleteModal.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

const projects = ref([])
const route = useRoute()
const router = useRouter()

const page = ref(Number(route.query.page) || 1)
const keyword = ref(route.query.keyword || '')
const total = ref(0)
const perPage = 6
const showDeleteModal = ref(false)
const projectToDelete = ref(null)

function getInitials(name) {
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .slice(0, 1)
}

function formatDate(date) {
  if (!date) return '-'

  const [year, month, day] = date.split('-')
  return `${day}/${month}/${year}`
}

async function fetchProjects(newPage = page.value) {
  try {
    page.value = newPage

    router.push({
      query: {
        ...route.query,
        page: newPage,
        keyword: keyword.value || undefined
      }
    })

    const { data } = await api.get('/projects', {
      params: {
        per_page: perPage,
        page: newPage,
        keyword: keyword.value || undefined
      }
    })

    const response = data.data

    total.value = response.total

    projects.value = response.data.map(p => ({
      id: p.id,

      title: p.name,
      code: p.code,
      pmCode: p.pm?.employee_code,
      status: p.status_label,

      timeline: `${formatDate(p.planned_start_date)} - ${formatDate(p.planned_end_date)}`,

      progress: p.progress_rate,

      tasksDone: p.task_progress.Task?.closed ?? 0,
      tasksTotal: p.task_progress.Task?.total ?? 0,

      bugsDone: p.task_progress.Bug?.closed ?? 0,
      bugsTotal: p.task_progress.Bug?.total ?? 0,

      members: p.projectMembers.map(m => ({
        initials: getInitials(m.name),
        color: '#6c5ce7'
      }))
    }))
  } catch (err) {
    console.error('Failed to load projects', err)
  }
}

function openDelete(id) {
  projectToDelete.value = id
  showDeleteModal.value = true
}

function openEdit(id) {
  router.push({ name: 'projects.edit', params: { id: id } })
}

async function deleteProject() {
  try {
    console.log('DELETE ID:', projectToDelete.value)

    await api.delete(`/projects/${projectToDelete.value}`)

    showDeleteModal.value = false
    fetchProjects(page.value)

  } catch (err) {
    console.error('Delete failed', err)
  }
}

watch(
  () => route.query.page,
  (p) => {
    const newPage = Number(p) || 1
    if (newPage !== page.value) {
      fetchProjects(newPage)
    }
  }
)

watch(
  () => route.query.keyword,
  (k) => {
    const newKeyword = k || ''
    if (newKeyword !== keyword.value) {
      keyword.value = newKeyword
      fetchProjects(1)
    }
  }
)

onMounted(async () => {
  if (!auth.initialized) {
    await auth.fetchUser()
  }

  fetchProjects(page.value)
})
</script>

<template>
  <DeleteModal
    v-model="showDeleteModal"
    @confirm="deleteProject"
  />

  <div class="main-layout">
    <AppHeaderAuth/>

    <div class="main-body">
      <AppSidebar/>

      <div class="main-content">
        <div class="main-content__top">
          <PageTitleForEmployee>Projects</PageTitleForEmployee>
          <div class="main-content__top__right">
            <BaseTextSearch
              v-model="keyword"
              placeholder="Search projects"
              @enter="fetchProjects(1)"
            />

            <BaseButton
              v-if="auth.can('project.create')"
              class="main-content__button"
              @click="router.push({ name: 'projects.create' })"
            >
              + Create Project
            </BaseButton>
          </div>
          
        </div>

        <div class="project-grid">
          <ProjectCard
            v-for="project in projects"
            :key="project.id"
            v-bind="project"
            @delete="openDelete"
            @edit="openEdit"
          />
        </div>

        <BasePagination
          class="pagination"
          :total-items="total"
          :current-page="page"
          :page-size="perPage"
          @change="fetchProjects"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
  .main-layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: #F0F6FF;
  }

  .main-body {
    flex: 1;
    display: flex;
    overflow: hidden;
    padding-bottom: 7px;
  }

  .main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 47px;
    overflow-y: auto;
    padding: 27px 44px 42px 59px;
  }

  .main-content__top {
    padding-left: 6px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .project-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 55px;
  }

  .pagination {
    display: flex;
    justify-content: center;
  }

  .main-content__top__right {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .main-content__button {
    height: 56px;
    font-size: 20px;
    padding: 13px 24px;
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
    line-height: 1.5;
  }
</style>