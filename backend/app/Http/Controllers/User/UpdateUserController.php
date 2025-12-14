<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\DTO\User\UpdateUserDTO;
use App\Contracts\Services\UserServiceContract;

class UpdateUserController extends Controller
{
    public function __invoke(UserServiceContract $userService, UpdateUserRequest $request): UserResource
    {
        $dto = UpdateUserDTO::from($request->validated());
        $user = $userService->update($request->user(), $dto);
        
        return UserResource::make($user);
    }
}