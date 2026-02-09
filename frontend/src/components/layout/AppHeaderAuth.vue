<script setup>
  import IconLogo from '@/components/icons/IconLogo.vue';
  import IconNotificationBell from '@/components/icons/IconNotificationBell.vue';
  import IconNotificationDot from '@/components/icons/IconNotificationDot.vue';
  import HeaderUserInfo from '@/components/common/HeaderUserInfo.vue';
  import HeaderAvatar from '@/components/common/HeaderAvatar.vue';
</script>

<template>
  <header class="app-header-auth">
    <div class="app-header-auth__content">
      <IconLogo />
      
      <div class="app-header-auth__right">
        <div class="notification">
          <IconNotificationBell class="notification__bell" />
          <IconNotificationDot class="notification__dot" />
        </div>

        <HeaderUserInfo>
          <template #name>Nguyễn Mạnh Quân</template>
          <template #position>PHP Developer</template>
        </HeaderUserInfo>

        <HeaderAvatar />
      </div>
    </div>
  </header>
</template>

<style scoped>
.app-header-auth {
  height: 85px;
  width: 100%;
  display: flex;
  align-items: center;

  border-bottom: 1px solid rgba(102, 102, 102, 0.25);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
}

.app-header-auth__content {
  width: 100%;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.app-header-auth__right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.notification {
  position: relative;
  width: fit-content;
  transform: translateY(-2px);
}

.notification__bell {
  color: #0A3B83;
  display: block;
}

.notification__dot {
  position: absolute;
  top: 0;
  right: 0;
  transform: translate(-100%, 50%);
  color: #D8727D;
}
</style>