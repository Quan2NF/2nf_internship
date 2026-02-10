<script setup>
  import IconSidebarProjects from '../icons/IconSidebarProjects.vue';
  import IconSidebarTasks from '../icons/IconSidebarTasks.vue';
  import IconSidebarWorkLogs from '../icons/IconSidebarWorkLogs.vue';
  import IconSidebarPerformance from '../icons/IconSidebarPerformance.vue';
  import IconSidebarOthers from '../icons/IconSidebarOthers.vue';

  import SideBarNavItem from '../common/SideBarNavItem.vue';
</script>

<template>
  <aside class="app-sidebar">
    <nav class="sidebar-nav">
      <SideBarNavItem
        :icon="IconSidebarProjects"
        label="Projects"
        to="/projects"
      />
      <SideBarNavItem
        :icon="IconSidebarTasks"
        label="Tasks"
        to="/tasks"
      />
      <SideBarNavItem
        :icon="IconSidebarWorkLogs"
        label="Work Logs"
        to="/logs"
      />
      <SideBarNavItem
        :icon="IconSidebarPerformance"
        label="Performance"
        to="/performance"
      />
      <SideBarNavItem
        :icon="IconSidebarOthers"
        label="Users"
        to="/users"
      />
      <SideBarNavItem
        :icon="IconSidebarOthers"
        label="Positions"
        to="/positions"
      />
      <SideBarNavItem
        :icon="IconSidebarOthers"
        label="Roles"
        to="/roles"
      />
    </nav>
  </aside>
</template>

<style scoped>
  .app-sidebar {
    width: 314px;
    flex-shrink: 0;
    background-color: #fff;
    box-shadow: 0 3px 35.16px rgba(0, 0, 0, 0.08);
    z-index: 5;
  }

  /* NAV LIST */
  .sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 14.64px;          /* vertical spacing between items */
    padding-top: 16px;  /* distance from header */
    padding-left: 8px;  /* distance from left edge */
  }
</style>