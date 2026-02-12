<script setup>
const modelValue = defineModel()

const props = defineProps({
  value: [String, Number, Boolean],
  name: String,
  disabled: Boolean
})
</script>

<template>
  <label class="radio" :class="{ 'radio--disabled': disabled }">
    <input
      type="radio"
      class="radio__input"
      :value="value"
      :name="name"
      :disabled="disabled"
      v-model="modelValue"
    />

    <svg
      class="radio__ui"
      width="16"
      height="16"
      viewBox="0 0 16 16"
      aria-hidden="true"
    >
      <!-- Outer circle -->
      <circle
        cx="8"
        cy="8"
        r="7.5"
        class="radio__outer"
      />

      <!-- Inner dot -->
      <circle
        cx="8"
        cy="8"
        r="4"
        class="radio__inner"
      />
    </svg>

    <slot />
  </label>
</template>

<style scoped>
.radio {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.radio__input {
  position: absolute;
  opacity: 0;
  inset: 0;
  cursor: inherit;
}

/* Outer circle (unchecked) */
.radio__outer {
  fill: white;
  stroke: #000;
  stroke-width: 1;
  transition: all 0.2s ease;
}

/* Inner dot hidden */
.radio__inner {
  fill: white;
  transform: scale(0);
  transform-origin: center;
  transition: transform 0.2s ease;
}

.radio__ui {
  overflow: visible;
}

/* Checked */
.radio__input:checked + .radio__ui .radio__outer {
  fill: #DC3545;
  stroke: #DC3545;
}

.radio__input:checked + .radio__ui .radio__inner {
  transform: scale(1);
}

/* Hover */
.radio:not(.radio--disabled):hover
.radio__input:not(:checked) + .radio__ui
.radio__outer {
  stroke: #b02a37;
}

/* Focus */
.radio__input:focus-visible + .radio__ui {
  outline: 2px solid #000;
  outline-offset: 2px;
}

/* Disabled */
.radio--disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
