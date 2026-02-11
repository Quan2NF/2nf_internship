<script setup>
import { computed } from 'vue'
import IconCalendar from '@/components/icons/IconCalendar.vue'
import { VueDatePicker } from '@vuepic/vue-datepicker'
import { enGB } from 'date-fns/locale'
import '@vuepic/vue-datepicker/dist/main.css'

const modelValue = defineModel({ type: String }) // yyyy-mm-dd

const props = defineProps({
  label: String,
  name: String,
  placeholder: String,
  error: String,
  hint: String,
  required: Boolean,
  disabled: Boolean,
  id: String,
  min: String,
  max: String,
})

const internalId = `date-${Math.random().toString(36).slice(2, 9)}`
const computedId = computed(() => props.id || internalId)

/* Convert between Date (picker) and yyyy-mm-dd (model) */
const internalValue = computed({
  get: () => (modelValue.value ? new Date(modelValue.value) : null),
  set(val) {
    if (!val) {
      modelValue.value = null
      return
    }
    const yyyy = val.getFullYear()
    const mm = String(val.getMonth() + 1).padStart(2, '0')
    const dd = String(val.getDate()).padStart(2, '0')
    modelValue.value = `${yyyy}-${mm}-${dd}`
  },
})
</script>

<template>
  <div class="base-input">
    <div v-if="label" class="base-input__header">
      <label :for="computedId" class="base-input__label">
        {{ label }}
        <span v-if="required" class="base-input__required">*</span>
      </label>
      <slot name="headerRight" />
    </div>

    <div class="base-input__control">
      <VueDatePicker
        v-model="internalValue"
        :locale="enGB"
        :formats="{ input: 'dd/MM/yyyy' }"
        :time-config="{ enableTimePicker: false }"
        :auto-apply="true"
        :disabled="disabled"
        :min-date="min"
        :max-date="max"
        :input-attrs="{
          id: computedId,
          name,
          placeholder,
          readonly: true,
          'aria-invalid': !!error,
          clearable: false,
        }"
        :class="['base-date-picker', { 'has-error': error }]"
      >
        <template #input-icon>
          <IconCalendar />
        </template>
      </VueDatePicker>
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

.base-input__required { color: red; }

.base-input__control { position: relative; width: 100%; }

.base-input__hint { font-size: 12px; color: #6b7280; }
.base-input__error { font-size: 12px; color: #ef4444; }

/* ===== DatePicker Theming ===== */

.base-date-picker { width: 100%; }

.base-date-picker :deep(.dp__input_wrap) { width: 100%; }

.base-date-picker :deep(.dp__input) {
  width: 100%;
  height: 56px;
  padding: 0 12px;
  padding-right: 44px;
  border-radius: 12px;
  border: 1px solid rgba(102,102,102,.35);
  font-size: 14px;
  font-family: 'Poppins', sans-serif;
  background: white;
  transition: border-color .2s, box-shadow .2s;
}

.base-date-picker :deep(.dp__input_focus) {
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgba(99,102,241,.15);
}

.base-date-picker.has-error :deep(.dp__input) {
  border-color: #ef4444;
}

.base-date-picker :deep(.dp__input_disabled) {
  background: #f3f4f6;
  cursor: not-allowed;
}

.base-date-picker :deep(.dp__input_icon) {
  left: auto;
  right: 12px;
}

.base-date-picker :deep(.dp__menu) {
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,.12);
}
</style>
