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

export async function apiMe() {
  const res = await api.get("/me");
  return res.data;
}

export async function apiLogout() {
  const res = await api.post("/logout");
  return res.data;
}

export async function apiGetUsers(params?: { keyword?: string; role?: string; page?: number; per_page?: number }) {
  const res = await api.get("/users", { params });
  return res.data;
}

export async function apiCreateUser(payload: any) {
  const res = await api.post("/users", payload);
  return res.data;
}

export async function apiUpdateUser(id: number, payload: any) {
  const res = await api.patch(`/users/${id}`, payload);
  return res.data;
}

export async function apiDeleteUser(id: number) {
  const res = await api.delete(`/users/${id}`);
  return res.data;
}

export async function apiGetRoles() {
  const res = await api.get("/roles");
  return res.data;
}


export async function apiGetUserRoles(userId: number) {
  const res = await api.get(`/users/${userId}/roles`);
  return res.data;
}


export async function apiCreateRole(payload: { code: string; name: string }) {
  const res = await api.post("/roles", payload);
  return res.data;
}


export async function apiUpdateRole(id: number, payload: { code: string; name: string }) {
  const res = await api.patch(`/roles/${id}`, payload);
  return res.data;
}


export async function apiDeleteRole(id: number) {
  const res = await api.delete(`/roles/${id}`);
  return res.data;
}

export type Project = {
  id: number;
  project_code?: string | null; 
  code?: string | null;
  title?: string | null;
  name?: string | null;
  start_date?: string | null;
  end_date?: string | null;
  description?: string | null;
  status?: string | number | null;
  is_public?: boolean | number | null;
  pm?: { id: number; name: string } | null;
  members?: Array<{ id: number; name: string; email?: string; role?: { id: number; name: string } }> | null;
};

export type Role = { id: number; code: string; name: string };
export type UserLite = { id: number; name: string; email: string; employee_code?: string | null };


export async function apiGetProjects(params?: { keyword?: string; page?: number; per_page?: number }) {

  if (params?.keyword && params.keyword.trim() !== "") {
    const res = await api.get("/filter/projects", { params });
    return res.data;
  }
  const res = await api.get("/projects", { params });
  return res.data;
}


export async function apiCreateProject(payload: any) {
  const res = await api.post("/projects", payload);
  return res.data;
}


export async function apiUpdateProject(id: number, payload: any) {
  const res = await api.put(`/projects/${id}`, payload);
  return res.data;
}


export async function apiDeleteProject(id: number) {
  const res = await api.delete(`/projects/${id}`);
  return res.data;
}

export async function apiGetRolesLite(): Promise<Role[]> {
  const res = await api.get("/roles");
  return res.data?.roles ?? res.data?.data?.roles ?? [];
}


export async function apiGetUsersLite(params?: { keyword?: string; page?: number; per_page?: number }): Promise<UserLite[]> {
  const res = await api.get("/users", { params });
  return res.data?.data?.items ?? res.data?.items ?? [];
}

export async function apiAssignMembers(
  projectId: number,
  payload: { members: Array<{ user_id: number; role_id: number }> }
) {
  const res = await api.post(`/projects/${projectId}/members`, payload);
  return res.data;
}

