<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/axios'

import AppHeaderAuth from '@/components/layout/AppHeaderAuth.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import PageTitleForEmployee from '@/components/common/PageTitleForEmployee.vue'
import DatabaseFilterBar from '@/components/common/DatabaseFilterBar.vue'
import DatabaseUserTable from '@/components/common/DatabaseUserTable.vue'
import DeleteModal from '@/components/modal/DeleteModal.vue'
import BasePagination from '@/components/common/BasePagination.vue'

const users = ref([])

const route = useRoute()
const router = useRouter()

const page = ref(Number(route.query.page) || 1)
const keyword = ref(route.query.keyword || '')

const total = ref(0)
const perPage = 8

const showDeleteModal = ref(false)
const userToDelete = ref(null)

function formatDate(date) {
  if (!date) return '-'

  const d = new Date(date)

  const day = String(d.getDate()).padStart(2, '0')
  const month = String(d.getMonth() + 1).padStart(2, '0')
  const year = d.getFullYear()

  return `${day}/${month}/${year}`
}

function formatStatus(active) {
  return active ? 'Active' : 'Inactive'
}

async function fetchUsers(newPage = page.value) {
  try {
    page.value = newPage

    const { data } = await api.get('/users', {
      params: {
        page: newPage,
        per_page: perPage,
        keyword: keyword.value || undefined
      }
    })

    const response = data.data

    total.value = response.total

    users.value = response.data.map((u, index) => ({
      id: u.id,
      no: (newPage - 1) * perPage + index + 1,
      code: u.employee_code,
      name: u.name,
      email: u.email,
      phone: u.phone_number ?? '-',
      joinDate: formatDate(u.join_date),
      status: formatStatus(u.is_active)
    }))

    router.replace({
      query: {
        page: newPage,
        keyword: keyword.value || undefined
      }
    })
  }
  catch (err) {
    console.error('Failed to load users', err)
  }
}

function openDelete(user) {
  userToDelete.value = user
  showDeleteModal.value = true
}

async function deleteUser() {
  try {
    await api.delete(`/users/${userToDelete.value.id}`)

    showDeleteModal.value = false

    fetchUsers(page.value)
  }
  catch (err) {
    console.error('Delete failed', err)
  }
}

function searchUsers() {
  fetchUsers(1)
}

onMounted(() => fetchUsers(page.value))
</script>

<template>
  <div class="main-layout">

    <AppHeaderAuth/>

    <div class="main-body">

      <AppSidebar/>

      <div class="main-content">

        <div class="main-content__top">
          <PageTitleForEmployee>Users</PageTitleForEmployee>
        </div>

        <div class="database-table">

          <DatabaseFilterBar
            v-model="keyword"
            @search="searchUsers"
          />

          <DatabaseUserTable
            :users="users"
            @delete="openDelete"
          />

          <BasePagination
            class="pagination"
            :total-items="total"
            :current-page="page"
            :page-size="perPage"
            @change="fetchUsers"
          />

          <DeleteModal
            v-model="showDeleteModal"
            @confirm="deleteUser"
          />

        </div>

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
  padding-bottom: 40px;
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
  overflow-y: auto;
  padding: 23px 43px 0 37px;
}

.main-content__top {
  display: flex;
  justify-content: space-between;
}

.database-table {
  font-family: 'Roboto', sans-serif;
  font-size: 20px;
  font-weight: 400;

  display: flex;
  flex-direction: column;
  gap: 30px;
}

.pagination {
  display: flex;
  justify-content: center;
}
</style>