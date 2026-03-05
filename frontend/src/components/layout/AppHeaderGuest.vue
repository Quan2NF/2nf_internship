<script setup>
  import IconLogo from '@/components/icons/IconLogo.vue';
</script>

<template>
  <header class="app-header-guest">
    <div class="app-header-guest__content">
      <IconLogo />
    </div>
  </header>
</template>

<style scoped>
.app-header-guest {
  height: 85px;
  width: 100%;
  display: flex;
  align-items: center;
  background-color: #ffffff;

  border-bottom: 1px solid rgba(102, 102, 102, 0.25);
}

.app-header-guest__content {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>