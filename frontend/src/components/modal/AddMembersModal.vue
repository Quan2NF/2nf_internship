<script setup>
import { ref } from 'vue'

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
            v-model="keyword"
          />
          <BaseButton size="modal-size">
            Search
          </BaseButton>
        </div>

        <!-- Table -->
        <div class="table">
          <!-- Header -->
          <div class="table__head table__row">
            <div class="cell cell--checkbox"></div>
            <div class="cell">User Name</div>
            <div class="cell">Role</div>
          </div>

          <!-- Body -->
          <div
            v-for="user in users"
            :key="user.id"
            class="table__row"
          >
            <div class="cell cell--checkbox">
              <BaseCheckbox
                :model-value="selected[user.id]?.checked || false"
                @update:model-value="val => toggleUser(user.id, val)"
              />
            </div>

            <div class="cell">
              {{ user.name }}
            </div>

            <div class="cell">
              <BaseSelectInput
                :model-value="selected[user.id]?.role || null"
                :options="roles"
                placeholder="Select role"
                @update:model-value="val => changeRole(user.id, val)"
              />
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
  margin-bottom: 16px;
}

.modal__footer {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 22px;
  margin-top: 20px;
}

/* Search */
.search {
  width: 637px;
  display: flex;
  gap: 22px;
  margin-bottom: 16px;
}

/* Table */
.table {
  width: 645px;
  border: 1px solid #F2F2F7;
  border-radius: 10px;
  overflow: hidden;
  background: white;
}

.table__row {
  display: grid;
  grid-template-columns: 60px 1fr 1fr;
  align-items: center;
  min-height: 44px;
}

.table__head {
  background: #F2F2F7;
  border-bottom: 1px solid #F2F2F7;
  font-weight: 600;
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
</style>