<script setup lang="ts">
import { useAuth } from '../composables/useAuth'
import { useApi } from '../composables/useApi'

const { isAuthenticated, initFromStorage } = useAuth()
const { apiFetch } = useApi()
const router = useRouter()

const name = ref('')
const email = ref('')
const message = ref('')
const loading = ref(false)
const success = ref(false)
const successMessage = ref<string | null>(null)
const error = ref<string | null>(null)
const formErrors = ref<Record<string, string[]> | null>(null)

onMounted(() => {
  initFromStorage()
  
})

type FeedbackResponse = {
  message?: string
  errors?: Record<string, string[]>
}

async function onSubmit() {
  if (name.value === '' || email.value === '' || message.value === '') {
    error.value = 'Пожалуйста, заполните все поля.'
    return
  }
  loading.value = true
  error.value = null
  formErrors.value = null
  success.value = false
  successMessage.value = null

  try {
    const { res, json } = await apiFetch<FeedbackResponse>('/feedback', {
      method: 'POST',
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        message: message.value,
      }),
    })

    if (!res.ok) {
      success.value = false
      successMessage.value = null
      
      // Обработка rate limit (429)
      if (res.status === 429) {
        error.value = 'Превышен лимит запросов. Повторите попытку через минуту.'
        loading.value = false
        // Не очищаем форму при rate limit
        return
      }

      // Обработка валидации (422)
      if (res.status === 422 && json?.errors) {
        formErrors.value = json.errors
        error.value = 'Пожалуйста, исправьте ошибки в форме.'
        loading.value = false
        return
      }

      // Обработка ошибок сервера (500 и другие)
      error.value = json?.message || 'Не удалось отправить сообщение. Попробуйте ещё раз.'
      loading.value = false
      return
    }

    // Успешная отправка (200)
    success.value = true
    error.value = null
    formErrors.value = null
    successMessage.value = json.message || 'Сообщение успешно отправлено'
    name.value = ''
    email.value = ''
    message.value = ''
  } catch (err: any) {
    error.value = 'Произошла ошибка при отправке сообщения. Попробуйте ещё раз.'
    success.value = false
    successMessage.value = null
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-[calc(100vh-200px)] py-16 md:py-24">
    <UContainer>
      <div class="max-w-4xl mx-auto">
        <div class="grid md:grid-cols-2 gap-12 md:gap-16">
          <!-- Информация -->
          <div class="space-y-8">
            <div class="space-y-4">
              <h1 class="text-3xl md:text-4xl font-semibold tracking-tight">
                Свяжитесь с нами
              </h1>
              <p class="text-lg text-muted leading-relaxed">
                Есть вопросы или предложения? Мы всегда рады помочь и услышать ваше мнение.
              </p>
            </div>

            <div class="space-y-6">
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold mb-1">Email</h3>
                  <p class="text-muted">support@quranreels.ru</p>
                </div>
              </div>

              <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold mb-1">Telegram</h3>
                  <p class="text-muted">@quranreels_bot</p>
                </div>
              </div>

              <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold mb-1">Время ответа</h3>
                  <p class="text-muted">Обычно отвечаем в течение нескольких часов</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Форма -->
          <div>
            <UCard class="w-full" :ui="{
              body: 'space-y-6',
              root: 'ring-1 rounded-2xl'
            }">
              <form @submit.prevent="onSubmit" class="space-y-5">
                  <div class="flex items-center justify-between">
                    <label class="text-sm w-16 font-medium">Имя</label>
                    <div class="flex-1">
                      <UInput v-model="name" placeholder="Ваше имя" size="lg"
                        :ui="{ base: 'rounded-md w-full font-medium', root: 'w-full' }" :disabled="loading" />
                      <p v-if="formErrors?.name" class="text-xs text-error mt-1">
                        {{ formErrors.name[0] }}
                      </p>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-sm w-16 font-medium">Email</label>
                    <div class="flex-1">
                      <UInput v-model="email" type="email" placeholder="your@email.com" size="lg"
                        :ui="{ base: 'rounded-md w-full font-medium', root: 'w-full' }" :disabled="loading" />
                      <p v-if="formErrors?.email" class="text-xs text-error mt-1">
                        {{ formErrors.email[0] }}
                      </p>
                    </div>
                  </div>

                <div class="flex flex-col gap-3">
                  <label class="text-sm font-medium">Сообщение</label>
                  <textarea v-model="message" rows="6" placeholder="Введите сообщение"
                    class="w-full px-4 py-3 text-sm border border-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none font-medium placeholder:text-muted"
                    :disabled="loading" />
                  <p v-if="formErrors?.message" class="text-xs text-error">
                    {{ formErrors.message[0] }}
                  </p>
                </div>

                <UAlert v-if="successMessage" :description="successMessage" color="success" variant="subtle" icon="i-lucide-check-circle">
                </UAlert>

                <UAlert v-if="error" :description="error" color="error" variant="subtle" icon="i-lucide-alert-circle">
                </UAlert>

                <UButton type="submit" block size="lg" color="primary" :loading="loading" :ui="{
                  base: 'rounded-xl font-semibold'
                }">
                  Отправить сообщение
                </UButton>
              </form>
            </UCard>
          </div>
        </div>
      </div>
    </UContainer>
  </div>
</template>
