<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\DTO\Auth\RegisterDTO;
use App\Http\Requests\RegisterUserRequest;
use App\Contracts\Services\AuthServiceContract;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    public function __invoke(RegisterUserRequest $request, AuthServiceContract $authService)
    {
        $registerDTO = RegisterDTO::from($request->validated());
        $locale = $request->route('locale') ?: config('app.locale');
        $user = $authService->register($registerDTO, $locale);
        
        return UserResource::make($user);
    }
}
