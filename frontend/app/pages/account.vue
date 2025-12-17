<script setup lang="ts">
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { user, isAuthenticated, loading, initFromStorage, fetchUser, updateUser, logout, checkEmailVerified } = useAuth()

const saving = ref(false)
const formName = ref('')
const newPassword = ref('')
const newPasswordConfirmation = ref('')
const formErrors = ref<Record<string, string[]> | null>(null)
const generalError = ref<string | null>(null)
const emailVerified = ref<boolean | null>(null)
const checkingVerification = ref(false)

onMounted(async () => {
  initFromStorage()

  if (!isAuthenticated.value) {
    await router.push('/login')
    return
  }

  if (!user.value) {
    await fetchUser()
  }

  if (user.value) {
    formName.value = user.value.name
  }

  // Проверяем статус верификации email
  checkingVerification.value = true
  try {
    emailVerified.value = await checkEmailVerified()
  } catch {
    emailVerified.value = false
  } finally {
    checkingVerification.value = false
  }
})

watch(user, (val) => {
  if (val) {
    formName.value = val.name
  }
})

async function onSave() {
  formErrors.value = null
  generalError.value = null
  saving.value = true

  const payload: {
    name?: string
    password?: string
    password_confirmation?: string
  } = {}

  if (formName.value && formName.value !== user.value?.name) {
    payload.name = formName.value
  }

  if (newPassword.value) {
    payload.password = newPassword.value
    payload.password_confirmation = newPasswordConfirmation.value
  }

  if (!Object.keys(payload).length) {
    saving.value = false
    return
  }

  try {
    await updateUser(payload)
    newPassword.value = ''
    newPasswordConfirmation.value = ''
  } catch (error: any) {
    if (error?.type === 'validation') {
      formErrors.value = error.errors
    } else {
      generalError.value = 'Не удалось сохранить изменения.'
    }
  } finally {
    saving.value = false
  }
}

async function onLogout() {
  await logout()
  await router.push('/')
}

</script>

<template>
  <UContainer class="py-8">
    <div class="max-w-3xl mx-auto space-y-8">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-semibold tracking-tight">
            Личный кабинет
          </h1>
          <p class="text-sm text-muted">
            Минимальный аккуратный профиль с возможностью изменить имя и пароль.
          </p>
        </div>

        <UButton
          color="neutral"
          variant="ghost"
          icon="i-lucide-log-out"
          @click="onLogout"
        >
          Выйти
        </UButton>
      </div>

      <!-- Индикатор загрузки при проверке верификации -->
      <div v-if="checkingVerification || emailVerified === null" class="flex justify-center py-8">
        <div class="text-center space-y-3">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          <p class="text-sm text-muted">Проверка статуса email...</p>
        </div>
      </div>

      <!-- Сообщение о необходимости подтверждения email -->
      <UAlert
        v-else-if="emailVerified === false"
        color="warning"
        variant="subtle"
        icon="i-lucide-mail"
        class="rounded-xl"
      >
        <template #title>
          Подтвердите ваш email
        </template>
        <template #description>
          <div class="space-y-3 mt-2">
            <p>
              Мы отправили ссылку для подтверждения регистрации на ваш email адрес.
              Пожалуйста, перейдите по ссылке в письме и затем перезагрузите эту страницу.
            </p>
            <p class="text-sm text-muted">
              Если письмо не пришло, проверьте папку "Спам" или обратитесь в поддержку.
            </p>
          </div>
        </template>
      </UAlert>

      <!-- Основной контент показывается только если email подтвержден -->
      <div v-else-if="emailVerified === true" class="grid gap-6 md:grid-cols-[2fr,3fr]">
        <UCard
          :ui="{
            root: 'ring-1 ring-border/50 rounded-2xl',
            body: 'space-y-4'
          }"
        >
          <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary font-semibold">
              {{ user?.name?.charAt(0)?.toUpperCase() || '?' }}
            </div>
            <div class="space-y-1">
              <p class="text-sm text-muted">
                Ваш профиль
              </p>
              <p class="text-base font-medium">
                {{ user?.name }}
              </p>
              <p class="text-xs text-muted">
                {{ user?.email }}
              </p>
            </div>
          </div>
        </UCard>

        <UCard
          :ui="{
            root: 'ring-1 ring-border/50 rounded-2xl',
            body: 'space-y-6'
          }"
        >
          <form class="space-y-4" @submit.prevent="onSave">
            <div class="space-y-1.5">
              <label class="text-sm font-medium">Имя</label>
              <UInput
                v-model="formName"
                placeholder="Ваше имя"
                size="lg"
                :ui="{ base: 'rounded-xl font-medium' }"
                :disabled="loading || saving"
              />
              <p
                v-if="formErrors?.name"
                class="text-xs text-red-500"
              >
                {{ formErrors.name[0] }}
              </p>
            </div>

            <UDivider
              label="Смена пароля (по желанию)"
              class="my-3"
            />

            <div class="space-y-1.5">
              <label class="text-sm font-medium">Новый пароль</label>
              <UInput
                v-model="newPassword"
                type="password"
                placeholder="Оставьте пустым, если не хотите менять"
                size="lg"
                :ui="{ base: 'rounded-xl font-medium' }"
                :disabled="loading || saving"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-sm font-medium">Подтверждение пароля</label>
              <UInput
                v-model="newPasswordConfirmation"
                type="password"
                placeholder="Повторите новый пароль"
                size="lg"
                :ui="{ base: 'rounded-xl font-medium' }"
                :disabled="loading || saving"
              />
              <p
                v-if="formErrors?.password"
                class="text-xs text-red-500"
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

            <div class="flex justify-end">
              <UButton
                type="submit"
                size="lg"
                :loading="saving"
                :disabled="loading"
                :ui="{
                  base: 'rounded-xl font-medium',
                }"
              >
                Сохранить изменения
              </UButton>
            </div>
          </form>
        </UCard>
      </div>
    </div>
  </UContainer>
</template>
