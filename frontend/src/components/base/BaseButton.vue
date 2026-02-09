<script setup>
defineProps({
  block: Boolean,
  disabled: Boolean,
  size: {
    type: String,
    default: 'md' // sm | md | lg
  },
})
</script>

<template>
  <button
    class="base-btn"
    :class="[
      `base-btn--${size}`,
      { 'base-btn--block': block }
    ]"
    :disabled="disabled"
  >
    <slot />
  </button>
</template>

<style scoped>
.base-btn {
  font-family: inherit;
  padding: 0 24px;
  border-radius: 32px;
  border: 1px solid #FF383C;
  background-color: #FF383C;
  color: white;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

/* SMALL */
.base-btn--sm {
  font-size: 16px;
  height: 56px;
}

/* MEDIUM (default) */
.base-btn--md {
  font-size: 22px;
  height: 64px;
}

/* Full width option */
.base-btn--block {
  width: 100%;
}

/* Hover effect (nice UX) */
.base-btn:hover:not(:disabled) {
  opacity: 0.9;
}

/* Disabled */
.base-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>