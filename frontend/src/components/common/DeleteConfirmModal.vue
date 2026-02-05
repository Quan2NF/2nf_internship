<script setup lang="ts">
const props = defineProps<{
  title: string;
  message: string;
  confirmText?: string;
}>();

const emit = defineEmits<{ (e: "close"): void; (e: "confirm"): void }>();
</script>

<template>
  <div class="modal-overlay" @click.self="emit('close')">
    <div class="modal">
      <div class="title">{{ props.title }}</div>
      <div class="msg">{{ props.message }}</div>
      <div class="actions">
        <button class="btn-cancel" @click="emit('close')">Cancel</button>
        <button class="btn-danger" @click="emit('confirm')">{{ props.confirmText ?? "Delete" }}</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.25); display:flex; align-items:center; justify-content:center; z-index:9999; }
.modal{ width:420px; max-width:calc(100vw - 24px); background:#fff; border:1px solid var(--border); border-radius:12px; padding:16px; }
.title{ font-size:18px; font-weight:900; text-align:center; }
.msg{ margin:14px 0; text-align:center; opacity:.8; }
.actions{ display:flex; justify-content:center; gap:12px; }
.btn-cancel{ border:0; padding:10px 22px; border-radius:22px; background:#cfcfcf; color:#fff; font-weight:800; cursor:pointer; }
.btn-danger{ border:0; padding:10px 22px; border-radius:22px; background:#ef3b3b; color:#fff; font-weight:800; cursor:pointer; }
</style>
