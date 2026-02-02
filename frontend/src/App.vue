<script setup>
  import BaseButton from '@/components/base/BaseButton.vue'
</script>

<template>
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
  </div>
</template>

<style scoped></style>
