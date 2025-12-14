<?php

namespace App\Contracts\Services;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\Http\Resources\UserResource;
use App\Models\User;

interface AuthServiceContract
{
    public function register(RegisterDTO $registerDTO): UserResource;
    public function login(LoginDTO $loginDTO): UserResource;
    public function logout(User $user): void;
}