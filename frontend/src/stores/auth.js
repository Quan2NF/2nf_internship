import { defineStore } from 'pinia'
import api from '@/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    initialized: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.user,
  },

  actions: {
    async fetchUser() {
      try {
        const response = await api.get('/me')
        this.user = response.data.data
      } catch {
        this.user = null
      } finally {
        this.initialized = true
      }
    },

    async logout() {
      await api.post('/logout')
      this.user = null
    }
  }
})