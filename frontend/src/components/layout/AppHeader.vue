<script setup>
  import IconLogo from '@/components/icons/IconLogo.vue';
</script>

<template>
  <header class="app-header">
    <div class="app-header__content">
      <IconLogo />
    </div>
  </header>
</template>

<style scoped>
.app-header {
  height: 72px;
  width: 100%;
  display: flex;
  align-items: center;

  border-bottom: 1px solid rgba(102, 102, 102, 0.25);
}

.app-header__content {
  width: 100%;
  padding: 0 24px; /* typical desktop padding */
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>