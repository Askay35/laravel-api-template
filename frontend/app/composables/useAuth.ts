import { computed } from 'vue'

type User = {
  name: string
  email: string
}

type LoginPayload = {
  email: string
  password: string
}

type RegisterPayload = {
  name: string
  email: string
  password: string
  password_confirmation: string
}

type UpdateUserPayload = {
  name?: string
  password?: string
  password_confirmation?: string
}

type ApiUserWithToken = {
    name: string
    email: string
    token: string
}

const ACCESS_TOKEN_KEY = 'access_token'

export function useAuth() {
  const user = useState<User | null>('auth_user', () => null)
  const accessToken = useState<string | null>('auth_access_token', () => null)
  const loading = useState<boolean>('auth_loading', () => false)
  const initialized = useState<boolean>('auth_initialized', () => false)

  const isAuthenticated = computed(() => !!user.value && !!accessToken.value)

  function setAccessToken(token: string | null) {
    accessToken.value = token
    if (token) {
      localStorage.setItem(ACCESS_TOKEN_KEY, token)
    } else {
      localStorage.removeItem(ACCESS_TOKEN_KEY)
    }
  }

  function setUser(newUser: User | null) {
    user.value = newUser
  }

  async function apiFetch<T>(path: string, options: RequestInit = {}): Promise<T> {
    const config = useRuntimeConfig()
    const base = config.public.apiBase || 'http://localhost:8000/api/v1'

    const normalizedPath = path.startsWith('/') ? path : `/${path}`
    const url = `${base}${normalizedPath}`

    const headers: Record<string, string> = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Accept-Language': 'ru',
    }

    // Добавляем существующие заголовки из options
    if (options.headers) {
      if (options.headers instanceof Headers) {
        options.headers.forEach((value, key) => {
          headers[key] = value
        })
      } else if (Array.isArray(options.headers)) {
        options.headers.forEach(([key, value]) => {
          headers[key] = value
        })
      } else {
        Object.assign(headers, options.headers)
      }
    }

    if (accessToken.value) {
      headers.Authorization = `Bearer ${accessToken.value}`
    }

    const res = await fetch(url, {
      ...options,
      headers: headers as HeadersInit,
      // Нужен для работы refreshToken в HttpOnly cookie
      credentials: 'include'
    })

    const json = await res.json().catch(() => ({}))

    if (!res.ok) {
      // Laravel валидация
      if (json?.errors) {
        throw { type: 'validation', errors: json.errors } as any
      }

      throw { type: 'http', status: res.status, data: json } as any
    }

    return json as T
  }

  async function login(payload: LoginPayload) {
    loading.value = true
    try {
      const data = await apiFetch<ApiUserWithToken>('/auth/login', {
        method: 'POST',
        body: JSON.stringify(payload)
      })


      setAccessToken(data.token)
      setUser({
        name: data.name,
        email: data.email
      })
    } finally {
      loading.value = false
    }
  }

  async function register(payload: RegisterPayload) {
    loading.value = true
    try {
      const data = await apiFetch<ApiUserWithToken>('/auth/register', {
        method: 'POST',
        body: JSON.stringify(payload)
      })

      setAccessToken(data.token)
      setUser({
        name: data.name,
        email: data.email
      })
    } finally {
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!accessToken.value) {
      return
    }

    try {
      const data = await apiFetch<{ data: User }>('/user', {
        method: 'GET'
      })
      setUser(data.data)
    } catch {
      setAccessToken(null)
      setUser(null)
    }
  }

  async function updateUser(payload: UpdateUserPayload) {
    const data = await apiFetch<{ data: User }>('/user', {
      method: 'PUT',
      body: JSON.stringify(payload)
    })

    setUser(data.data)
  }

  async function checkEmailVerified(): Promise<boolean> {
    if (!accessToken.value) {
      return false
    }

    try {
      const data = await apiFetch<{ data: boolean }>('/auth/email-verified', {
        method: 'GET'
      })
      return data.data
    } catch {
      return false
    }
  }

  async function logout() {
    try {
      await apiFetch('/auth/logout', {
        method: 'POST'
      })
    } catch {
      // игнорируем ошибку логаута
    } finally {
      setAccessToken(null)
      setUser(null)
    }
  }

  function initFromStorage() {
    if (initialized.value) {
      return
    }

    const token = localStorage.getItem(ACCESS_TOKEN_KEY)
    if (token) {
      accessToken.value = token
    }

    initialized.value = true
  }

  return {
    user,
    accessToken,
    loading,
    isAuthenticated,
    initFromStorage,
    login,
    register,
    fetchUser,
    updateUser,
    logout,
    checkEmailVerified
  }
}

