<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'


import AppHeaderGuest from '@/components/layout/AppHeaderGuest.vue'
import PageTitle from '@/components/common/PageTitle.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseCheckbox from '@/components/base/BaseCheckbox.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseLink from '@/components/base/BaseLink.vue'
import api from '@/axios' 

const auth = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const errors = reactive({
  email: '',
  password: ''
})

function validate() {
  errors.email = ''
  errors.password = ''

  let isValid = true

  if (!email.value) {
    errors.email = 'Email is required'
    isValid = false
  }

  if (!password.value) {
    errors.password = 'Password is required'
    isValid = false
  }

  return isValid
}

async function handleLogin() {
  if (!validate()) return

  try {
    await api.get('/sanctum/csrf-cookie')

    const response = await api.post('/auth/login', {
      email: email.value,
      password: password.value,
      remember: rememberMe.value
    })

    if (response.data.response_code === 'R_CMN_200_01') {
      await auth.fetchUser()
      router.push('/');
    }

  } catch (error) {
    console.error(error)
  }
}
</script>

<template>
  <div class="login-layout">
    <AppHeaderGuest />

    <main class="login-container">
      <div class="login-card">
        <PageTitle class="login-card__title">Log in</PageTitle>

        <BaseInput
          v-model="email"
          label="Email address"
          type="email"
          placeholder=""
          :error="errors.email"
          required
        />

        <BaseInput
          class="login-card__password-input"
          v-model="password"
          label="Password"
          type="password"
          showToggle
          :error="errors.password"
          required
        />

        <div class="login-options">
          <BaseCheckbox
            v-model="rememberMe"
            label="Remember me"
          />
        </div>

        <BaseButton block @click="handleLogin">
          Log in
        </BaseButton>

        <div class="forgot-password">
          <BaseLink to="/forgot-password">
            Forgot your password
          </BaseLink>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
.login-layout {
  min-height: 100dvh;
  display: flex;
  flex-direction: column;
}

.login-container {
  flex: 1;
  display: flex;
  position: relative;
  
  flex-direction: column;
  align-items: center;
  padding-top: 72px;
  background: #ffffff;
}

.login-card {
  position: relative;

  width: 580px;
  background: #ffffff;

  display: flex;
  flex-direction: column;
  gap: 24px;
}

.login-card__title {
  margin-bottom: 8px;
}

.login-card__password-input {
  margin-bottom: -24px;
}

.login-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 40px;
}

.forgot-password {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
