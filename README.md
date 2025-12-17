### Laravel API Template — авторизация и архитектура

Laravel Api Template — это пример аккуратно спроектированного REST API на Laravel с продуманной архитектурой (сервисы, репозитории, DTO) и полноценной авторизацией по access / refresh токенам.

---

## Архитектура проекта

- **DTO (Data Transfer Objects)**  
  На вход/выход сервисов не ходят «сырые» массивы, а строгие объекты (на базе `spatie/laravel-data`):
  - `App\DTO\Auth\RegisterDTO`
  - `App\DTO\Auth\LoginDTO`
  - `App\DTO\User\UpdateUserDTO`

- **Сервисы** (`app/Services`)
  - **`AuthService`** — вся бизнес‑логика авторизации:
    - регистрация
    - вход
    - обновление токена
    - логаут
    - удаление/очистка токенов
  - **`UserService`** — работа с профилем пользователя.
  - **`CookieService`** — инкапсулирует создание/очистку HTTP‑куки для refresh‑токена.

  Контроллеры максимально «тонкие» и просто делегируют в сервисы.

- **Репозитории** (`app/Repositories`, `app/Contracts/Repositories`)
  - `UserRepository` + `UserRepositoryContract` — слой доступа к БД для пользователя (поиск по email и др.).
  - Контроллеры/сервисы зависят от **контрактов**, а не от конкретных моделей — удобнее тестировать и расширять.

- **Контракты сервисов** (`app/Contracts/Services`)
  - `AuthServiceContract`, `UserServiceContract` — договоры, по которым регистрируются сервисы в `ServicesServiceProvider`.
  - Это облегчает подмену реализаций и написание unit‑тестов.

- **Ресурсы (Resources)** (`app/Http/Resources`)
  - `UserResource`, `UserWithTokenResource`, `MessageResource`  
    Отвечают за единый формат JSON‑ответов, чтобы фронту было просто и предсказуемо.

- **Refresh токены** (`App\Models\RefreshToken`)
  - Отдельная таблица `refresh_tokens`, связанная `hasOne` с `User`.
  - Хранит refresh‑токен и срок действия.
  - Access‑токен кэшируется в Redis для производительности.

---

## Авторизация: как это работает

### 1. Регистрация

- **Endpoint:** `POST /api/v1/auth/register`  
- **Контроллер:** `App\Http\Controllers\Auth\RegisterController`  
- **Сервис:** `AuthService::register(RegisterDTO $dto)`

При регистрации:

1. Создаётся пользователь (`User::create(...)`).
2. Создаётся связанный `refresh_token`.
3. Диспатчится событие `Registered`, которое запускает отправку письма с подтверждением email (`MustVerifyEmail` + кастомное уведомление).
4. Возвращается объект `UserWithTokenResource` с access‑токеном и данными пользователя.

**Пример запроса:**

```http
POST /api/v1/auth/register
Content-Type: application/json

{
  "name": "Ahmad",
  "email": "ahmad@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

**Успешный ответ (200):**

```json
{
  "name": "Ahmad",
  "email": "ahmad@example.com",
  "token": "ACCESS_TOKEN_HERE"
}
```

### 2. Логин

- **Endpoint:** `POST /api/v1/auth/login`  
- **Контроллер:** `Auth\LoginController`  
- **Сервис:** `AuthService::login(LoginDTO $dto)`

Логика:

1. Ищем пользователя через `UserRepository`.
2. Проверяем пароль.
3. Генерируем/берём из Redis access‑токен (Laravel Sanctum).
4. Обновляем/используем существующий `refresh_token`.
5. Возвращаем `UserWithTokenResource` и выставляем refresh‑токен в **HTTP‑cookie** (через `CookieService`).

**Пример запроса:**

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "ahmad@example.com",
  "password": "secret123"
}
```

**Ответ (200):**

```json
{
  "name": "Ahmad",
  "email": "ahmad@example.com",
  "token": "ACCESS_TOKEN_HERE"
}
```

В заголовках ответа придёт `Set-Cookie` с refresh‑токеном.

### 3. Получение текущего пользователя

- **Endpoint:** `GET /api/v1/user`  
- **Требуется:** заголовок `Authorization: Bearer ACCESS_TOKEN_HERE`  

**Пример запроса:**

```http
GET /api/v1/user
Authorization: Bearer ACCESS_TOKEN_HERE
Accept: application/json
```

**Ответ:**

```json
{
    "name": "Ahmad",
    "email": "ahmad@example.com",
}
```

### 4. Обновление пользователя

- **Endpoint:** `PUT /api/v1/user`  
- **Сервис:** `UserService::update(UpdateUserDTO $dto)` (через `AuthService` / репозиторий)

**Пример запроса:**

```http
PUT /api/v1/user
Authorization: Bearer ACCESS_TOKEN_HERE
Content-Type: application/json

{
  "name": "Ahmad Updated",
  "email": "ahmad.new@example.com"
}
```

**Ответ:**

```json
{
    "name": "Ahmad Updated",
    "email": "ahmad.new@example.com"
}
```

### 5. Обновление access‑токена (refresh)

- **Endpoint:** `POST /api/v1/auth/refresh`  
- **Требуется:** refresh‑токен в cookie (создаётся при логине/регистрации).  
- **Контроллер:** `Auth\RefreshTokenController`  
- **Сервис:** `AuthService::refreshToken(User $user, string $refreshToken)`

Логика:

1. По текущему пользователю и переданному refresh‑токену проверяем:
   - не истёк ли refresh‑токен,
   - совпадает ли он с сохранённым в БД.
2. Если всё ок — генерируем новый access‑токен (через Sanctum + Redis cache).
3. Возвращаем тот же формат, что при логине (`UserWithTokenResource`).

**Пример запроса:**

```http
POST /api/v1/auth/refresh
Cookie: refresh_token=REFRESH_TOKEN_VALUE
Authorization: Bearer OLD_ACCESS_TOKEN
```

**Ответ (200):**

```json
{
  "token": "NEW_ACCESS_TOKEN_HERE"
}
```

### 6. Логаут

- **Endpoint:** `POST /api/v1/auth/logout`  
- **Контроллер:** `Auth\LogoutController`  
- **Сервис:** `AuthService::logout(User $user)`

Логика:

1. Удаляем все access‑токены пользователя (`Sanctum`).
2. Очищаем refresh‑токен (в БД и cookie).
3. Чистим кэш access‑токена в Redis.

**Пример запроса:**

```http
POST /api/v1/auth/logout
Authorization: Bearer ACCESS_TOKEN_HERE
Cookie: refresh_token=REFRESH_TOKEN_VALUE
```

**Ответ (200):**

```json
{
  "message": "Вы успешно вышли из аккаунта"
}
```

### 7. Подтверждение email

Основные части:

- Модель `User` реализует `MustVerifyEmail`.
- Событие `Registered` → стандартный listener Laravel `SendEmailVerificationNotification`.
- В `User::sendEmailVerificationNotification()` используется кастомное уведомление `VerifyEmailNotification` (реализует `ShouldQueue`):
  - ссылка: `route('verification.verify')` с подписанным URL  
  - очередь: `notifications` (через `viaQueues()`).

**Пример маршрута подтверждения:**

```http
GET /email/verify/{user}?expires=...&signature=...&hash=...
```

`VerifyEmailController`:

- проверяет хэш email;
- помечает `email_verified_at`;
- возвращает успешное сообщение.

---