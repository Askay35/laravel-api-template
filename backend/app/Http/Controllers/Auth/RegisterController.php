<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\DTO\Auth\RegisterDTO;
use App\Http\Requests\Auth\RegisterRequest;
use App\Contracts\Services\AuthServiceContract;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, AuthServiceContract $authService): UserResource
    {
        $dto = RegisterDTO::from($request->validated());
        $user = $authService->register($dto);
        
        return $user;
    }
}
