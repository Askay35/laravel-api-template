<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Contracts\Services\AuthServiceContract;
use App\Http\Resources\UserResource;
use App\DTO\Auth\LoginDTO;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, AuthServiceContract $authService): UserResource
    {
        $dto = LoginDTO::from($request->validated());
        $userResource = $authService->login($dto);
        
        return $userResource;
    }
}
