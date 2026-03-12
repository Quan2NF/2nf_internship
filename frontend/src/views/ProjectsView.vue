<script setup>
import { ref, onMounted } from 'vue'
import api from '@/axios'

import BaseTextSearch from '@/components/base/BaseTextSearch.vue'
import PageTitleForEmployee from '@/components/common/PageTitleForEmployee.vue'
import AppHeaderAuth from '@/components/layout/AppHeaderAuth.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import ProjectCard from '@/components/common/ProjectCard.vue'
import BasePagination from '@/components/common/BasePagination.vue'

const projects = ref([])
const page = ref(1)
const total = ref(0)
const perPage = 6

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

function formatStatus(status) {
  if (!status) return ''
  return status.charAt(0) + status.slice(1).toLowerCase()
}

async function fetchProjects(newPage = page.value) {
  try {
    page.value = newPage

    const { data } = await api.get('/projects', {
      params: {
        per_page: perPage,
        page: newPage
      }
    })

    const response = data.data

    total.value = response.total

    projects.value = response.data.map(p => ({
      id: p.code,

      title: p.name,
      code: p.code,
      pmCode: p.pm.employee_code,
      status: formatStatus(p.status_label),

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

onMounted(fetchProjects)
</script>

<template>
  <div class="main-layout">
    <AppHeaderAuth/>

    <div class="main-body">
      <AppSidebar/>

      <div class="main-content">
        <div class="main-content__top">
          <PageTitleForEmployee>Projects</PageTitleForEmployee>
          <BaseTextSearch/>
        </div>

        <div class="project-grid">
          <ProjectCard
            v-for="project in projects"
            :key="project.id"
            v-bind="project"
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

<style>
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
    padding-bottom: 40px;
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
</style>