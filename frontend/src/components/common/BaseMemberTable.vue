<script setup>
import BaseTable from '@/components/base/BaseTable.vue'

import IconTrash from '@/components/icons/IconTrash.vue'
// import IconPencil from '../icons/IconPencil.vue'

const props = defineProps({
  members: {
    type: Array,
    default: () => []
  },
  roles: {
    type: Array,
    default: () => []
    // [{ label, value }]
  }
})

const columns = [
  { label: 'Name', key: 'name', width: '2fr' },
  { label: 'Role', key: 'roles', width: '1fr' },
  { label: 'Action', key: 'action', width: '120px', align: 'center' }
]

// ✅ map role codes → labels
function formatRoles(roleCodes) {
  if (!Array.isArray(roleCodes) || !roleCodes.length) return ''

  return roleCodes
    .map(code => {
      const found = props.roles.find(r => r.value === code)
      return found?.label || code
    })
    .join(', ')
}
</script>

<template>
  <BaseTable
    class="table"
    :items="members"
    :columns="columns"
    showIndex
    style="--table-head-weight: 400;"
  >

    <template #roles="{ item }">
      {{ formatRoles(item.roles) }}
    </template>

    <!-- actions -->
    <template #action="{ item }">
      <!-- <button
        class="icon-btn icon-btn--edit"
        @click="$emit('edit', item)"
      >
        <IconPencil />
      </button> -->
      <button
        class="icon-btn icon-btn--delete"
        @click="$emit('delete', item)"
      >
        <IconTrash />
      </button>
    </template>

  </BaseTable>
</template>

<style scoped>
.icon-btn {
  width: 28px;
  height: 28px;
  border-radius: 6px;
  border: none;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--icon-color, black);
  transition: all .15s ease;
}

.icon-btn:hover {
  background: #f3f4f6;
  color: color-mix(
    in srgb,
    var(--icon-color, black) 80%,
    black
  );
}

.icon-btn:active {
  transform: scale(0.95);
}

.icon-btn--delete {
  --icon-color: #FF383C;
}

.icon-btn--delete:hover {
  color: #dc3545;
}

.icon-btn--edit {
  color: #212529;
}

.table {
  font-family: 'Roboto', sans-serif;
  line-height: 1.5;
}
</style>