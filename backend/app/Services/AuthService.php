<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceContract;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\LoginDTO;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithTokenResource;
use App\Models\RefreshToken;
use App\Models\User;
use App\Services\CookieService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceContract
{
    public function __construct(
        private CookieService $cookieService
    ) {
    }

    public function register(RegisterDTO $registerDTO): UserWithTokenResource
    {
        $user = User::create($registerDTO->toArray());
        $user->refreshToken()->create();

        event(new Registered($user));

        return UserWithTokenResource::make($user);
    }
    
    public function login(LoginDTO $loginDTO): JsonResponse
    {
        $user = User::where('email', $loginDTO->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [__('messages.email_not_found')],
            ]);
        }

        if (!Hash::check($loginDTO->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => [__('messages.password_incorrect')],
            ]);
        }

        $user->refreshToken->regenerate();
        
        if (Cache::has('token_'.$user->id)) {
            $token = Cache::get('token_'.$user->id);
        } else {
            $token = Cache::remember('token_'.$user->id, config('auth.token_lifetime'), function () use ($user) {
                return $user->createToken('auth-token')->plainTextToken;
            });
        }

        $refreshToken = $user->refreshToken->token;

        return $this->createTokenResponse($user, $refreshToken, $token);
    }

    public function refreshToken(User $user, string $refreshToken): JsonResponse
    {
        if ($user->refreshToken->isExpired() || !$user->refreshToken->token) {            
            $this->deleteAccessToken($user);

            if (!$refreshToken) {
                return response()->json(MessageResource::make(__('messages.refresh_token_not_found')), 401);
            }
            if (!$user->refreshToken->token === $refreshToken) {
                return response()->json(MessageResource::make(__('messages.refresh_token_mismatch')), 401);
            }
            return response()->json(MessageResource::make(__('messages.refresh_token_expired')), 401);
        }
        
        $token = Cache::remember('token_'.$user->id, config('auth.token_lifetime'), function () use ($user) {
            return $user->createToken('auth-token')->plainTextToken;
        });
        
        return $this->createTokenResponse($user, $refreshToken, $token);
    }

    private function createTokenResponse(User $user, string $refreshToken, string $token): JsonResponse
    {
        $cookie = $this->cookieService->createRefreshTokenCookie($refreshToken);

        $response = response()->json(UserWithTokenResource::make($user));

        $response->headers->setCookie($cookie);

        return $response;
    }

    public function deleteAllTokens(User $user): void
    {
        $user->tokens()->delete();
        if ($user->refreshToken) {
            $user->refreshToken->token = null;
            $user->refreshToken->expires_at = null;
            $user->refreshToken->save();
        }
        Cache::forget('token_'.$user->id);
    }

    public function deleteAccessToken(User $user): void
    {
        $user->tokens()->delete();
        Cache::forget('token_'.$user->id);
    }

    public function logout(User $user): void
    {
        $this->deleteAllTokens($user);
    }
}