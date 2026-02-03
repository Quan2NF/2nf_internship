import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: true,
    port: 5173,
    proxy: {
      '/login': { target: 'http://nginx', changeOrigin: true },
      '/logout': { target: 'http://nginx', changeOrigin: true },
      '/me': { target: 'http://nginx', changeOrigin: true },
      '/forgot-password': { target: 'http://nginx', changeOrigin: true },
      '/reset-password': { target: 'http://nginx', changeOrigin: true },

      '/users': { target: 'http://nginx', changeOrigin: true },
      '/roles': { target: 'http://nginx', changeOrigin: true },
      '/projects': { target: 'http://nginx', changeOrigin: true },
      '/tasks': { target: 'http://nginx', changeOrigin: true },
    },
  },
})
