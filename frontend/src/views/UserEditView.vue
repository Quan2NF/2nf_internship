<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AppHeaderAuth from '@/components/layout/AppHeaderAuth.vue';
import AppSidebar from '@/components/layout/AppSidebar.vue';
import PageTitleForEmployee from '@/components/common/PageTitleForEmployee.vue';
import BaseInput from '@/components/base/BaseInput.vue';
import BaseDateInput from '@/components/base/BaseDateInput.vue';
import BaseSelectInput from '@/components/base/BaseSelectInput.vue';
import BasePositionTable from '@/components/common/BasePositionTable.vue';
import BaseSwitch from '@/components/base/BaseSwitch.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import AssignPositionModal from '@/components/modal/AssignPositionModal.vue';
import DeleteModal from '@/components/modal/DeleteModal.vue';
import api from '@/axios'

const router = useRouter()
const route = useRoute()
const userId = route.params.id

const form = reactive({
  employee_code: '',
  name: '',
  email: '',
  phone_number: '',
  birthday: '',
  gender: null,
  join_date: '',
  resign_date: '',
  avatar: '',
  is_active: true
})

const loading = ref(true)

const showAssignPositionModal = ref(false)
const showDeleteModal = ref(false)

const positions = ref([])
const assignedPositions = ref([])

const positionToDelete = ref(null)

const existingPositions = computed(() =>
  assignedPositions.value.map(p => ({ id: p.id }))
)

async function fetchPositions() {
  const res = await api.get('/positions')
  positions.value = (res.data?.data || []).map(p => ({
    id: p.id,
    name: p.name
  }))
}

async function fetchUser() {
  const { data } = await api.get(`/users/${userId}`)
  const u = data.data

  form.employee_code = u.employee_code
  form.name = u.name
  form.email = u.email
  form.phone_number = u.phone_number
  form.birthday = u.birthday
  form.gender = u.gender
  form.join_date = u.join_date
  form.resign_date = u.resign_date
  form.avatar = u.avatar
  form.is_active = u.is_active
}

async function fetchUserPositions() {
  const { data } = await api.get(`/users/${userId}/positions`)

  assignedPositions.value = (data.data || []).map(p => ({
    id: p.id,
    name: p.name
  }))
}

function handleAssign(positionIds) {
  const selected = positions.value.filter(p =>
    positionIds.includes(p.id)
  )

  assignedPositions.value.push(...selected)
  showAssignPositionModal.value = false
}

function openDelete(position) {
  positionToDelete.value = position
  showDeleteModal.value = true
}

function deletePosition() {
  assignedPositions.value = assignedPositions.value.filter(
    p => p.id !== positionToDelete.value.id
  )
  showDeleteModal.value = false
}

async function updateUser() {
  try {
    await api.put(`/users/${userId}`, {
      employee_code: form.employee_code,
      name: form.name,
      email: form.email,
      phone_number: form.phone_number || null,
      birthday: form.birthday || null,
      gender: form.gender ? Number(form.gender) : null,
      join_date: form.join_date || null,
      resign_date: form.resign_date || null,
      avatar: form.avatar || null,
      is_active: form.is_active
    })

    await api.put(`/users/${userId}/positions`, {
      position_ids: assignedPositions.value.map(p => p.id)
    })

    await fetchUser()

  } catch (err) {
    console.error('Update user failed', err)
  }
}

function cancel() {
  router.push('/users')
}

onMounted(async () => {
  try {
    loading.value = true

    await Promise.all([
      fetchPositions(),
      fetchUser(),
      fetchUserPositions()
    ])

  } catch (err) {
    console.error('Failed to load user edit data:', err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
<div class="main-layout">
  <AppHeaderAuth/>

  <div class="main-body">
    <AppSidebar/>

    <div class="main-content">
      <div class="main-content__top">
        <PageTitleForEmployee>Edit User</PageTitleForEmployee>
      </div>

      <div v-if="!loading" class="main-content__form">
        <BaseInput
          v-model="form.employee_code"
          label="Code"
          disabled
          style="--input-label-color:#000;"
        />

        <BaseInput
          v-model="form.name"
          label="Name"
          required
          style="--input-label-color:#000;"
        />

        <BaseInput
          v-model="form.email"
          label="Email"
          disabled
          style="--input-label-color:#000;"
        />

        <BaseInput
          v-model="form.phone_number"
          label="Phone number"
          style="--input-label-color:#000;"
        />

        <div class="main-content__form__grid">
          <BaseSelectInput
            v-model="form.gender"
            label="Gender"
            :options="[
              { label: 'Male', value: 1 },
              { label: 'Female', value: 2 },
              { label: 'Other', value: 3 }
            ]"
            style="--select-input-label-color:#000;"
          />

          <BaseDateInput
            v-model="form.birthday"
            label="Birthday"
            style="--date-input-label-color:#000;"
          />

          <BaseDateInput
            v-model="form.join_date"
            label="Joined date"
            style="--date-input-label-color:#000;"
          />

          <BaseDateInput
            v-model="form.resign_date"
            label="Leaved date"
            style="--date-input-label-color:#000;"
          />
        </div>

        <div class="main-content__form__position-table">
          <div class="main-content__form__top">
            <div>Position</div>

            <button
              class="btn-assign-position"
              @click="showAssignPositionModal = true"
            >
              + Assign position
            </button>
          </div>

          <BasePositionTable
            :positions="assignedPositions"
            @delete="openDelete"
          />

          <AssignPositionModal
            v-model="showAssignPositionModal"
            :positions="positions"
            @add="handleAssign"
            :existing-positions="existingPositions"
          />

          <DeleteModal
            v-model="showDeleteModal"
            @confirm="deletePosition"
          />
        </div>

        <div class="main-content__form__active">
          <div class="main-content__form__top">
            Active
          </div>

          <BaseSwitch v-model="form.is_active"/>
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
            @click="updateUser"
          >
            Apply
          </BaseButton>

        </div>
      </div>

      <div v-else>
        Loading...
      </div>
    </div>
  </div>
</div>
</template>

<style scoped>
/* EXACTLY SAME STYLE — unchanged */
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

.main-content__form__grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  column-gap: 30px;
  row-gap: 15px;
}

.main-content__form__position-table {
  display: flex;
  flex-direction: column;
  gap: 9px;
}

.main-content__form__top {
  display: flex;
  gap: 20px;
  color: #000;
}

.btn-assign-position {
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

.main-content__form__active {
  display: flex;
  flex-direction: column;
  gap: 9px;
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