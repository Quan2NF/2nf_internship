<script setup>
import BaseButton from '@/components/base/BaseButton.vue'

const props = defineProps({
  loading: Boolean
})

const isOpen = defineModel({ type: Boolean })
const emit = defineEmits(['confirm'])

function close() {
  isOpen.value = false
}

function confirmDelete() {
  emit('confirm')
}
</script>

<template>
  <teleport to="body">
    <div v-if="isOpen" class="overlay" @click.self="close">
      <div class="modal">
        <!-- Message -->
        <span class="modal__text">Are you sure you want to delete it?</span>

        <!-- Buttons -->
        <div class="modal__actions">
          <BaseButton
            class="btn-cancel"
            @click="close"
          >
            Cancel
          </BaseButton>

          <BaseButton
            class="btn-delete"
            @click="confirmDelete"
          >
            Delete
          </BaseButton>
        </div>
      </div>
    </div>
  </teleport>
</template>

<style scoped>
/* Dark backdrop */
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.35);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

/* Modal panel */
.modal {
  position: relative;
  width: 450px;
  height: 200px;
  background: #FFFFFF;
  border: 1px solid #404040;
  border-radius: 10px;

  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 56px 0;
  gap: 30px;
}

/* Text */
.modal__text {
  width: 265px;
  font-family: 'Inter', sans-serif;
  font-weight: 500;
  font-size: 16px;
  text-align: center;
  color: #000;
}

/* Buttons row */
.modal__actions {
  display: flex;
  justify-content: center;
  gap: 22px;
  width: 100%;
}

/* Cancel button style override */
.btn-cancel {
  width: 123px;
  height: 56px;

  background: #CCCCCC;
  border: 1px solid #F2F2F7;
  color: #FFFFFF;

  font-size: 16px;
  font-weight: 500;
}

/* Delete button style override */
.btn-delete {
  width: 123px;
  height: 56px;

  background: #FF383C;
  border: 1px solid #FF383C;
  color: #FFFFFF;

  font-size: 16px;
  font-weight: 500;
}
</style>
