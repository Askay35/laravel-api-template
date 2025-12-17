<script setup lang="ts">

import { useAuth } from './composables/useAuth'

const config = useRuntimeConfig()
const appTitle = config.public.appName || 'QuranReels'
const appDescription = config.public.appDescription || 'Создавайте профессиональные вертикальные ролики с чтением Корана'

useHead({
  title: appTitle,
  meta: [
    { name: 'viewport', content: 'width=device-width, initial-scale=1' }
  ],
  link: [
    { rel: 'icon', href: '/favicon.ico' }
  ],
  htmlAttrs: {
    lang: 'ru'
  }
})

useSeoMeta({
  title: appTitle,
  description: appDescription,
  ogTitle: appTitle,
  ogDescription: appDescription,
  twitterCard: 'summary_large_image'
})

const router = useRouter()
const route = useRoute()
const { isAuthenticated, user, initFromStorage, logout } = useAuth()

onMounted(() => {
  initFromStorage()
})

const isAuthPage = computed(() => ['login', 'register'].includes(route.name as string))

async function onLogout() {
  await logout()
  await router.push('/')
}
</script>

<template>
  <UApp>
    <UHeader
      :ui="{
        root: 'backdrop-blur border-b',
        container: 'flex items-center justify-between gap-6 py-4'
      }"
    >
      <template #left>
        <NuxtLink to="/" class="flex items-center gap-3">
          <AppLogo class="w-auto h-10 shrink-0 text-primary" />
          <span class="text-2xl font-bold tracking-tight">
            Quran<span class="text-primary">Reels</span>
          </span>
        </NuxtLink>
      </template>

      <template #right>
        <div class="flex items-center gap-3">
          <template v-if="isAuthenticated">
            <UButton
              to="/account"
              variant="soft"
              color="neutral"
              size="md"
              :ui="{ base: 'rounded-full font-medium px-4' }"
              icon="i-lucide-user"
            >
              {{ user?.name || 'Кабинет' }}
            </UButton>

            <UButton
              color="neutral"
              variant="ghost"
              size="md"
              :ui="{ base: 'rounded-full px-4' }"
              icon="i-lucide-log-out"
              @click="onLogout"
            >
              Выйти
            </UButton>
          </template>

          <template v-else>
            <UButton
              v-if="!isAuthPage"
              to="/login"
              color="neutral"
              variant="ghost"
              size="md"
              :ui="{ base: 'rounded-full bg-white px-4 border-2 border-slate-200' }"
            >
              Войти
            </UButton>

            <UButton
              v-if="!isAuthPage"
              to="/register"
              size="md"
              color="primary"
              variant="solid"
              :ui="{ base: 'rounded-full font-semibold px-5' }"
            >
              Регистрация
            </UButton>
          </template>
        </div>
      </template>
    </UHeader>

    <UMain>
      <NuxtPage />
    </UMain>

    <UFooter
      :ui="{
        root: 'border-t border-default',
        container: 'flex items-center justify-between py-4 text-xs text-muted'
      }"
    >
      <template #left>
        <span>© {{ new Date().getFullYear() }} QuranReels</span>
      </template>
      <template #right>
        <div class="flex items-center gap-4">
          <NuxtLink
            to="/contact"
            class="text-xs text-muted hover:text-green-600 transition-colors"
          >
            Контакты
          </NuxtLink>
          <span class="text-xs text-muted">Сделано с помощью Аллаха</span>
        </div>
      </template>
    </UFooter>
  </UApp>
</template>
