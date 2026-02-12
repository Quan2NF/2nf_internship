<script setup>
import IconTrash from '@/components/icons/IconTrash.vue'

defineProps({
  members: {
    type: Array,
    required: true,
    // [{ id, name, role }]
  }
})

const emit = defineEmits(['delete'])
</script>

<template>
  <div class="table">
    <!-- Header -->
    <div class="table__head table__row">
      <div class="cell cell--no">No</div>
      <div class="cell">Name</div>
      <div class="cell">Role</div>
      <div class="cell cell--action">Action</div>
    </div>

    <!-- Body -->
    <template v-if="members.length">
      <div
        v-for="(member, index) in members"
        :key="member.id"
        class="table__row"
      >
        <div class="cell cell--no">{{ index + 1 }}</div>
        <div class="cell">{{ member.name }}</div>
        <div class="cell">{{ member.role }}</div>

        <div class="cell cell--action">
          <button
            class="icon-btn"
            @click="$emit('delete', member)"
            aria-label="Delete member"
          >
            <IconTrash />
          </button>
        </div>
      </div>
    </template>

    <!-- Empty state -->
    <div v-else class="table__empty">
      No members found
    </div>
  </div>
</template>

<style scoped>
.table {
  width: 800px;
  border: 1px solid #F2F2F7;
  border-radius: 12px;
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
  font-weight: 600;
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

/* Hover row */
.table__row:hover:not(.table__head) {
  background: #f9fafb;
}

/* Icon button */
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
  color: #FF383C;
  transition: all .15s ease;
}

.icon-btn:hover {
  background: #f3f4f6;
  color: #dc3545;
}

.icon-btn:active {
  transform: scale(0.95);
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
