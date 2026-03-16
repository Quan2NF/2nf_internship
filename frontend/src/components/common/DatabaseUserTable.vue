<script setup>
import DatabaseTable from '@/components/common/DatabaseTable.vue'

import IconTrash from '@/components/icons/IconTrash.vue'
import IconPencil from '@/components/icons/IconPencil.vue'

defineProps({
  users: {
    type: Array,
    default: () => []
  }
})

const columns = [
  { label: 'No', key: 'no', width: '70px', align: 'center' },
  { label: 'Code', key: 'code', width: '150px' },
  { label: 'Name', key: 'name', width: '1fr' },
  { label: 'Email', key: 'email', width: '1fr' },
  { label: 'Phone number', key: 'phone', width: '1fr' },
  { label: 'Join date', key: 'joinDate', width: '140px' },
  { label: 'Status', key: 'status', width: '120px', align: 'center' },
  { label: 'Action', key: 'action', width: '120px', align: 'center' }
]
</script>

<template>
  <DatabaseTable
    :items="users"
    :columns="columns"
    style="--table-row-gap: 10px;"
  >

    <template #status="{ item }">
      <span
        class="status"
        :class="item.status === 'Active' ? 'is-active' : 'is-inactive'"
      >
        {{ item.status }}
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

.email {
  display: block;
  overflow: hidden;
  min-width: 0;
  text-overflow: ellipsis;
  white-space: nowrap;
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