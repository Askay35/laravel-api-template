<?php

namespace App\Contracts\Services;

use App\DTO\User\UpdateUserDTO;
use App\Http\Resources\UserResource;
use App\Models\User;

interface UserServiceContract
{
    /**
     * Update an existing user.
     *
     * @param User $user
     * @param UpdateUserDTO $dto
     * @return UserResource
     */
    public function update(User $user, UpdateUserDTO $dto): UserResource;

}
