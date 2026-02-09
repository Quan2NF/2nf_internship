<script setup>
const props = defineProps({
  href: String,
  to: [String, Object], // for router-link
  external: Boolean,    // force <a>
})
</script>

<template>
  <!-- Vue Router link -->
  <RouterLink
    v-if="to && !external"
    :to="to"
    class="base-link"
  >
    <slot />
  </RouterLink>

  <!-- Normal anchor -->
  <a
    v-else
    :href="href"
    class="base-link"
    target="_blank"
    rel="noopener noreferrer"
  >
    <slot />
  </a>
</template>

<style scoped>
.base-link {
  font-size: 16px;
  font-weight: 500;
  color: #FF383C;
  text-decoration: underline;
  cursor: pointer;
  transition: opacity 0.2s ease;
}

.base-link:hover {
  opacity: 0.8;
}

.base-link:active {
  opacity: 0.6;
}
</style>