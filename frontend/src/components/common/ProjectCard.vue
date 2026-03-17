<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import IconProjectCardQuickAction from '../icons/IconProjectCardQuickAction.vue'
import ProjectCardProgressBar from './ProjectCardProgressBar.vue'

const { id } = defineProps({
  id: [String, Number],
  title: String,
  code: String,
  pmCode: String,
  status: String,
  timeline: String,
  progress: Number,
  tasksDone: Number,
  tasksTotal: Number,
  bugsDone: Number,
  bugsTotal: Number,
  members: Array
})

const emit = defineEmits(['delete', 'edit'])

function handleDelete() {
  emit('delete', id)
}

function handleEdit() {
  emit('edit', id)
}

const menuOpen = ref(false)
const dropdownRef = ref(null)
const buttonRef = ref(null)

function toggleMenu() {
  setTimeout(() => {
    menuOpen.value = !menuOpen.value
  })
}

function handleClickOutside(e) {
  if (
    dropdownRef.value?.contains(e.target) ||
    buttonRef.value?.contains(e.target)
  ) {
    return
  }

  menuOpen.value = false
}

onMounted(() => {
  window.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  window.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <div class="project-card">
    <!-- Header -->
    <div class="project-card__header">
      <h3 class="project-card__title">{{ title }}</h3>

      <div class="menu-wrapper">
        <button class="project-card__menu" @click="toggleMenu">
          <IconProjectCardQuickAction/>
        </button>

        <div v-if="menuOpen" ref="dropdownRef" class="project-card__dropdown">
          <button
            class="dropdown-item"
            @click="handleEdit"
          >
            Edit
          </button>

          <div class="dropdown-divider"></div>

          <button
            class="dropdown-item delete"
            @click="handleDelete"
          >
            Delete
          </button>
        </div>
      </div>
    </div>

    <div class="project-card__info">
      <!-- Meta -->
      <div class="project-card__meta">
        <span class="project-card__code">{{ code }}</span>

        <div class="project-card__people">
          <span><b>PM</b>: {{ pmCode }}</span>

          <div class="project-card__members">
            <span><b>Members</b>:</span>

            <div class="avatars">
              <div
                v-for="(m, i) in members"
                :key="i"
                class="avatar"
                :style="{ background: m.color }"
              >
                {{ m.initials }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="project-card__bars">
        <ProjectCardProgressBar
          title="Progress"
          :current-progress="progress"
          :max-progress="100"
        />

        <ProjectCardProgressBar
          title="Tasks"
          :current-progress="tasksDone"
          :max-progress="tasksTotal"
        />

        <ProjectCardProgressBar
          title="Bugs"
          :current-progress="bugsDone"
          :max-progress="bugsTotal"
        />
      </div>
    </div>

    <div class="project-card__status">
      <span class="badge">{{ status }}</span>
      <span class="timeline"><b>Timeline</b>: {{ timeline }}</span>
    </div>
  </div>
</template>

<style scoped>
.project-card {
  width: 450px;
  height: 375px;
  padding: 21.45px 9px 21.45px 28.3px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 2px 11px rgba(0, 0, 0, 0.25);
  display: flex;
  flex-direction: column;
  gap: 7.2px;
  font-family: Inter, sans-serif;
}

/* Header */
.project-card__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}

.project-card__info {
  display: flex;
  flex-direction: column;
  gap: 18px;
  padding-right: 10px;
  padding-left: 2.7px;

  font-family: 'Inter', sans-serif;
  font-size: 12px;
  font-weight: 500;
  color: #787486;
}

.project-card__title {
  font-family: 'Nunito', sans-serif;
  font-size: 26px;
  font-weight: 600;
  color: #0D062D;
  margin: 0;

  max-width: 28ch;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.project-card__menu {
  background: none;
  border: none;
  cursor: pointer;

  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* Dropdown */

.menu-wrapper {
  position: relative;
}

.project-card__dropdown {
  position: absolute;
  top: 28px;
  right: 10px;

  width: 120px;
  height: auto;

  background: #FFFFFF;
  border: 1px solid rgba(120, 120, 120, 0.2);
  border-radius: 5px;
  
  overflow: hidden;

  display: flex;
  flex-direction: column;
}

.dropdown-item {
  height: 37px;
  display: flex;
  align-items: center;
  padding-left: 12px;

  font-family: 'Inter', sans-serif;
  font-weight: 500;
  font-size: 12px;

  border: none;
  background: none;
  cursor: pointer;
  color: #333333;
}

.dropdown-divider {
  height: 1px;
  background: #F2F2F7;
}

.dropdown-item.delete {
  color: #FF383C;
}

.dropdown-item:hover {
  background: #F7F7FA;
}

/* Other styles */

.project-card__code {
  margin-bottom: 20px;
}

.project-card__people {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.project-card__members {
  display: flex;
  align-items: center;
  gap: 15px;
}

.project-card__meta {
  display: flex;
  flex-direction: column;
}

.project-card__bars {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.badge {
  background: #198754;
  color: white;
  padding: 5px 12px;
  border-radius: 999px;

  font-family: 'Roboto', sans-serif;
  font-weight: 400;
  font-size: 15px;
}

.timeline {
  font-size: 12px;
  color: #787486;
}

.avatars {
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  color: white;
  font-size: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  margin-left: -8px;
}

.project-card__status {
  display: flex;
  align-items: center;
  gap: 13.5px;
  margin-top: 16px;
}
</style>