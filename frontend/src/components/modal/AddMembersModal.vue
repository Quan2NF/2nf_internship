<script setup>
import { ref, computed, watch } from 'vue'

import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseCheckbox from '@/components/base/BaseCheckbox.vue'
import BaseSelectInput from '@/components/base/BaseSelectInput.vue'

const props = defineProps({
  users: Array,
  roles: Array,
  existingMembers: {
    type: Array,
    default: () => []
    // [{ user_id, roles: [] }]
  }
})

const existingIds = computed(() =>
  new Set(props.existingMembers.map(m => m.id))
)

const isOpen = defineModel({ type: Boolean })

const emit = defineEmits(['close', 'add'])

const keyword = ref('')

const filteredUsers = computed(() => {
  const k = keyword.value.trim().toLowerCase()

  return props.users
    .filter(user => !existingIds.value.has(user.id)) // 🔥 remove added members
    .filter(user => {
      if (!k) return true
      return (
        user.name.toLowerCase().includes(k) ||
        user.email.toLowerCase().includes(k)
      )
    })
})

const selected = ref({}) // { userId: { checked: true, roles: [] } }

function toggleUser(userId, checked) {
  if (!selected.value[userId]) {
    selected.value[userId] = { checked: false, roles: [] }
  }

  selected.value[userId].checked = checked

  if (checked) {
    // only assign default if no role yet
    if (!selected.value[userId].roles.length) {
      const devRole = props.roles.find(r => r.value === 'DEV')
      if (devRole) {
        selected.value[userId].roles = [devRole.value]
      }
    }
  } else {
    // uncheck → clear roles
    selected.value[userId].roles = []
  }
}

function changeRole(userId, roles) {
  if (!selected.value[userId]) {
    selected.value[userId] = { checked: false, roles: [] }
  }

  const normalized = Array.isArray(roles)
    ? roles
    : roles
      ? [roles]
      : []

  selected.value[userId].roles = normalized

  if (normalized.length) {
    selected.value[userId].checked = true
  } else {
    selected.value[userId].checked = false
  }
}

function submit() {
  const result = Object.entries(selected.value)
    .filter(([_, v]) => v.checked)
    .map(([userId, v]) => ({
      userId: Number(userId),
      roles: v.roles ?? []
    }))

  emit('add', result)
}

function close() {
  isOpen.value = false
}

function search() {
  keyword.value = keyword.value.trim()
}

watch(isOpen, (val) => {
  if (!val) {
    selected.value = {}
    keyword.value = ''
  }
})
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
                  :model-value="selected[user.id]?.roles?.[0] || null"
                  :options="roles"
                  placeholder=""
                  :disabled="!selected[user.id]?.checked"
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