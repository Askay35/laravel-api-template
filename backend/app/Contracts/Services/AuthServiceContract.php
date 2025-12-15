<?php

namespace App\Contracts\Services;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\Http\Resources\UserWithTokenResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface AuthServiceContract
{
    public function register(RegisterDTO $registerDTO): UserWithTokenResource;
    public function login(LoginDTO $loginDTO): JsonResponse;
    public function logout(User $user): void;
    public function deleteAllTokens(User $user): void;
    public function deleteAccessToken(User $user): void;
    public function updateToken(User $user, string $refreshToken): JsonResponse;
}