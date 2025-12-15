<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\DTO\Auth\RegisterDTO;
use App\Http\Requests\Auth\RegisterRequest;
use App\Contracts\Services\AuthServiceContract;
use App\Http\Resources\UserWithTokenResource;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, AuthServiceContract $authService): UserWithTokenResource
    {
        $dto = RegisterDTO::from($request->validated());
        $response = $authService->register($dto);
        
        return $response;
    }
}
