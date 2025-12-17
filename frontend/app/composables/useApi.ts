import { useAuth } from './useAuth'

function getApiBase() {
  const config = useRuntimeConfig()
  if (process.client) {
    return config.public.apiBase || 'http://localhost:8000/api/v1'
  } else {
    return config.public.apiBase || 'http://backend:8000/api/v1'
  }
}

export function useApi() {
  const { accessToken } = useAuth()

  async function apiFetch<T>(path: string, options: RequestInit = {}): Promise<{ res: Response, json: T }> {
    const base = getApiBase()

    const headers: HeadersInit = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Accept-Language': 'ru',
      ...(options.headers || {})
    }

    if (accessToken.value) {
      headers.Authorization = `Bearer ${accessToken.value}`
    }

    const normalizedPath = path.startsWith('/') ? path : `/${path}`
    const url = `${base}${normalizedPath}`

    const res = await fetch(url, {
      ...options,
      headers,
      credentials: 'include'
    })

    const json = await res.json().catch(() => ({})) as T

    return { res, json }
  }

  return {
    apiFetch
  }
}
