import { createRouter, createWebHistory } from 'vue-router'
import ProjectsView from '@/views/ProjectsView.vue'
import ProjectCreateView from '@/views/ProjectCreateView.vue'
import { useAuthStore } from '@/stores/auth'
import UsersView from '@/views/UsersView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: () => {
        const auth = useAuthStore()
        return auth.isAuthenticated ? '/projects' : '/login'
      }
    },
    {
      path: '/projects',
      name: 'projects.index',
      component: ProjectsView,
      meta: { requiresAuth: true },
    },
    {
      path: '/projects/new',
      name: 'projects.create',
      component: ProjectCreateView,
      meta: { requiresAuth: true },
    },
    {
      path: '/users',
      name: 'users.index',
      component: UsersView,
      meta: {requiresAuth: true},
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue')
    },
    {
      path: '/forgot-password',
      name: 'forgot-password',
      component: () => import('@/views/ForgotPasswordView.vue')
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: () => import('@/views/ResetPasswordView.vue')
    },
  ],
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()

  if (!auth.initialized) {
    await auth.fetchUser()
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return '/login'
  }

  if (to.path === '/login' && auth.isAuthenticated) {
    return '/projects'
  }
})

export default router
