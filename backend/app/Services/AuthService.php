<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceContract;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\LoginDTO;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceContract
{
    public function register(RegisterDTO $registerDTO): UserResource
    {
        $user = User::create($registerDTO->toArray());
        $user->refreshToken()->create();

        event(new Registered($user));

        return UserResource::make($user)->additional(['token' => $user->createToken('auth-token')->plainTextToken]);
    }

    public function login(LoginDTO $loginDTO): UserResource
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

        if ($user->refreshToken->isExpired()) {
            $user->refreshToken->regenerate();
        }

        if (Cache::has('token_'.$user->id)) {
            return UserResource::make($user)->additional(['token' => Cache::get('token_'.$user->id)]);
        }
        
        $token = Cache::remember('token_'.$user->id, config('auth.token_lifetime'), function () use ($user) {
            return $user->createToken('auth-token')->plainTextToken;
        });

        return UserResource::make($user)->additional(['token' => $token]);
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}