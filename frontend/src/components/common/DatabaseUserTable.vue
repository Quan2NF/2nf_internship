<script setup>
import DatabaseTable from '@/components/common/DatabaseTable.vue'

import IconTrash from '@/components/icons/IconTrash.vue'
import IconPencil from '../icons/IconPencil.vue'

const props = defineProps({
  members: Array
})

const columns = [
  { label: 'Name', key: 'name', width: '2fr' },
  { label: 'Role', key: 'role', width: '1fr' },
  { label: 'Status', key: 'status', width: '120px' },
  { label: 'Action', key: 'action', width: '120px', align: 'center' }
]
</script>

<template>
  <DatabaseTable
    :items="members"
    :columns="columns"
    showIndex
    style="--table-row-gap: 10px;" 
  >
    <template #status="{ item }">
      <span
        class="status"
        :class="item.status ? 'is-active' : 'is-inactive'"
      >
        {{ item.status ? 'Active' : 'Inactive' }}
      </span>
    </template>

    <template #action="{ item }">
      <button
        class="icon-btn icon-btn--edit"
        @click="$emit('edit', item)"
      >
        <IconPencil />
      </button>
      <button
        class="icon-btn icon-btn--delete"
        @click="$emit('delete', item)"
      >
        <IconTrash />
      </button>
    </template>

  </DatabaseTable>
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

.is-active {
  color: #34C759;
}

.is-inactive {
  color: #FF383C;
}

</style>