<script setup>
import { computed } from 'vue'
import defaultAvatar from '@/assets/images/default-avatar.png'

const props = defineProps({
  src: String,
  alt: { type: String, default: 'User avatar' },
})

const avatarSrc = computed(() => props.src || defaultAvatar)
</script>

<template>
  <div class="header-avatar">
    <img
      :src="avatarSrc"
      :alt="alt"
      class="header-avatar__img"
    />
  </div>
</template>

<style scoped>
.header-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  border: 1px solid #F0F6FF;
  flex-shrink: 0;
}

.header-avatar__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
</style>