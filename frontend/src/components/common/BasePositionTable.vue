<script setup>
import BaseTable from '@/components/base/BaseTable.vue'
import IconTrash from '@/components/icons/IconTrash.vue'

const props = defineProps({
  positions: {
    type: Array,
    default: () => []
  }
})

const columns = [
  { label: 'Name', key: 'name', width: '1fr' },
  { label: 'Action', key: 'action', width: '120px', align: 'center' }
]
</script>

<template>
  <BaseTable
    class="table"
    :items="positions"
    :columns="columns"
    showIndex
    style="--table-head-weight: 400;"
  >
    <!-- actions -->
    <template #action="{ item }">
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

.table {
  font-family: 'Roboto', sans-serif;
  line-height: 1.5;
}
</style>