<?php

namespace App\Contracts\Services;

use App\DTO\Auth\RegisterDTO;
use App\Http\Resources\UserResource;

interface AuthServiceContract
{
    public function register(RegisterDTO $registerDTO, string $locale): UserResource;
}