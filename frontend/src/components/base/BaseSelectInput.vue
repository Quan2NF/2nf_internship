<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import IconChevronDown from '@/components/icons/IconChevronDown.vue'

const modelValue = defineModel()

const props = defineProps({
  label: String,
  options: { type: Array, required: true }, // [{ label, value }]
  placeholder: { type: String, default: '' },
  error: String,
  hint: String,
  required: Boolean,
  disabled: Boolean,
})

const isOpen = ref(false)
const root = ref(null)
const trigger = ref(null)
const dropdown = ref(null)
const dropdownStyle = ref({})

const selectedOption = computed(() =>
  props.options.find(o => o.value === modelValue.value)
)

function positionDropdown() {
  if (!trigger.value) return

  const rect = trigger.value.getBoundingClientRect()
  const spaceBelow = window.innerHeight - rect.bottom
  const openUp = spaceBelow < 260

  dropdownStyle.value = {
    position: 'fixed',
    left: rect.left + 'px',
    width: rect.width + 'px',
    top: openUp ? rect.top - 8 + 'px' : rect.bottom + 8 + 'px',
    transform: openUp ? 'translateY(-100%)' : 'none',
    zIndex: 9999,
  }
}

function open() {
  if (props.disabled) return
  isOpen.value = true
  nextTick(positionDropdown)
  window.addEventListener('resize', positionDropdown)
  window.addEventListener('scroll', positionDropdown, true)
}

function close() {
  isOpen.value = false
  window.removeEventListener('resize', positionDropdown)
  window.removeEventListener('scroll', positionDropdown, true)
}

function toggle() {
  isOpen.value ? close() : open()
}

function select(option) {
  modelValue.value = option.value
  close()
}

function onClickOutside(e) {
  if (!root.value?.contains(e.target)) close()
}

function onKeydown(e) {
  if (props.disabled) return

  switch (e.key) {
    case 'Enter':
    case ' ':
    case 'ArrowDown':
      e.preventDefault()
      if (!isOpen.value) open()
      break

    case 'Escape':
      close()
      break
  }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', onClickOutside))
</script>

<template>
  <div class="base-input" ref="root">
    <div v-if="label" class="base-input__header">
      <label class="base-input__label">
        {{ label }}
        <span v-if="required" class="base-input__required">*</span>
      </label>
      <slot name="headerRight" />
    </div>

    <!-- Trigger -->
    <div class="select-wrapper">
      <div
        ref="trigger"
        class="select"
        :class="{ open: isOpen, error: error, disabled }"
        tabindex="0"
        role="combobox"
        aria-haspopup="listbox"
        :aria-expanded="isOpen"
        @click="toggle"
        @keydown="onKeydown"
      >
        <span v-if="selectedOption" class="select__value">
          {{ selectedOption.label }}
        </span>
        <span v-else class="select__placeholder">
          {{ placeholder }}
        </span>
      </div>

      <IconChevronDown class="base-input__toggle" :class="{ 'is-open': isOpen }"/>
    </div>

    <!-- Dropdown -->
    <teleport to="body">
      <transition name="fade">
        <div
          v-if="isOpen"
          ref="dropdown"
          class="dropdown"
          :style="dropdownStyle"
          role="listbox"
        >
          <div
            v-for="opt in options"
            :key="opt.value"
            class="dropdown__item"
            :class="{ active: modelValue === opt.value }"
            role="option"
            :aria-selected="modelValue === opt.value"
            @click="select(opt)"
          >
            {{ opt.label }}
          </div>
        </div>
      </transition>
    </teleport>

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
  color: var(--select-input-label-color, #666);
}

.base-input__required {
  color: red;
}

.select-wrapper {
  position: relative;
  width: 100%;
}

.select {
  height: var(--select-input-height, 56px);
  border-radius: 12px;
  border: 1px solid var(--select-input-color, rgba(102,102,102,.35));
  padding: 0 12px;
  padding-right: 44px;
  display: flex;
  align-items: center;
  position: relative;
  background: white;
  cursor: pointer;
  font-size: 14px;
}

.select.open {
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgba(99,102,241,.15);
}

.select.error {
  border-color: #ef4444;
}

.select.disabled {
  opacity: .6;
  pointer-events: none;
}

.select__placeholder {
  color: #9ca3af;
}

.select__value {
  color: #111;
}

.base-input__toggle {
  position: absolute;
  top: 50%;
  right: 12px;
  background: none;
  border: none;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #666;

  transform: translateY(-50%) rotate(0deg);
  transform-origin: center;
  transition: transform .2s ease;
}


/* When dropdown open */
.base-input__toggle.is-open {
  transform: translateY(-50%) rotate(-180deg);
}

.dropdown {
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,.12);
  overflow: hidden;

  max-height: 240px;
  overflow-y: auto;
}

.dropdown__item {
  padding: 14px 16px;
  font-size: 14px;
  cursor: pointer;
}

.dropdown__item:hover {
  background: #f3f4f6;
}

.dropdown__item.active {
  font-weight: 600;
}

.base-input__hint {
  font-size: 12px;
  color: #6b7280;
}

.base-input__error {
  font-size: 12px;
  color: #ef4444;
}

/* Animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity .12s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
