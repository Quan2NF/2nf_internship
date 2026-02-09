<script setup lang="ts">
import { ref } from 'vue'
import IconSearch from '@/components/icons/IconSearch.vue'

const props = defineProps<{
  modelValue?: string
  placeholder?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
  (e: 'enter', value: string): void
}>()

const inputRef = ref<HTMLInputElement | null>(null)

const onInput = (e: Event) => {
  const value = (e.target as HTMLInputElement).value
  emit('update:modelValue', value)
}

const onKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Enter') {
    emit('enter', props.modelValue ?? '')
  }
}
</script>

<template>
  <div class="search-box">
    <IconSearch class="search-icon" />
    <input
      ref="inputRef"
      type="text"
      :value="modelValue"
      :placeholder="placeholder || 'Search'"
      class="text-search"
      @input="onInput"
      @keydown="onKeydown"
    />
  </div>
</template>

<style scoped>
.search-box {
  width: 417px;
  height: 44px;
  background: #fefefe;
  border: 1px solid #9a93b3;
  border-radius: 6px;
  display: flex;
  align-items: center;
  padding: 0 12px;
  box-sizing: border-box;
}

.text-search {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  padding-left: 15px; /* space between icon and placeholder text */

  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 400;
  color: #000000;
}

.text-search::placeholder {
  color: #787486;
  opacity: 1;
}

.search-icon {
  width: 18px;
  height: 18px;
  color: #787486;
}
</style>