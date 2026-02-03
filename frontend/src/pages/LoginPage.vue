<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { apiLogin } from "../lib/api";


const router = useRouter();

const email = ref("");
const password = ref("");
const remember = ref(true);

const show = ref(false);
const loading = ref(false);
const error = ref("");

async function onSubmit() {
  loading.value = true;
  error.value = "";
  try {
    await apiLogin({ email: email.value, password: password.value, remember: remember.value });
    router.push("/");
  } catch (e: any) {
    error.value = e?.message || "LOGIN_FAILED";
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="auth-card">
    <div class="auth-title">Log in</div>

    <form @submit.prevent="onSubmit">
      <div class="form-group">
        <div class="label">
          Email address <span class="required">*</span>
        </div>
        <input class="input" v-model="email" type="email" autocomplete="username" required />
      </div>

      <div class="form-group">
        <div class="label">
          Password <span class="required">*</span>
        </div>

        <div class="password-wrap">
          <input
            class="input"
            v-model="password"
            :type="show ? 'text' : 'password'"
            autocomplete="current-password"
            required
          />
          <span class="password-toggle" @click="show = !show">
            {{ show ? "Hide" : "Show" }}
          </span>
        </div>
      </div>

      <div class="row">
        <label class="checkbox">
          <input type="checkbox" v-model="remember" />
          Remember me
        </label>
      </div>

      <button class="btn-primary" type="submit" :disabled="loading">
        {{ loading ? "Logging in..." : "Log in" }}
      </button>

      <div class="center-link">
        <router-link class="link-danger" to="/auth/forgot-password">
          Forgot your password
        </router-link>
      </div>

      <div v-if="error" class="error">{{ error }}</div>
    </form>
  </div>
</template>
