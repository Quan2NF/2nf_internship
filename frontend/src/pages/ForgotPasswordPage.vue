<script setup lang="ts">
import { ref } from "vue";
import { apiForgotPassword } from "../lib/api";

const email = ref("");
const loading = ref(false);
const ok = ref("");
const error = ref("");

async function onSubmit() {
  loading.value = true;
  ok.value = "";
  error.value = "";
  try {
    const msg = await apiForgotPassword({ email: email.value });
    ok.value = msg;
  } catch (e: any) {
    error.value = e?.message || "REQUEST_FAILED";
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="auth-card">
    <div class="auth-title">Forgot password</div>

    <form @submit.prevent="onSubmit">
      <div class="form-group">
        <div class="label">Email address <span class="required">*</span></div>
        <input class="input" v-model="email" type="email" required />
      </div>

      <button class="btn-primary" type="submit" :disabled="loading">
        {{ loading ? "Sending..." : "Send reset link" }}
      </button>

      <div v-if="ok" class="hint">{{ ok }}</div>
      <div v-if="error" class="error">{{ error }}</div>

      <div class="center-link">
        <router-link class="link-danger" to="/auth/login">Back to login</router-link>
      </div>
    </form>
  </div>
</template>
