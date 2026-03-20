<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/axios'

import BaseInput from '@/components/base/BaseInput.vue'
import BaseTextarea from '@/components/base/BaseTextarea.vue'
import BaseDateInput from '@/components/base/BaseDateInput.vue'
import BaseSelectInput from '@/components/base/BaseSelectInput.vue'
import BaseRadio from '@/components/base/BaseRadio.vue'
import BaseSwitch from '@/components/base/BaseSwitch.vue'
import BaseButton from '@/components/base/BaseButton.vue'

import PageTitleForEmployee from '@/components/common/PageTitleForEmployee.vue'
import AppHeaderAuth from '@/components/layout/AppHeaderAuth.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import BaseMemberTable from '@/components/common/BaseMemberTable.vue'

import AddMembersModal from '@/components/modal/AddMembersModal.vue'
import DeleteModal from '@/components/modal/DeleteModal.vue'


const router = useRouter()

const form = reactive({
  code: '',
  name: '',
  description: '',
  planned_start_date: '',
  planned_end_date: '',
  manager: null,
  status: null,
  is_public: false,
  is_active: true
})

const users = ref([])
const members = ref([])

const showDeleteModal = ref(false)
const memberToDelete = ref(null)

const statuses = ref([])

async function fetchStatuses() {
  const { data } = await api.get('/enums/project-statuses')
  statuses.value = data

  if (!form.status && data.length) {
    form.status = data[0].value
  }
}

const roles = ref([])

async function fetchRoles() {
  const { data } = await api.get('/roles')

  roles.value = data
    .filter(r => r.code !== 'PM') // 👈 remove PM here
    .map(r => ({
      label: r.label,
      value: r.code,
      id: r.value
    }))
}

const manager_options = computed(() =>
  members.value.map(m => ({
    label: m.name,
    value: m.id
  }))
)

const showAddMembersModal = ref(false)

async function fetchUsers() {
  const { data } = await api.get('/users', {
    params: { per_page: 100 }
  })

  const list = data.data.data

  users.value = list.map(u => ({
    id: u.id,
    name: u.name,
    email: u.email
  }))
}

function handleAdd(data) {
  data.forEach(item => {
    const user = users.value.find(u => u.id == item.userId)
    if (!user) return

    const exists = members.value.find(m => m.id == user.id)

    const newRoles = Array.isArray(item.roles) ? item.roles : []

    if (!newRoles.length) {
      members.value = members.value.filter(m => m.id !== user.id)
      return
    }

    if (exists) {
      exists.roles = newRoles
    } else {
      members.value.push({
        id: user.id,
        name: user.name,
        email: user.email,
        roles: newRoles
      })
    }
  })

  showAddMembersModal.value = false
}

function openDelete(member) {
  memberToDelete.value = member
  showDeleteModal.value = true
}

function deleteMember() {
  members.value = members.value.filter(
    m => m.id !== memberToDelete.value.id
  )

  showDeleteModal.value = false
}

function mapMembersForApi() {
  return members.value.map(member => ({
    user_id: member.id,
    roles: Array.isArray(member.roles)
      ? member.roles
      : [member.roles].filter(Boolean)
  }))
}

async function createProject() {
  try {
    const { data } = await api.post('/projects', {
      code: form.code,
      name: form.name,
      description: form.description,
      status: form.status,
      planned_start_date: form.planned_start_date || null,
      planned_end_date: form.planned_end_date || null,
      progress_rate: 0,
      is_public: form.is_public,
      is_active: form.is_active
    })

    const projectId = data.data.id

    if (form.manager) {
      await api.put(`/projects/${projectId}/pm`, {
        pm_id: form.manager
      })
    }

    if (members.value.length) {
      await api.put(`/projects/${projectId}/members`, {
        members: mapMembersForApi()
      })
    }

    router.push('/projects')

  } catch (err) {
    console.error('Create project failed')

    if (err.response) {
      console.error('STATUS:', err.response.status)
      console.error('RESPONSE:', err.response.data)

      if (err.response.data?.errors) {
        console.error('VALIDATION ERRORS:', err.response.data.errors)
      }
    } else {
      console.error(err)
    }
  }
}

function cancel() {
  router.push('/projects')
}

watch(members, (newMembers) => {
  const exists = newMembers.find(m => m.id === form.manager)
  if (!exists) {
    form.manager = null
  }
})

onMounted(() => {
  fetchUsers()
  fetchStatuses()
  fetchRoles()
})
</script>

<template>
<div class="main-layout">

  <AppHeaderAuth/>

  <div class="main-body">

    <AppSidebar/>

    <div class="main-content">

      <div class="main-content__top">
        <PageTitleForEmployee>Create Project</PageTitleForEmployee>
      </div>

      <div class="main-content__form">

        <BaseInput
          v-model="form.code"
          label="Code"
          required
          style="--input-label-color:#000;"
        />

        <BaseInput
          v-model="form.name"
          label="Title"
          required
          style="--input-label-color:#000;"
        />

        <div class="main-content__form__date-input">

          <BaseDateInput
            v-model="form.planned_start_date"
            label="Start date"
            style="--date-input-label-color:#000;"
          />

          <BaseDateInput
            v-model="form.planned_end_date"
            label="End date"
            style="--date-input-label-color:#000;"
          />

        </div>

        <BaseTextarea
          v-model="form.description"
          label="Description"
          :rows="8"
          style="--input-label-color:#000;"
        />

        <BaseSelectInput
          v-model="form.manager"
          label="Manager"
          :options="manager_options"
          :placeholder="manager_options.length ? '' : 'Select a member first'"
          :disabled="!manager_options.length"
          style="--select-input-label-color:#000;"
        />

        <div class="main-content__form__member-table">

          <div class="main-content__form__top">
            <div>Members</div>

            <button
              class="btn-add-member"
              @click="showAddMembersModal = true"
            >
              + Add member
            </button>
          </div>

          <BaseMemberTable
            :members="members"
            :roles="roles"
            @delete="openDelete"
          />

          <AddMembersModal
            :users="users"
            :roles="roles"
            :existing-members="members"
            v-model="showAddMembersModal"
            @add="handleAdd"
          />

          <DeleteModal
            v-model="showDeleteModal"
            @confirm="deleteMember"
          />

        </div>

        <div class="main-content__form__status">

          <div class="main-content__form__top">
            Status
          </div>

          <div class="main-content__form__status-row">
            <BaseRadio
              v-for="s in statuses"
              :key="s.value"
              v-model="form.status"
              :value="s.value"
              name="status"
            >
              {{ s.label }}
            </BaseRadio>
          </div>

        </div>

        <div class="main-content__form__public-and-active">

          <div class="main-content__form__public">
            <div class="main-content__form__top">
              Public
            </div>

            <BaseSwitch v-model="form.is_public"/>
          </div>

          <div class="main-content__form__active">
            <div class="main-content__form__top">
              Active
            </div>

            <BaseSwitch v-model="form.is_active"/>
          </div>

        </div>

        <div class="form__footer">

          <BaseButton
            size="form-size"
            color="cancel"
            @click="cancel"
          >
            Cancel
          </BaseButton>

          <BaseButton
            size="form-size"
            @click="createProject"
          >
            Save
          </BaseButton>

        </div>

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
    padding-bottom: 46px;
  }

  .main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-y: auto;
    padding: 35px 37px 0px 39px;
  }

  .main-content__top {
    padding-left: 6px;
    display: flex;
    justify-content: space-between;
  }

  .main-content__form {
    position: relative;
    background: white;
    padding: 35px 342px 71px 342px;
    border-radius: 10px;
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
    line-height: 1.5;

    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  .main-content__form__date-input {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
  }

  .btn-add-member {
    background: none;
    border: none;
    padding: 0;

    font-family: 'Roboto', sans-serif;
    font-size: 16px;
    font-weight: 400;
    color: #FF383C;
    line-height: 1.5;

    cursor: pointer;
  }

  .main-content__form__member-table {
    display: flex;
    flex-direction: column;
    gap: 9px;
  }

  .main-content__form__public-and-active {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
  }

  .main-content__form__public {
    display: flex;
    flex-direction: column;
    gap: 9px;
  }

  .main-content__form__active {
    display: flex;
    flex-direction: column;
    gap: 9px;
  }

  .main-content__form__top {
    display: flex;
    gap: 20px;
    color: #000;
  }

  .main-content__form__status {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }

  .main-content__form__status-row {
    display: flex;
    gap: 16px;
  }

  .form__footer {
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    font-size: 16px;
    line-height: 1;

    display: flex;
    margin-top: 42px;
    justify-content: center;
    align-items: center;
    gap: 22px;
  }
</style>