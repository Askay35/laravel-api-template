<script setup lang="ts">
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { login, loading, isAuthenticated, initFromStorage } = useAuth()

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const formErrors = ref<Record<string, string[]> | null>(null)
const generalError = ref<string | null>(null)

onMounted(async () => {
  initFromStorage()

  if (isAuthenticated.value) {
    await router.push('/account')
  }
})

async function onSubmit() {
  formErrors.value = null
  generalError.value = null

  try {
    await login({
      email: email.value,
      password: password.value
    })

    await router.push('/account')
  } catch (error: any) {
    console.log(error)
    if (error?.type === 'validation') {
      formErrors.value = error.errors
    } else {
      generalError.value = 'Не удалось войти. Попробуйте ещё раз.'
    }
  }
}
</script>

<template>
  <UContainer class="min-h-[calc(100vh-160px)] flex items-center justify-center">
    <UCard
      class="w-full max-w-md"
      :ui="{
        body: 'space-y-6',
        root: 'ring-1 rounded-2xl'
      }"
    >
      <div class="text-center">
        <h1 class="text-3xl font-bold tracking-tight">
          Вход в аккаунт
        </h1>
      </div>

      <form class="space-y-4 mb-3" @submit.prevent="onSubmit">
        <div class="space-y-1.5">
          <div class="flex items-center justify-between">
            <label class="text-sm min-w-20 font-medium">E-mail</label>
            <UInput
              v-model="email"
              type="email"
              placeholder="Ваша электронная почта"
              size="lg"
              :ui="{ base: 'rounded-md w-full font-medium', root: 'w-full' }"
              :disabled="loading"
              required
            />
          </div>
          <p
            v-if="formErrors?.email"
            class="text-xs text-error"
          >
            {{ formErrors.email[0] }}
          </p>
        </div>

        <div class="space-y-1.5">
          <div class="flex items-center justify-between">
            <label class="text-sm min-w-20 font-medium">Пароль</label>
            <div class="relative flex-1">
              <UInput
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Ваш пароль"
                required
                size="lg"
                :ui="{ base: 'rounded-md w-full font-medium pr-10', root: 'w-full' }"
                :disabled="loading"
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-muted cursor-pointer"
                @click="showPassword = !showPassword"
              >
                <Icon
                  :name="showPassword ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                  class="w-5 h-5"
                />
              </button>
            </div>
          </div>
          <p
            v-if="formErrors?.password"
            class="text-xs text-error"
          >
            {{ formErrors.password[0] }}
          </p>
        </div>

        <UAlert
          v-if="generalError"
          color="error"
          variant="subtle"
          icon="i-lucide-alert-circle"
        >
          {{ generalError }}
        </UAlert>

        <UButton
          type="submit"
          block
          size="lg"
          color="primary"
          :loading="loading"
          :ui="{
            base: 'rounded-lg font-semibold'
          }"
        >
          Войти
        </UButton>
      </form>

      <div class="text-center text-sm text-muted">
        Нет аккаунта?
        <NuxtLink
          to="/register"
          class="font-medium text-primary hover:underline"
        >
          Зарегистрироваться
        </NuxtLink>
      </div>
    </UCard>
  </UContainer>
</template>
