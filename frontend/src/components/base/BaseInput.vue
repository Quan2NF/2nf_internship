<script setup>
import { computed, ref } from 'vue'
import IconEye from '@/components/icons/IconEye.vue'
import IconEyeOff from '@/components/icons/IconEyeOff.vue'

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  type: { type: String, default: 'text' },
  name: String,
  placeholder: String,
  error: String,
  hint: String,
  required: Boolean,
  disabled: Boolean,
  id: String,
  showToggle: Boolean, // for password only
})

const emit = defineEmits(['update:modelValue'])

const internalId = `input-${Math.random().toString(36).slice(2, 9)}`
const computedId = computed(() => props.id || internalId)

const onInput = (e) => emit('update:modelValue', e.target.value)

/* Password visibility */
const isVisible = ref(false)

const actualType = computed(() => {
  if (props.type !== 'password') return props.type
  return isVisible.value ? 'text' : 'password'
})

const toggleVisibility = () => {
  isVisible.value = !isVisible.value
}
</script>

<template>
  <div class="base-input">
    <!-- Label -->
    <div v-if="label" class="base-input__header">
      <label :for="computedId" class="base-input__label">
        {{ label }}
        <span v-if="required" class="base-input__required">*</span>
      </label>

      <slot name="headerRight" />
    </div>

    <!-- Input wrapper -->
    <div class="base-input__control">
      <input
        :id="computedId"
        :type="actualType"
        :name="name"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :aria-invalid="!!error"
        :class="[
          'base-input__field',
          { 'base-input__field--error': error }
        ]"
        @input="onInput"
      />

      <!-- Eye icon inside input -->
      <button
        v-if="type === 'password' && showToggle"
        type="button"
        class="base-input__toggle"
        @click="toggleVisibility"
        :aria-label="isVisible ? 'Hide password' : 'Show password'"
      >
        <IconEye v-if="!isVisible" />
        <IconEyeOff v-else />
      </button>
    </div>

    <p v-if="hint && !error" class="base-input__hint">{{ hint }}</p>
    <p v-if="error" class="base-input__error">{{ error }}</p>
  </div>
</template>

<style scoped>
.base-input {
  display: flex;
  flex-direction: column;
  gap: 6px;
  width: 100%;
}

.base-input__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.base-input__label {
  font-size: 16px;
  font-weight: 400;
  color: #666;
}

.base-input__required {
  color: red;
}

.base-input__control {
  position: relative;
  width: 100%;
}

.base-input__field {
  width: 100%;
  height: 56px;
  padding: 0 12px;
  padding-right: 44px; /* space for eye icon */
  border-radius: 12px;
  border: 1px solid var(--input-border-color, rgba(102, 102, 102, 0.35));
  font-size: 14px;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.base-input__field:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
}

.base-input__field:disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.base-input__field--error {
  border-color: #ef4444;
}

.base-input__toggle {
  position: absolute;
  top: 50%;
  right: 12px;
  transform: translateY(-50%);
  background: none;
  border: none;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #666;
}

.base-input__hint {
  font-size: 12px;
  color: #6b7280;
}

.base-input__error {
  font-size: 12px;
  color: #ef4444;
}
</style>
