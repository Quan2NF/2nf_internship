<script setup>
import { ref, computed, watch } from 'vue'

import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseCheckbox from '@/components/base/BaseCheckbox.vue'

const props = defineProps({
  positions: {
    type: Array,
    default: () => []
  },
  existingPositions: {
    type: Array,
    default: () => [] // [{ id }]
  }
})

const existingIds = computed(() =>
  new Set(props.existingPositions.map(p => p.id))
)

const isOpen = defineModel({ type: Boolean })

const emit = defineEmits(['close', 'add'])

const keyword = ref('')

const filteredPositions = computed(() => {
  const k = keyword.value.trim().toLowerCase()

  return props.positions
    .filter(pos => !existingIds.value.has(pos.id)) // remove already assigned
    .filter(pos => {
      if (!k) return true
      return pos.name.toLowerCase().includes(k)
    })
})

const selected = ref({}) // { positionId: true }

function togglePosition(positionId, checked) {
  selected.value[positionId] = checked
}

function submit() {
  const result = Object.entries(selected.value)
    .filter(([_, checked]) => checked)
    .map(([id]) => Number(id))

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
          Assign Position
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
            <div class="cell">Name</div>
          </div>

          <!-- Body -->
          <div class="table__body">
            <div
              v-for="pos in filteredPositions"
              :key="pos.id"
              class="table__row"
            >
              <div class="cell cell--checkbox">
                <BaseCheckbox
                  variant="red"
                  :model-value="selected[pos.id] || false"
                  @update:model-value="val => togglePosition(pos.id, val)"
                />
              </div>

              <div class="cell">
                {{ pos.name }}
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
  bottom: 32px;
  display: flex;
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
  height: 294px;
  overflow-y: auto;
}

.table__row {
  display: grid;
  grid-template-columns: 80px 1fr;
  align-items: center;
  height: 42px;
}

.table__head {
  background: #F2F2F7;
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