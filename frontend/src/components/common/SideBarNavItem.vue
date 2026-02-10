<template>
  <RouterLink :to="to" class="nav-item" active-class="nav-item--active" exact-active-class="nav-item--active">
    <component :is="icon" class="nav-item__icon" aria-hidden="true" />
    <span class="nav-item__label">{{ label }}</span>
  </RouterLink>
</template>

<script setup lang="ts">
import type { Component } from 'vue'

defineProps<{
  icon: Component
  label: string
  to: string
}>()
</script>

<style scoped>
.nav-item {
  display: flex;
  align-items: center;
  gap: 0px;

  text-decoration: none;
  color: #5D7285;
  font-family: 'Poppins', sans-serif;
  font-size: 14.64px;
  font-weight: 600;

  padding: 0px 7.32px;

  transition: background 0.15s ease, color 0.15s ease;
}

.nav-item--active {
  color: #FF383C;
}

.nav-item__icon {
  flex-shrink: 0;
}

.nav-item__label {
  white-space: nowrap;
  padding: 7.32px;
}
</style>
