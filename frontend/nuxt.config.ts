// https://nuxt.com/docs/api/configuration/nuxt-config

export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/ui'
  ],

  devtools: {
    enabled: true
  },

  css: ['~/assets/css/main.css'],

  routeRules: {
    '/': { prerender: true }
  },

  runtimeConfig: {
    public: {
      apiBase: 'http://localhost:8000/api/v1',
      appName: 'QuranReels',
      appDescription: 'Создавайте профессиональные вертикальные ролики с чтением Корана'
    }
  },

  compatibilityDate: '2025-01-15',

  devServer: {
    host: '0.0.0.0',
    port: 3000
  },

  eslint: {
    config: {
      stylistic: {
        commaDangle: 'never',
        braceStyle: '1tbs'
      }
    }
  }
})
