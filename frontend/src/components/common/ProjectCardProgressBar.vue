<script setup>
defineProps({
  title: String,
  currentProgress: Number,
  maxProgress: Number
})
</script>

<template>
<div class="progress_bar">
  <div class="progress_bar__top">
    <span>{{ title }}:</span>
    <span>{{ currentProgress }}/{{ maxProgress }}</span>
  </div>

  <div class="progress_bar__empty">
    <div class="progress_bar__fill" :style="{ width: (currentProgress / maxProgress) * 100 + '%' }"></div>
  </div>
</div>
</template>

<style scoped>
.progress_bar {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.progress_bar__top {
  display: flex;
  justify-content: space-between;
}

.progress_bar__empty {
  height: 16px;
  background: #e9ecef;
  border-radius: 4px;
  overflow: hidden;
}

.progress_bar__fill {
  height: 100%;
  background: #DC3545;
}
</style>