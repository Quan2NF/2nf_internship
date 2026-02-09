<template>
  <div class="header-user-info">
    <div class="header-user-info__name">
      <slot name="name" />
    </div>
    <div class="header-user-info__position">
      <slot name="position" />
    </div>
  </div>
</template>

<style scoped>
.header-user-info {
  display: flex;
  flex-direction: column;
  gap: 3px;
  font-family: 'Inter', sans-serif;
  align-items: flex-end;
}

/* Employee name */
.header-user-info__name {
  font-size: 16px;
  font-weight: 400;
  color: #0D062D;
}

/* Employee position */
.header-user-info__position {
  font-size: 14px;
  font-weight: 400;
  color: #787486;
}
</style>