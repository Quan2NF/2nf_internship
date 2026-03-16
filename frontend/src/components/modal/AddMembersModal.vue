<script setup>
import { ref, computed } from 'vue'

import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseCheckbox from '@/components/base/BaseCheckbox.vue'
import BaseSelectInput from '@/components/base/BaseSelectInput.vue'

const props = defineProps({
  users: {
    type: Array,
    default: () => []
    // [{ id, name }]
  },
  roles: {
    type: Array,
    default: () => []
    // [{ label, value }]
  }
})

const isOpen = defineModel({ type: Boolean })

const emit = defineEmits(['close', 'add'])

const keyword = ref('')

const filteredUsers = computed(() => {
  if (!keyword.value.trim()) return props.users

  const k = keyword.value.toLowerCase()

  return props.users.filter(user =>
    user.name.toLowerCase().includes(k) ||
    user.email.toLowerCase().includes(k)
  )
})

const selected = ref({}) // { userId: { checked: true, role: '...' } }

function toggleUser(userId, checked) {
  if (!selected.value[userId]) {
    selected.value[userId] = { checked: false, role: null }
  }
  selected.value[userId].checked = checked
}

function changeRole(userId, role) {
  if (!selected.value[userId]) {
    selected.value[userId] = { checked: false, role: null }
  }
  selected.value[userId].role = role
}

function submit() {
  const result = Object.entries(selected.value)
    .filter(([_, v]) => v.checked)
    .map(([userId, v]) => ({
      userId,
      role: v.role
    }))

  emit('add', result)
}

function close() {
  isOpen.value = false
}

function search() {
  keyword.value = keyword.value.trim()
}
</script>

<template>
  <teleport to="body">
    <div v-if="isOpen" class="overlay" @click.self="close">
      <div class="modal">
        <!-- Header -->
        <div class="modal__header">
          Add member
        </div>

        <!-- Search -->
        <div class="search">
          <BaseInput
            style="--input-border-color:#F2F2F7; width:492px;"
            v-model="keyword"
            @keyup.enter="search"
          />
          <BaseButton size="modal-size" @click="search">
            Search
          </BaseButton>
        </div>

        <!-- Table -->
        <div class="table">
          <!-- Header -->
          <div class="table__head table__row">
            <div class="cell cell--checkbox"></div>
            <div class="cell">User</div>
            <div class="cell">Role</div>
          </div>

          <!-- Scroll Body -->
          <div class="table__body">
            <div
              v-for="user in filteredUsers"
              :key="user.id"
              class="table__row"
            >
              <div class="cell cell--checkbox">
                <BaseCheckbox
                  variant="red"
                  :model-value="selected[user.id]?.checked || false"
                  @update:model-value="val => toggleUser(user.id, val)"
                />
              </div>

              <div class="cell cell--user">
                <div class="user-name">
                  {{ user.name }}
                </div>
                <div class="user-sub">
                  {{ user.email }}
                </div>
              </div>

              <div class="cell">
                <BaseSelectInput
                  class="role-select"
                  :model-value="selected[user.id]?.role || null"
                  :options="roles"
                  placeholder="Select role"
                  @update:model-value="val => changeRole(user.id, val)"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal__footer">
          <BaseButton size="modal-size" color="cancel" @click="close">
            Cancel
          </BaseButton>
          <BaseButton size="modal-size" @click="submit">
            Apply
          </BaseButton>
        </div>
      </div>
    </div>
  </teleport>
</template>

<style scoped>
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.35);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.modal {
  position: relative;
  width: 750px;
  height: 600px;
  background: white;
  border-radius: 10px;
  border: 1px solid #F2F2F7;

  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 18px 53px;
}

.modal__header {
  font-family: 'Nunito', sans-serif;
  font-weight: 700;
  font-size: 30px;
  color: #0E2040;
  text-align: center;
  margin-bottom: 6px;
}

.modal__footer {
  position: absolute;
  bottom: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 22px;
}

/* Search */
.search {
  width: 637px;
  display: flex;
  justify-content: center;
  gap: 22px;
  margin-bottom: 16px;
}

/* Table */
.table {
  font-family: 'Roboto', sans-serif;
  font-size: 16px;
  line-height: 1.5;
  width: 645px;
  border: 1px solid #F2F2F7;
  border-radius: 10px;
  overflow: hidden;
  background: white;
}

.table__body {
  height: 300px;        /* 5 rows */
  overflow-y: auto;
}

.table__row {
  display: grid;
  grid-template-columns: 80px 1fr 1fr;
  align-items: center;
  height: 60px;
}

.table__head {
  background: #F2F2F7;
  border-bottom: 1px solid #F2F2F7;
  height: 44px;
}

.table__row:not(.table__head):not(:last-child) {
  border-bottom: 1px solid #F2F2F7;
}

.cell {
  padding: 0 16px;
  display: flex;
  align-items: center;
}

.cell--checkbox {
  justify-content: center;
}

.role-select {
  --select-input-height: 42px;
}

.cell--user {
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  gap: 0px;
}

.user-name {
  font-size: 16px;
  line-height: 1.5;
  font-weight: 400;
}

.user-sub {
  font-size: 13px;
  line-height: 1.5;
  color: #8E8E93;
  font-weight: 400;
  margin-top: -4px;
}
</style>