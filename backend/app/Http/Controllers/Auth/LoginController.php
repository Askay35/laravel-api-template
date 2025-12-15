<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Contracts\Services\AuthServiceContract;
use App\DTO\Auth\LoginDTO;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, AuthServiceContract $authService): JsonResponse
    {
        $dto = LoginDTO::from($request->validated());
        $response = $authService->login($dto);
        
        return $response;
    }
}
