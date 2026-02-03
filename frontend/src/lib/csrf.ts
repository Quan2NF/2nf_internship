import { api } from './api'

export async function ensureCsrfCookie() {
  // Nếu bạn dùng Sanctum kiểu /sanctum/csrf-cookie thì gọi endpoint đó.
  // Nếu bạn KHÔNG dùng sanctum, bạn có thể bỏ bước này và rely on exempt.
  await api.get('/sanctum/csrf-cookie')
}
