<script setup lang="ts">
import { useAuth } from '../composables/useAuth'

const { isAuthenticated, user } = useAuth()
</script>

<template>
  <div class="min-h-screen">
    <section class="py-20 md:py-28">
      <UContainer>
        <div class="max-w-7xl mx-auto">
          <div class="grid md:grid-cols-2 gap-12 md:gap-16 items-center">
            <div class="space-y-8">
              <div class="space-y-6">
                <h1 class="text-4xl leading-tight md:text-5xl lg:text-6xl font-bold tracking-tight">
                  Зарабатывайте награду, распространяя <span class="text-primary">Коран</span> через красивые ролики
                </h1>
                <p class="md:text-lg text-slate-600 leading-normal max-w-xl">
                  Создавайте профессиональные вертикальные ролики с чтением Корана за пару минут.
                  Идеально для людей, которые хотят получать саваб за распространение священного текста.
                </p>
              </div>

              <div class="flex flex-wrap items-center gap-4">
                <template v-if="isAuthenticated">
                  <UButton
                    to="/account"
                    size="xl"
                    color="primary"
                    :ui="{ base: 'rounded-full font-semibold px-8 py-6 text-lg' }"
                    icon="i-lucide-user"
                  >
                    Перейти в личный кабинет
                  </UButton>
                  <span class="text-sm text-muted">
                    Вошли как {{ user?.email }}
                  </span>
                </template>

                <template v-else>
                  <UButton
                    to="/login"
                    size="xl"
                    color="neutral"
                    variant="ghost"
                    :ui="{ base: 'rounded-full font-medium px-7 py-4 text-lg border-2 border-slate-200' }"
                  >
                    Войти
                  </UButton>
                  <UButton
                    to="/register"
                    size="xl"
                    color="primary"
                    variant="solid"
                    :ui="{ base: 'rounded-full font-semibold px-7 py-4 text-lg' }"
                  >
                    Начать бесплатно
                  </UButton>
                </template>
              </div>

              <!-- Статистика -->
              <div class="flex flex-wrap gap-8 pt-4">
                <div>
                  <div class="text-2xl md:text-3xl font-bold text-green-600">100%</div>
                  <div class="text-sm text-muted">Бесплатно</div>
                </div>
                <div>
                  <div class="text-2xl md:text-3xl font-bold text-green-600">2 мин</div>
                  <div class="text-sm text-muted">На создание ролика</div>
                </div>
                <div>
                  <div class="text-2xl md:text-3xl font-bold text-green-600">∞</div>
                  <div class="text-sm text-muted">Роликов в месяц</div>
                </div>
              </div>
            </div>

            <!-- Визуальный блок с телефоном -->
            <div class="relative flex justify-center items-center">
              <div class="relative">
                <!-- Фоновая декорация с анимацией -->
                <div class="absolute inset-0 bg-linear-to-br from-green-200 via-emerald-100 to-teal-50 rounded-3xl blur-3xl opacity-70 -z-10 transform scale-110 animate-pulse"></div>
                <div class="absolute inset-0 bg-gradient-to-tr from-green-100/50 via-transparent to-emerald-100/50 rounded-3xl blur-2xl opacity-50 -z-10 transform scale-125"></div>
                
                <!-- Телефон с контентом -->
                <div class="relative transform transition-all duration-500 hover:scale-105">
                  <!-- Внешняя рамка телефона с улучшенным градиентом -->
                  <div class="relative bg-linear-to-br from-slate-200 via-slate-100 to-slate-200 rounded-[3rem] p-[7px] shadow-[0_25px_80px_-20px_rgba(0,0,0,0.4),0_0_0_1px_rgba(255,255,255,0.1),inset_0_1px_0_rgba(255,255,255,0.2)]">
                    <!-- Металлический эффект на рамке -->
                    <div class="absolute inset-0 rounded-[3rem] bg-linear-to-br from-white/30 via-transparent to-transparent pointer-events-none"></div>
                    
                    <!-- Внутренняя рамка -->
                    <div class="bg-linear-to-br from-slate-950 via-slate-900 to-slate-950 rounded-[2.75rem] p-[4px] shadow-inner">
                      <!-- Экран телефона -->
                      <div class="bg-slate-950 rounded-[2.5rem] overflow-hidden aspect-[9/19.5] w-[280px] md:w-[320px] relative">
                        <!-- Размытый фон как в реальном видео -->
                        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-900/95 to-slate-950">
                          <!-- Имитация размытого видео фона -->
                          <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 30% 40%, rgba(22,165,123,0.3) 0%, transparent 50%), radial-gradient(circle at 70% 60%, rgba(15,118,110,0.2) 0%, transparent 50%); filter: blur(40px);"></div>
                        </div>
                        
                        <!-- Улучшенные блики на экране -->
                        <div class="absolute inset-0 bg-linear-to-br from-white/8 via-transparent to-transparent pointer-events-none"></div>
                        <div class="absolute top-0 left-0 w-full h-2/5 bg-gradient-to-b from-white/12 via-white/4 to-transparent pointer-events-none"></div>
                        <div class="absolute top-1/4 left-0 w-1/2 h-1/2 bg-linear-to-br from-green-500/10 via-transparent to-transparent pointer-events-none blur-2xl"></div>
                        
                        <!-- Dynamic Island (iPhone-стиль) с улучшенным дизайном -->
                        <div class="absolute top-2 left-1/2 transform -translate-x-1/2 z-30">
                          <div class="bg-black/95 backdrop-blur-xl rounded-full px-7 py-2 flex items-center gap-2.5 shadow-[0_4px_12px_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.1)] border border-white/5">
                            <!-- Анимированные индикаторы -->
                            <div class="flex items-center gap-1.5">
                              <div class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_4px_rgba(34,197,94,0.6)]"></div>
                              <div class="w-1.5 h-1.5 bg-yellow-400 rounded-full animate-pulse shadow-[0_0_4px_rgba(250,204,21,0.6)]" style="animation-delay: 0.2s;"></div>
                              <div class="w-1.5 h-1.5 bg-red-400 rounded-full animate-pulse shadow-[0_0_4px_rgba(248,113,113,0.6)]" style="animation-delay: 0.4s;"></div>
                            </div>
                            <span class="text-white text-[11px] font-semibold ml-1 tracking-tight">9:41</span>
                          </div>
                        </div>

                        <!-- Улучшенный статус-бар -->
                        <div class="absolute top-3 left-4 right-4 flex justify-between items-center z-20 pointer-events-none">
                          <div class="flex items-center gap-1">
                            <div class="w-5 h-2 bg-white/90 rounded-sm shadow-sm"></div>
                            <div class="w-5 h-2 bg-white/70 rounded-sm shadow-sm"></div>
                            <div class="w-5 h-2 bg-white/50 rounded-sm shadow-sm"></div>
                            <div class="w-5 h-2 bg-white/30 rounded-sm shadow-sm"></div>
                          </div>
                          <div class="flex items-center gap-1.5">
                            <svg class="w-4.5 h-4.5 text-white/90 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                            <svg class="w-6 h-3.5 text-white/90 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 12">
                              <rect x="0" y="3.5" width="18" height="5" rx="1.5" fill="currentColor"/>
                              <rect x="18" y="5" width="2" height="2.5" rx="0.5" fill="currentColor"/>
                            </svg>
                          </div>
                        </div>
                        
                        <!-- Полумесяц в центре с улучшенной анимацией и эффектами -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                          <div class="relative">
                            <!-- Многослойное свечение вокруг полумесяца -->
                            <div class="absolute inset-0 bg-green-400/30 blur-3xl rounded-full transform scale-[1.8] animate-pulse"></div>
                            <div class="absolute inset-0 bg-emerald-500/20 blur-2xl rounded-full transform scale-[1.5]"></div>
                            <div class="absolute inset-0 bg-teal-400/15 blur-xl rounded-full transform scale-[1.2]"></div>
                            
                            <svg width="160" height="160" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-[0_0_30px_rgba(22,165,123,0.5)]">
                              <defs>
                                <linearGradient id="crescent-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                  <stop offset="0%" style="stop-color:#22C55E;stop-opacity:1" />
                                  <stop offset="50%" style="stop-color:#16A57B;stop-opacity:1" />
                                  <stop offset="100%" style="stop-color:#0F766E;stop-opacity:1" />
                                </linearGradient>
                                <radialGradient id="crescent-shine" cx="30%" cy="30%">
                                  <stop offset="0%" style="stop-color:#34D399;stop-opacity:0.8" />
                                  <stop offset="100%" style="stop-color:#16A57B;stop-opacity:1" />
                                </radialGradient>
                                <mask id="crescent-mask">
                                  <rect width="160" height="160" fill="white"/>
                                  <circle cx="96" cy="80" r="40" fill="black"/>
                                </mask>
                                <filter id="glow-strong">
                                  <feGaussianBlur stdDeviation="4" result="coloredBlur"/>
                                  <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                  </feMerge>
                                </filter>
                                <filter id="glow-soft">
                                  <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                  <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                  </feMerge>
                                </filter>
                              </defs>
                              <!-- Симметричный полумесяц с улучшенным градиентом -->
                              <circle cx="80" cy="80" r="50" fill="url(#crescent-gradient)" mask="url(#crescent-mask)" filter="url(#glow-strong)" opacity="0.95"/>
                              <!-- Дополнительный слой для блеска -->
                              <circle cx="80" cy="80" r="50" fill="url(#crescent-shine)" mask="url(#crescent-mask)" opacity="0.6"/>
                              <!-- Кнопка play с улучшенным дизайном -->
                              <g filter="url(#glow-soft)">
                                <polygon
                                  points="66,58 90,80 66,102"
                                  fill="white"
                                  opacity="0.98"
                                  class="drop-shadow-lg"
                                />
                                <!-- Внутренняя тень для объема -->
                                <polygon
                                  points="66,58 90,80 66,102"
                                  fill="url(#crescent-gradient)"
                                  opacity="0.3"
                                  transform="translate(2,2)"
                                />
                              </g>
                            </svg>
                          </div>
                        </div>

                        <!-- Аят на арабском с улучшенной типографикой -->
                        <div class="absolute bottom-36 left-0 right-0 px-6 z-10">
                          <div class="text-center">
                            <div class="text-white text-2xl md:text-3xl font-arabic leading-relaxed" dir="rtl" style="text-shadow: 0 3px 12px rgba(0,0,0,0.7), 0 0 20px rgba(22,165,123,0.3); letter-spacing: 0.05em;">
                              بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
                            </div>
                          </div>
                        </div>

                        <!-- Перевод на русском с улучшенным дизайном -->
                        <div class="absolute bottom-20 left-0 right-0 px-6 z-10">
                          <div class="text-center">
                            <div class="text-white/98 text-sm md:text-base leading-relaxed font-medium" style="text-shadow: 0 2px 8px rgba(0,0,0,0.6), 0 0 15px rgba(22,165,123,0.2);">
                              Во имя Аллаха, Милостивого, Милосердного
                            </div>
                          </div>
                        </div>

                        <!-- Улучшенный индикатор прогресса видео -->
                        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-white/15 z-20 backdrop-blur-sm">
                          <div class="h-full bg-gradient-to-r from-green-400 via-emerald-400 to-green-500 w-1/3 shadow-[0_0_8px_rgba(22,165,123,0.6)]"></div>
                          <!-- Точка текущего времени -->
                          <div class="absolute top-1/2 left-1/3 transform -translate-y-1/2 -translate-x-1/2 w-3 h-3 bg-white rounded-full shadow-[0_0_6px_rgba(255,255,255,0.8)]"></div>
                        </div>

                        <!-- Улучшенные отражения света -->
                        <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-white/8 via-white/3 to-transparent pointer-events-none"></div>
                        <div class="absolute top-1/3 right-0 w-1/3 h-1/3 bg-gradient-to-bl from-green-400/10 via-transparent to-transparent pointer-events-none blur-2xl"></div>
                        
                        <!-- Эффект виньетки по краям -->
                        <div class="absolute inset-0 bg-radial-gradient from-transparent via-transparent to-black/20 pointer-events-none" style="background: radial-gradient(ellipse at center, transparent 0%, transparent 60%, rgba(0,0,0,0.15) 100%);"></div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Улучшенные тени для объема -->
                  <div class="absolute -inset-6 bg-linear-to-br from-slate-300/40 via-transparent to-slate-200/30 rounded-[3.5rem] blur-2xl -z-10"></div>
                  <div class="absolute -inset-8 bg-gradient-to-tr from-green-200/20 to-emerald-100/20 rounded-[4rem] blur-3xl -z-20 opacity-60"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </UContainer>
    </section>

    <!-- Как это работает -->
    <section class="py-20 md:py-28 bg-slate-50">
      <UContainer>
        <div class="max-w-6xl mx-auto">
          <div class="text-center space-y-4 mb-16">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
              Как это работает
            </h2>
            <p class="text-lg text-muted max-w-2xl mx-auto">
              Три простых шага до вашего первого ролика
            </p>
          </div>

          <div class="grid md:grid-cols-3 gap-8 md:gap-12">
            <div class="bg-white rounded-2xl p-8 border-2 border-slate-200 shadow-none transition-shadow">
              <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold mb-3">Загрузите аудио или видео</h3>
              <p class="text-muted">
                Загрузите запись чтения Корана или видео, из которого мы автоматически извлечём звук.
                Поддерживаются все популярные форматы.
              </p>
            </div>

            <div class="bg-white rounded-2xl p-8 border-2 border-slate-200 shadow-none transition-shadow">
              <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold mb-3">Выберите суру и аяты</h3>
              <p class="text-muted">
                Укажите, какая сура читается, и выберите конкретные аяты. Система автоматически синхронизирует
                текст с аудио и разместит его на экране.
              </p>
            </div>

            <div class="bg-white rounded-2xl p-8 border-2 border-slate-200 shadow-none transition-shadow">
              <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold mb-3">Получите готовый ролик</h3>
              <p class="text-muted">
                Скачайте готовый вертикальный ролик с аятами на арабском и переводом на русском.
                Готово к публикации в Instagram Reels, YouTube Shorts, TikTok.
              </p>
            </div>
          </div>
        </div>
      </UContainer>
    </section>

    <!-- Преимущества -->
    <section class="py-20 md:py-28">
      <UContainer>
        <div class="max-w-6xl mx-auto">
          <div class="text-center space-y-4 mb-16">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
              Почему люди выбирают QuranReels
            </h2>
            <p class="text-lg text-muted max-w-2xl mx-auto">
              Инструмент для тех, кто хочет зарабатывать награду за распространение Корана
            </p>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="space-y-3 p-6 rounded-xl bg-linear-to-br from-green-50 to-white border border-green-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold">Получайте саваб</h3>
              </div>
              <p class="text-muted leading-relaxed">
                Каждый ролик с чтением Корана — это возможность заработать награду от Всевышнего.
                Распространяйте священный текст и получайте вознаграждение.
              </p>
            </div>

            <div class="space-y-3 p-6 rounded-xl bg-linear-to-br from-green-50 to-white border border-green-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold">Быстро и просто</h3>
              </div>
              <p class="text-muted leading-relaxed">
                Создайте красивый ролик за 2 минуты без навыков монтажа.
                Всё автоматически: синхронизация текста, размещение аятов, перевод.
              </p>
            </div>

            <div class="space-y-3 p-6 rounded-xl bg-linear-to-br from-green-50 to-white border border-green-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold">Качественный вид</h3>
              </div>
              <p class="text-muted leading-relaxed">
                Красивая типографика, правильное размещение аятов, аккуратный перевод.
                Ваши ролики будут выглядеть как будто редактировались вручную.
              </p>
            </div>

            <div class="space-y-3 p-6 rounded-xl bg-linear-to-br from-green-50 to-white border border-green-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold">Идеально для соцсетей</h3>
              </div>
              <p class="text-muted leading-relaxed">
                Вертикальный формат 9:16, оптимизирован для Instagram Reels, YouTube Shorts, TikTok.
                Готово к публикации без дополнительной обработки.
              </p>
            </div>

            <div class="space-y-3 p-6 rounded-xl bg-linear-to-br from-green-50 to-white border border-green-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold">Точность текста</h3>
              </div>
              <p class="text-muted leading-relaxed">
                Все аяты проверены и соответствуют официальным переводам.
                Вы можете быть уверены в корректности текста.
              </p>
            </div>

            <div class="space-y-3 p-6 rounded-xl bg-linear-to-br from-green-50 to-white border border-green-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold">Полностью бесплатно</h3>
              </div>
              <p class="text-muted leading-relaxed">
                Создавайте неограниченное количество роликов без подписок и скрытых платежей.
                Мы верим, что распространение Корана должно быть доступно всем.
              </p>
            </div>
          </div>
        </div>
      </UContainer>
    </section>

    <!-- CTA Section -->
    <section class="py-20 md:py-28 bg-linear-to-br from-green-50 to-white">
      <UContainer>
        <div class="max-w-4xl mx-auto text-center space-y-8">
          <div class="space-y-4">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
              Начните распространять Коран уже сегодня
            </h2>
            <p class="md:text-lg text-muted max-w-2xl mx-auto">
              Присоединяйтесь к верующим, которые зарабатывают награду за каждую минуту просмотра их роликов с чтением Корана.
              Регистрация займёт меньше минуты.
            </p>
          </div>

          <div class="flex flex-wrap justify-center gap-4">
            <template v-if="!isAuthenticated">
              <UButton
                to="/register"
                size="xl"
                color="primary"
                variant="solid"
                :ui="{ base: 'rounded-full font-semibold px-8 py-4 text-lg' }"
              >
                Создать аккаунт
              </UButton>
              <UButton
                to="/contact"
                size="xl"
                color="neutral"
                variant="ghost"
                :ui="{ base: 'rounded-full font-medium px-8 py-4 text-lg border-2 border-slate-200' }"
              >
                Связаться с нами
              </UButton>
            </template>
            <template v-else>
              <UButton
                to="/account"
                size="xl"
                color="primary"
                variant="solid"
                :ui="{ base: 'rounded-full font-semibold px-8 py-4 text-lg' }"
              >
                Создать первый ролик
              </UButton>
            </template>
          </div>
        </div>
      </UContainer>
    </section>
  </div>
</template>
