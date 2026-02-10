<script setup lang="ts">
import { computed } from 'vue'
import IconPaginationLeft from '@/components/icons/IconPaginationLeft.vue';
import IconPaginationRight from '@/components/icons/IconPaginationRight.vue';
import IconPaginationEllipsis from '../icons/IconPaginationEllipsis.vue';

const props = defineProps<{
  totalItems: number
  currentPage: number
  pageSize: number
}>()

const emit = defineEmits<{
  (e: 'change', page: number): void
}>()

const totalPages = computed(() =>
  Math.max(1, Math.ceil(props.totalItems / props.pageSize))
)

const goTo = (page: number) => {
  if (page < 1 || page > totalPages.value || page === props.currentPage) return
  emit('change', page)
}

const pages = computed(() => {
  const t = totalPages.value
  const c = props.currentPage

  if (t <= 7) return Array.from({ length: t }, (_, i) => i + 1)

  const result: (number | string)[] = [1]

  if (c > 4) result.push('...')

  const start = Math.max(2, c - 2)
  const end = Math.min(t - 1, c + 2)

  for (let i = start; i <= end; i++) result.push(i)

  if (c < t - 3) result.push('...')

  result.push(t)
  return result
})
</script>

<template>
  <div class="pagination-wrapper">
    <div class="total-text">Total {{ totalItems }} items</div>

    <div class="pagination">
      <button class="page-btn" @click="goTo(currentPage - 1)"><IconPaginationLeft/></button>

      <template v-for="(p, i) in pages" :key="i">
        <div v-if="p === '...'" class="page-btn ellipsis"><IconPaginationEllipsis/></div>

        <button
          v-else
          class="page-btn"
          :class="{ active: p === currentPage }"
          @click="goTo(p as number)"
        >
          {{ p }}
        </button>
      </template>

      <button class="page-btn" @click="goTo(currentPage + 1)"><IconPaginationRight/></button>
    </div>
  </div>
</template>

<style scoped>
.pagination-wrapper {
  height: 56px;
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Total text */
.total-text {
  font-family: 'Roboto', sans-serif;
  font-weight: 400;
  font-size: 20px;
  line-height: 1.5;
  color: #212529;
}

/* Pagination container */
.pagination {
  display: flex;
}

/* Page item */
.page-btn {
  width: 62px;
  height: 56px;
  background: #ffffff;
  border: 1px solid #dee2e6;
  border-right: none;

  display: flex;
  align-items: center;
  justify-content: center;

  font-family: 'Roboto', sans-serif;
  font-weight: 400;
  font-size: 20px;
  line-height: 1.5;
  color: #ff383c;

  cursor: pointer;
}

/* Left-most button (<) */
.pagination > .page-btn:first-child {
  border-top-left-radius: 8px;
  border-bottom-left-radius: 8px;
}

/* Right-most button (>) */
.pagination > .page-btn:last-child {
  border-right: 1px solid #dee2e6;
  border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
}

.page-btn.active {
  background: #ff383c;
  color: #ffffff;
  border: 1px solid #ff383c;
}

.ellipsis {
  cursor: default;
}
</style>
