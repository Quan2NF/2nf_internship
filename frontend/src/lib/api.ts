import axios, { AxiosError } from "axios";

/**
 * Axios instance for Laravel backend.
 * Use relative baseURL so Vite proxy can forward requests to Laravel (Docker/nginx).
 */
export const api = axios.create({
  baseURL: "", // keep relative for Vite proxy
  withCredentials: true,
  timeout: 15000,
  headers: {
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
});

type LaravelErrorPayload = {
  message?: string;
  errors?: Record<string, unknown>;
};

function pickFirstValidationMessage(errors: Record<string, unknown>): string | null {
  const keys = Object.keys(errors);
  const firstKey = keys.length > 0 ? keys[0] : undefined;
  if (!firstKey) return null;

  const first = errors[firstKey];
  if (Array.isArray(first) && typeof first[0] === "string") return first[0];
  if (typeof first === "string") return first;

  return null;
}

/**
 * Extract a user-friendly error message from Axios/Laravel error responses.
 */
export function getErrorMessage(err: unknown): string {
  if (axios.isAxiosError(err)) {
    const ax = err as AxiosError<LaravelErrorPayload>;

    // network/timeout
    if (!ax.response) {
      if (ax.code === "ECONNABORTED") return "Request timeout. Please try again.";
      return ax.message || "Network error. Please check your connection.";
    }

    const data = ax.response.data;

    if (data?.errors && typeof data.errors === "object" && data.errors !== null) {
      const msg = pickFirstValidationMessage(data.errors as Record<string, unknown>);
      if (msg) return msg;
    }

    if (typeof data?.message === "string" && data.message.trim()) return data.message;

    return `Request failed (${ax.response.status})`;
  }

  if (err instanceof Error) return err.message;
  return "Something went wrong";
}

/** Login API: POST /login */
export async function apiLogin(payload: { email: string; password: string; remember?: boolean }) {
  const res = await api.post("/login", payload);
  return res.data;
}

/** Forgot password API: POST /forgot-password */
export async function apiForgotPassword(payload: { email: string }) {
  const res = await api.post("/forgot-password", payload);
  return res.data;
}

/** Reset password API: POST /reset-password */
export async function apiResetPassword(payload: {
  email: string;
  token: string;
  password: string;
  password_confirmation: string;
}) {
  const res = await api.post("/reset-password", payload);
  return res.data;
}
