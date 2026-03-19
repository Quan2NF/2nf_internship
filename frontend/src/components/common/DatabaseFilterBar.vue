<script setup>
import BaseInput from '../base/BaseInput.vue';
import BaseSelectInput from '../base/BaseSelectInput.vue';
import BaseButton from '../base/BaseButton.vue';

const options = [
  { label: 'Sample', value: 'sample' },
]
</script>

<template>
  <div class="filter-bar">
    <div class="filter-bar__label-text">
      Filter
    </div>
    <div class="filter-bar__text-input">
      <BaseInput/>
    </div>
    <div class="filter-bar__select-input">
      <BaseSelectInput
        :options="options"
      />
    </div>
    <BaseButton class="filter-bar__search-button">
      Search
    </BaseButton>
  </div>
</template>

<style scoped>
.filter-bar {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 25px;
  background: white;
  padding: 25px 17px;
}

.filter-bar__label-text {
  line-height: 1.5;
}

.filter-bar__text-input {
  --input-border-color: #E0E0E0;
  height: 56px;
  width: 400px;
}

.filter-bar__select-input {
  --select-input-color: #E0E0E0;
  width: 400px;
}

.filter-bar__search-button {
  height: 56px;
  padding: 13px 24px;

  font-size: 20px;
  line-height: 1.5;
  font-weight: 400;
}
</style>