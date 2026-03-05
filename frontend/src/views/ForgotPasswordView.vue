<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

import AppHeaderGuest from '@/components/layout/AppHeaderGuest.vue'
import PageTitle from '@/components/common/PageTitle.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import api from '@/axios'

const router = useRouter()

const email = ref('')

const errors = reactive({
  email: ''
})

function validate() {
  errors.email = ''

  if (!email.value) {
    errors.email = 'Email is required'
    return false
  }

  return true
}

async function handleSend() {
  if (!validate()) return

  try {
    await api.get('/sanctum/csrf-cookie')

    const response = await api.post('/auth/forgot-password', {
      email: email.value
    })

    if (response.data.response_code === 'R_CMN_200_01') {
      console.log(
        'If an account with this email exists, a password reset link has been sent.'
      )

      router.push({ name: 'login' })
    }

  } catch (error) {
    console.error(error)
  }
}
</script>

<template>
  <div class="forgot-password-layout">
    <AppHeaderGuest />

    <main class="forgot-password-container">
      <div class="forgot-password-card">
        <PageTitle class="forgot-password-card__title">
          Forgot your password
        </PageTitle>

        <BaseInput
          v-model="email"
          label="Email address"
          type="email"
          :error="errors.email"
          required
        />

        <BaseButton block @click="handleSend">
          Send
        </BaseButton>
      </div>
    </main>
  </div>
</template>

<style scoped>
.forgot-password-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.forgot-password-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding-top: 72px;
  background: #ffffff;
  align-items: center;
}

.forgot-password-card {
  position: relative;

  width: 580px;
  background: #ffffff;

  display: flex;
  flex-direction: column;
  gap: 24px;
}

.forgot-password-card__title {
  margin-bottom: 8px;
}

</style>