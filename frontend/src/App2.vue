<script setup>
  import { ref, reactive } from 'vue'
  import BaseButton from '@/components/base/BaseButton.vue'
  import BaseInput from '@/components/base/BaseInput.vue'
  import BaseLink from '@/components/base/BaseLink.vue'
  import AppHeaderGuest from '@/components/layout/AppHeaderGuest.vue'
  import AppHeaderAuth from '@/components/layout/AppHeaderAuth.vue'
  import BaseTextSearch from '@/components/base/BaseTextSearch.vue'
  import BasePagination from '@/components/common/BasePagination.vue'
  import ProjectCard from './components/common/ProjectCard.vue'
  import BaseDateInput from './components/base/BaseDateInput.vue'

  
  import BaseCheckbox from '@/components/base/BaseCheckbox.vue'
  import PageTitle from '@/components/common/PageTitle.vue'
  import AppSidebar from '@/components/layout/AppSidebar.vue'
  import PageTitleForEmployee from '@/components/common/PageTitleForEmployee.vue'
  import BaseSelectInput from '@/components/base/BaseSelectInput.vue'
  import BaseRadio from '@/components/base/BaseRadio.vue'
  import BaseSwitch from '@/components/base/BaseSwitch.vue'
  import BaseMemberTable from '@/components/common/BaseMemberTable.vue'
  import DeleteModal from '@/components/modal/DeleteModal.vue'
  import AddMembersModal from '@/components/modal/AddMembersModal.vue'
  import DatabaseUserTable from '@/components/common/DatabaseUserTable.vue'
import DatabaseFilterBar from './components/common/DatabaseFilterBar.vue'

  const showAddMembersModal = ref(false)

  const users = [
    { id: 1, name: 'Alice', email: 'alice@example.com' },
    { id: 2, name: 'Bob', email: 'bob@example.com' },
    { id: 3, name: 'Charlie', email: 'charlie@example.com' },
    { id: 4, name: 'D', email: 'd@example.com' },
    { id: 5, name: 'E', email: 'e@example.com' },
  ]

  const roles = [
    { label: 'Viewer', value: 'viewer' },
    { label: 'Editor', value: 'editor' },
    { label: 'Admin', value: 'admin' }
  ]

  function handleAdd(data) {
    console.log('ADD', data)
  }

  const showDeleteModal = ref(false)

  function deleteMember() {
    members.value = members.value.filter(m => m.id !== memberToDelete.value.id)
    showDeleteModal.value = false
  }

  const memberToDelete = ref(null)

  const members = ref([
    { id: 1, name: 'Nguyen Van A', role: 'Project Manager', status: true },
    { id: 2, name: 'Tran Thi B', role: 'Frontend Developer', status: true },
    { id: 3, name: 'Le Minh C', role: 'Backend Developer', status: true },
    { id: 4, name: 'Pham Duc D', role: 'UI/UX Designer', status: true },
    { id: 5, name: 'Hoang Gia E', role: 'QA Engineer', status: false },
    { id: 6, name: 'Vo Thanh F', role: 'DevOps Engineer', status: false }
  ])

  function openDelete(member) {
    memberToDelete.value = member
    showDeleteModal.value = true
  }


  const selected = ref('a')
  
  const email = ref('')
  const password = ref('')

  const errors = reactive({
    email: '',
  })

  const form = ref({
    birthDate: '',
    role: '',
    enabled: true,
  })
  const rememberMe = ref(false)

  const page = ref(10)
</script>

<template>
  <div class="app-layout">
    <AppHeaderAuth/>

    <div class="app-body">
      <AppSidebar/>

      <main class="app-main">
        <h1>You did it!</h1>
        <p>
          Visit <a href="https://vuejs.org/" target="_blank" rel="noopener">vuejs.org</a> to read the
          documentation
        </p>

        <div style="padding: 40px 300px">
          <PageTitle>Log in</PageTitle>

          <BaseButton>Log in</BaseButton>

          <BaseButton block>
            Submit Form
          </BaseButton>

          <BaseButton disabled>
            Disabled Button
          </BaseButton>

          <BaseInput
            v-model="email"
            label="Email"
            type="email"
            placeholder="you@example.com"
            :error="errors.email"
          />

          <BaseInput
            v-model="password"
            label="Password"
            type="password"
            showToggle
            required
          />

          <BaseCheckbox
            v-model="rememberMe"
            label="Remember me"
          />

          <BaseLink to="/login">Go to Login</BaseLink>

          <BaseTextSearch
            v-model="keyword"
            @enter="handleSearch"
          />

          <BasePagination
            :total-items="240"
            :current-page="page"
            :page-size="10"
            @change="page = $event"
          />

          <ProjectCard
            title="Jobmatching"
            code="PROJ-01"
            pmCode="PM-01"
            status="Active"
            timeline="01/07/2025 - 30/06/2026"
            :progress="25"
            :tasksDone="50"
            :tasksTotal="600"
            :bugs="25"
            :members="[
              { initials: 'A', color: '#fd7e14' },
              { initials: 'K', color: '#198754' },
              { initials: 'M', color: '#dc3545' }
            ]"
          />

          <PageTitleForEmployee>Projects</PageTitleForEmployee>
          <BaseDateInput
            v-model="form.birthDate"
            label="Birth date"
            required
            min="1900-01-01"
          />
          
          <BaseSelectInput
            v-model="form.role"
            label="Role"
            :options="[
              { label: 'Admin', value: 'admin' },
              { label: 'Editor', value: 'editor' },
              { label: 'User', value: 'user' }
            ]"
          />
          
          <BaseRadio v-model="selected" value="a" name="test">Option A</BaseRadio>
          <BaseRadio v-model="selected" value="b" name="test">Option B</BaseRadio>

          <BaseSwitch v-model="form.enabled" />

          <BaseMemberTable
            :members="members"
            @delete="openDelete"
          />

          <DeleteModal
            v-model="showDeleteModal"
            @confirm="deleteMember"
          />

          <button
            class="btn-add-member"
            @click="showAddMembersModal = true"
          >
            + Add member
          </button>

          <AddMembersModal
            :users="users"
            :roles="roles"
            @add="handleAdd"
            v-model="showAddMembersModal"
          />
        </div>
        <div class="database-table">
          <DatabaseFilterBar/>

          <DatabaseUserTable
            :members="members"
            @delete="openDelete"
          />
        </div>
        <router-view />
      </main>
    </div>
  </div>
</template>

<style scoped>
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

.btn-add-member:hover {
  opacity: 0.8;
}

.database-table {
  font-family: 'Roboto', sans-serif;
  font-size: 20px;
  font-weight: 400;
  padding: 0 43px 0 37px;

  display: flex;
  flex-direction: column;
  gap: 30px;
}
</style>
