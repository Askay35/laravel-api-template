<script setup lang="ts">
  import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { register, loading, isAuthenticated, initFromStorage } = useAuth()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

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
    await register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    })

    await router.push('/account')
  } catch (error: any) {
    if (error?.type === 'validation') {
      formErrors.value = error.errors
    } else {
      generalError.value = 'Не удалось создать аккаунт. Попробуйте ещё раз.'
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
      <div class="space-y-3 text-center">
        <h1 class="text-3xl font-semibold tracking-tight">
          Регистрация
        </h1>
        <p class="text-sm md:text-base text-muted">
          Начните создавать ролики с чтением Корана и публиковать их в любимые соцсети.
        </p>
      </div>

      <form class="space-y-4 mb-3" @submit.prevent="onSubmit">
        <div class="space-y-1.5">
          <div class="flex flex-col gap-1">
            <label class="text-sm font-medium">Имя</label>
            <UInput
              v-model="name"
              placeholder="Введите имя"
              size="lg"
              :ui="{ base: 'rounded-lg w-full font-medium', root: 'w-full' }"
              :disabled="loading"
              required
            />
          </div>
          <p
            v-if="formErrors?.name"
            class="text-xs text-error"
          >
            {{ formErrors.name[0] }}
          </p>
        </div>

        <div class="space-y-1.5">
          <div class="flex flex-col gap-1">
            <label class="text-sm font-medium">E-mail</label>
            <UInput
              v-model="email"
              type="email"
              placeholder="Введите email"
              size="lg"
              :ui="{ base: 'rounded-lg w-full font-medium', root: 'w-full' }"
              :disabled="loading"
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
            <div class="flex flex-col gap-1">
            <label class="text-sm font-medium">Пароль</label>
            <div class="relative">
              <UInput
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Введите пароль от 8 символов"
                minlength="8"
                size="lg"
                :ui="{ base: 'rounded-lg w-full font-medium pr-10', root: 'w-full' }"
                :disabled="loading"
                required
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

        <div class="space-y-1.5">
          <div class="flex flex-col gap-1">
            <label class="text-sm font-medium">Подтверждение пароля</label>
            <div class="relative">
              <UInput
                v-model="passwordConfirmation"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                placeholder="Введите пароль ещё раз"
                size="lg"
                :ui="{ base: 'rounded-lg w-full font-medium pr-10', root: 'w-full' }"
                :disabled="loading"
                required
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-muted cursor-pointer"
                @click="showPasswordConfirmation = !showPasswordConfirmation"
              >
                <Icon
                  :name="showPasswordConfirmation ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                  class="w-5 h-5"
                />
              </button>
            </div>
          </div>
          <p
            v-if="formErrors?.password_confirmation"
            class="text-xs text-error"
          >
            {{ formErrors.password_confirmation[0] }}
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
          Зарегистрироваться
        </UButton>
      </form>

      <div class="text-center text-sm text-muted">
        Уже есть аккаунт?
        <NuxtLink
          to="/login"
          class="font-medium text-primary hover:underline"
        >
          Войти
        </NuxtLink>
      </div>
    </UCard>
  </UContainer>
</template>
