import { api } from '../lib/api'

export async function login(payload: { email: string; password: string }) {
  return api.post('/login', payload)
}

export async function forgotPassword(payload: { email: string }) {
  return api.post('/forgot-password', payload)
}

export async function resetPassword(payload: {
  token: string
  email: string
  password: string
  password_confirmation: string
}) {
  return api.post('/reset-password', payload)
}

export async function logout() {
  return api.post('/logout')
}
