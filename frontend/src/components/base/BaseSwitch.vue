<script setup>
const modelValue = defineModel({ type: Boolean })

const props = defineProps({
  disabled: Boolean
})
</script>

<template>
  <label
    class="switch"
    :class="{
      'switch--checked': modelValue,
      'switch--disabled': disabled
    }"
  >
    <input
      type="checkbox"
      class="switch__input"
      v-model="modelValue"
      :disabled="disabled"
    />

    <span class="switch__track"></span>
    <span class="switch__thumb"></span>
  </label>
</template>

<style scoped>
.switch {
  position: relative;
  display: block;
  width: 32px;
  height: 16px;
  cursor: pointer;
}

.switch--disabled {
  cursor: not-allowed;
  opacity: 0.6;
}

/* Hide native checkbox safely */
.switch__input {
  position: absolute;
  opacity: 0;
  inset: 0;
  cursor: inherit;
}

/* Track */
.switch__track {
  position: absolute;
  inset: 0;
  border-radius: 999px;
  background: #ffffff;
  border: 1px solid #DEE2E6;
  box-sizing: border-box;
  transition: all 0.2s ease;
}

/* Thumb */
.switch__thumb {
  position: absolute;
  top: 2px;
  left: 2px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.25);
  transition: transform 0.2s ease, background 0.2s ease;
}

/* ON state */
.switch--checked .switch__track {
  background: #FF383C;
  border-color: #FF383C;
}

.switch--checked .switch__thumb {
  background: #ffffff;
  transform: translateX(16px);
}

.switch__input:focus-visible + .switch__track {
  outline: 2px solid #000;
  outline-offset: 2px;
}
</style>
