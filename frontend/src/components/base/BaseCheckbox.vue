<script setup>
import { computed } from 'vue'
import IconCheckboxChecked from '@/components/icons/IconCheckboxChecked.vue'
import IconCheckboxUnchecked from '@/components/icons/IconCheckboxUnchecked.vue'

const props = defineProps({
  modelValue: Boolean,
  label: String,
  hint: String,
  error: String,
  disabled: Boolean,
  id: String,
  required: Boolean,
})

const emit = defineEmits(['update:modelValue'])

const internalId = `checkbox-${Math.random().toString(36).slice(2, 9)}`
const computedId = computed(() => props.id || internalId)

const toggle = () => {
  if (props.disabled) return
  emit('update:modelValue', !props.modelValue)
}
</script>

<template>
  <div class="base-checkbox">
    <label :for="computedId" class="base-checkbox__wrapper">
      <!-- Accessible native checkbox -->
      <input
        :id="computedId"
        type="checkbox"
        class="base-checkbox__input"
        :checked="modelValue"
        :disabled="disabled"
        :aria-invalid="!!error"
        @change="toggle"
      />

      <!-- ICON CONTROL -->
      <span
        class="base-checkbox__icon"
        :class="{
          'is-checked': modelValue,
          'is-disabled': disabled,
          'is-error': error
        }"
      >
        <IconCheckboxChecked v-if="modelValue" />
        <IconCheckboxUnchecked v-else />
      </span>

      <!-- Label -->
      <span v-if="label" class="base-checkbox__label">
        {{ label }}
        <span v-if="required" class="base-checkbox__required">*</span>
      </span>
    </label>

    <p v-if="hint && !error" class="base-checkbox__hint">{{ hint }}</p>
    <p v-if="error" class="base-checkbox__error">{{ error }}</p>
  </div>
</template>

<style scoped>
  .base-checkbox {
    display: flex;
    flex-direction: column;
    gap: 6px;
    font-family: inherit;
  }

  .base-checkbox__wrapper {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
  }

  /* Hide native checkbox completely */
  .base-checkbox__input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
  }

  /* Icon container */
  .base-checkbox__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.15s ease, opacity 0.2s;
    color: rgba(102, 102, 102, 0.35);
  }

  /* Ensure all icons are 24×24 */
  .base-checkbox__icon svg {
    width: 24px;
    height: 24px;
  }

  /* Focus ring (keyboard users) */
  .base-checkbox__input:focus-visible + .base-checkbox__icon {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
    border-radius: 6px;
  }

  .base-checkbox__icon.is-checked {
    color: #111111; /* or your primary */
  }

  /* Disabled */
  .base-checkbox__icon.is-disabled {
    opacity: 0.5;
  }

  /* Error tint (optional if your icon uses currentColor) */
  .base-checkbox__icon.is-error {
    color: #ef4444;
  }

  /* Label */
  .base-checkbox__label {
    font-size: 16px;
    font-weight: 400;
    color: #333333;
  }

  .base-checkbox__required {
    color: #FF0000;
    margin-left: 4px;
  }

  .base-checkbox__hint {
    font-size: 12px;
    color: #6b7280;
  }

  .base-checkbox__error {
    font-size: 12px;
    color: #ef4444;
  }
</style>
