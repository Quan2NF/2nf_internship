<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  name: String,
  placeholder: String,
  error: String,
  hint: String,
  required: Boolean,
  disabled: Boolean,
  id: String,
  rows: { type: Number, default: 4 }
})

const emit = defineEmits(['update:modelValue'])

const internalId = `textarea-${Math.random().toString(36).slice(2, 9)}`
const computedId = computed(() => props.id || internalId)

const onInput = (e) => emit('update:modelValue', e.target.value)
</script>

<template>
  <div class="base-textarea">
    <!-- Label -->
    <div v-if="label" class="base-textarea__header">
      <label :for="computedId" class="base-textarea__label">
        {{ label }}
        <span v-if="required" class="base-textarea__required">*</span>
      </label>

      <slot name="headerRight" />
    </div>

    <!-- Textarea -->
    <textarea
      :id="computedId"
      :name="name"
      :rows="rows"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :aria-invalid="!!error"
      :class="[
        'base-textarea__field',
        { 'base-textarea__field--error': error }
      ]"
      @input="onInput"
    />

    <p v-if="hint && !error" class="base-textarea__hint">{{ hint }}</p>
    <p v-if="error" class="base-textarea__error">{{ error }}</p>
  </div>
</template>

<style scoped>
.base-textarea {
  display: flex;
  flex-direction: column;
  gap: 6px;
  width: 100%;
}

.base-textarea__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.base-textarea__label {
  font-size: 16px;
  font-weight: 400;
  color: var(--input-label-color, #666);
}

.base-textarea__required {
  color: red;
}

.base-textarea__field {
  width: 100%;
  min-height: 120px;
  padding: 12px;
  border-radius: 12px;
  border: 1px solid var(--input-border-color, rgba(102,102,102,0.35));
  font-size: 14px;
  font-family: inherit;
  resize: none; 
  transition: border-color 0.2s, box-shadow 0.2s;
}

.base-textarea__field:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
}

.base-textarea__field:disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.base-textarea__field--error {
  border-color: #ef4444;
}

.base-textarea__hint {
  font-size: 12px;
  color: #6b7280;
}

.base-textarea__error {
  font-size: 12px;
  color: #ef4444;
}
</style>