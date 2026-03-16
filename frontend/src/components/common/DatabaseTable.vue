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
    const width = col.width || '1fr'

    if (width.includes('fr')) {
      cols.push(`minmax(0, ${width})`)
    } else {
      cols.push(width)
    }
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
  width: 100%;
  display: grid;
  overflow: hidden;
  background: white;
  font-size: 20px;
  line-height: 1.5;
  gap: 5px;
}

.table__row {
  display: grid;
  align-items: center;
  height: 62px;
}

.table__head {
  border: 1px solid #F2F2F7;
  font-weight: 700;
}

.table__row:not(:last-child) {
  border: 1px solid #F2F2F7;
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
