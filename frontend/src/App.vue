<script setup>
  import { ref, reactive } from 'vue'
  import BaseButton from '@/components/base/BaseButton.vue'
  import BaseInput from '@/components/base/BaseInput.vue'
  import BaseLink from '@/components/base/BaseLink.vue'
  import AppHeader from '@/components/layout/AppHeader.vue'

  const email = ref('')
  const password = ref('')

  const errors = reactive({
    email: '',
  })

  import BaseCheckbox from '@/components/base/BaseCheckbox.vue'

  const rememberMe = ref(false)
</script>

<template>
  <AppHeader/>

  <h1>You did it!</h1>
  <p>
    Visit <a href="https://vuejs.org/" target="_blank" rel="noopener">vuejs.org</a> to read the
    documentation
  </p>

  <div style="padding: 40px">
    <BaseButton>Log in</BaseButton>

    <BaseButton block>
      Submit Form
    </BaseButton>

    <BaseButton disabled>
      Disabled Button
    </BaseButton>

    <BaseInput
      v-model="email"
      label="Email"
      type="email"
      placeholder="you@example.com"
      :error="errors.email"
    />

    <BaseInput
      v-model="password"
      label="Password"
      type="password"
      showToggle
      required
    />

    <BaseCheckbox
      v-model="rememberMe"
      label="Remember me"
    />

    <BaseLink to="/login">Go to Login</BaseLink>
  </div>
</template>

<style scoped></style>
