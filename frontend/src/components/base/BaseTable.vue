<script setup>

const props = defineProps({
  items: {
    type: Array,
    required: true
  },
  columns: {
    type: Array,
    required: true
    /**
     * [
     *   { label: 'Name', key: 'name' },
     *   { label: 'Role', key: 'role' }
     * ]
     */
  },
  showIndex: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['delete'])

function gridTemplate() {
  const cols = []

  if (props.showIndex) cols.push('80px')

  props.columns.forEach(col => {
    cols.push(col.width || '1fr')
  })

  return cols.join(' ')
}
</script>

<template>
  <div class="table">
    <!-- Header -->
    <div
      class="table__head table__row"
      :style="{ gridTemplateColumns: gridTemplate() }"
    >
      <div v-if="showIndex" class="cell cell--no">No</div>

      <div
        v-for="col in columns"
        :key="col.key"
        class="cell"
        :class="`cell--${col.align || 'left'}`"
      >
        {{ col.label }}
      </div>
    </div>

    <!-- Body -->
    <template v-if="items.length">
      <div
        v-for="(item, index) in items"
        :key="item.id || index"
        class="table__row"
        :style="{ gridTemplateColumns: gridTemplate() }"
      >
        <div v-if="showIndex" class="cell">
          {{ index + 1 }}
        </div>

        <div
          v-for="col in columns"
          :key="col.key"
          class="cell"
          :class="`cell--${col.align || 'left'}`"
        >
          <slot :name="col.key" :item="item">
            {{ item[col.key] }}
          </slot>
        </div>
      </div>
    </template>

    <div v-else class="table__empty">
      No data found
    </div>
  </div>
</template>

<style scoped>
.table {
  width: 800px;
  border: 1px solid #F2F2F7;
  border-radius: var(--table-radius, 10px);
  overflow: hidden;
  background: white;
  font-size: 14px;
}

.table__row {
  display: grid;
  grid-template-columns: 80px 1fr 1fr 80px;
  align-items: center;
  height: 44px;
}

.table__head {
  font-weight: var(--table-head-weight, 600);
}

.table__row:not(:last-child) {
  border-bottom: 1px solid #F2F2F7;
}

.cell {
  padding: 0 16px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.cell--action {
  display: flex;
  justify-content: center;
}

.cell--left {
  justify-self: start;
  text-align: left;
}

.cell--center {
  display: flex;
  justify-content: center;
  align-items: center;
}

.cell--right {
  justify-self: end;
  text-align: right;
}

/* Hover row */
.table__row:hover:not(.table__head) {
  background: #f9fafb;
}

/* Empty */
.table__empty {
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
}
</style>
