<script setup>
  import { ref, reactive } from 'vue'
  import BaseButton from '@/components/base/BaseButton.vue'
  import BaseInput from '@/components/base/BaseInput.vue'
  import BaseLink from '@/components/base/BaseLink.vue'
  import AppHeaderGuest from '@/components/layout/AppHeaderGuest.vue'
  import AppHeaderAuth from '@/components/layout/AppHeaderAuth.vue'
  import BaseTextSearch from '@/components/base/BaseTextSearch.vue'
  import BasePagination from '@/components/common/BasePagination.vue'

  const email = ref('')
  const password = ref('')

  const errors = reactive({
    email: '',
  })

  import BaseCheckbox from '@/components/base/BaseCheckbox.vue'
  import PageTitle from '@/components/common/PageTitle.vue'

  const rememberMe = ref(false)

  const page = ref(10)
</script>

<template>
  <AppHeaderAuth/>

  <h1>You did it!</h1>
  <p>
    Visit <a href="https://vuejs.org/" target="_blank" rel="noopener">vuejs.org</a> to read the
    documentation
  </p>

  <div style="padding: 40px 300px">
    <PageTitle>Log in</PageTitle>

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

    <BaseTextSearch
      v-model="keyword"
      @enter="handleSearch"
    />

    <BasePagination
      :total-items="240"
      :page-size="10"
      v-model:current-page="page"
    />
  </div>
</template>

<style scoped></style>
