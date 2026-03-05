<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'

import AppHeaderGuest from '@/components/layout/AppHeaderGuest.vue'
import PageTitle from '@/components/common/PageTitle.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import api from '@/axios'

const router = useRouter()
const route = useRoute()

const email = route.query.email
const token = route.query.token

const passwordNew = ref('')
const passwordConfirmation = ref('')

const errors = reactive({
  passwordNew: '',
  passwordConfirmation: ''
})

function validate() {
  errors.passwordNew = ''
  errors.passwordConfirmation = ''

  let valid = true

  if (!passwordNew.value) {
    errors.password = 'Password is required'
    valid = false
  }

  if (!passwordConfirmation.value) {
    errors.passwordConfirmation = 'Password confirmation is required'
    valid = false
  }

  if (
    passwordNew.value &&
    passwordConfirmation.value &&
    passwordNew.value !== passwordConfirmation.value
  ) {
    errors.passwordConfirmation = 'Passwords do not match'
    valid = false
  }

  return valid
}

async function handleSave() {
  if (!validate()) return

  try {
    await api.get('/sanctum/csrf-cookie')

    const response = await api.post('/auth/reset-password', {
      email,
      password: passwordNew.value,
      password_confirmation: passwordConfirmation.value,
      token,
    })

    if (response.data.response_code === 'R_CMN_200_01') {
      router.push({ name: 'login' })
    }

  } catch (error) {
    console.error(error)
  }
}
</script>

<template>
  <div class="reset-password-layout">
    <AppHeaderGuest />

    <main class="reset-password-container">
      <div class="reset-password-card">
        <PageTitle class="reset-password-card__title">
          Reset password
        </PageTitle>

        <BaseInput
          v-model="passwordNew"
          label="New password"
          type="password"
          :error="errors.passwordNew"
          autocomplete="new-password"
          showToggle
          required
        />

        <BaseInput
          v-model="passwordConfirmation"
          label="Confirm password"
          type="password"
          :error="errors.passwordConfirmation"
          autocomplete="new-password"
          showToggle
          required
        />

        <BaseButton block @click="handleSave">
          Save
        </BaseButton>
      </div>
    </main>
  </div>
</template>

<style scoped>
.reset-password-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.reset-password-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding-top: 72px;
  background: #ffffff;
  align-items: center;
}

.reset-password-card {
  position: relative;

  width: 580px;
  background: #ffffff;

  display: flex;
  flex-direction: column;
  gap: 24px;
}

.reset-password-card__title {
  margin-bottom: 8px;
}
</style>