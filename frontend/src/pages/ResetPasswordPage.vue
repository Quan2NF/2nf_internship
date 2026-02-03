<script setup lang="ts">
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { apiResetPassword, getErrorMessage } from "../lib/api";

const route = useRoute();
const router = useRouter();

const token = computed(() => String(route.params.token || ""));
const email = ref(String(route.query.email || ""));

const password = ref("");
const password_confirmation = ref("");

const show1 = ref(false);
const show2 = ref(false);

const loading = ref(false);
const ok = ref("");
const error = ref("");

async function onSubmit() {
  loading.value = true;
  ok.value = "";
  error.value = "";

  try {
    const res = await apiResetPassword({
      email: email.value,
      token: token.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });

    ok.value = res?.message || "PASSWORD_RESET_SUCCESS";
    router.push("/auth/login");
  } catch (e: unknown) {
    error.value = getErrorMessage(e);
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="auth-card">
    <div class="auth-title">Reset password</div>

    <form @submit.prevent="onSubmit">
      <div class="form-group">
        <div class="label">Email address <span class="required">*</span></div>
        <input class="input" v-model="email" type="email" required />
      </div>

      <div class="form-group">
        <div class="label">New password <span class="required">*</span></div>
        <div class="password-wrap">
          <input
            class="input"
            v-model="password"
            :type="show1 ? 'text' : 'password'"
            autocomplete="new-password"
            required
          />
          <span class="password-toggle" @click="show1 = !show1">
            {{ show1 ? "Hide" : "Show" }}
          </span>
        </div>
      </div>

      <div class="form-group">
        <div class="label">Confirm password <span class="required">*</span></div>
        <div class="password-wrap">
          <input
            class="input"
            v-model="password_confirmation"
            :type="show2 ? 'text' : 'password'"
            autocomplete="new-password"
            required
          />
          <span class="password-toggle" @click="show2 = !show2">
            {{ show2 ? "Hide" : "Show" }}
          </span>
        </div>
      </div>

      <button class="btn-primary" type="submit" :disabled="loading">
        {{ loading ? "Resetting..." : "Reset password" }}
      </button>

      <div v-if="ok" class="hint">{{ ok }}</div>
      <div v-if="error" class="error">{{ error }}</div>

      <div class="center-link">
        <router-link class="link-danger" to="/auth/login">Back to login</router-link>
      </div>
    </form>
  </div>
</template>
