<?php

namespace App\Services;

use App\Contracts\Services\UserServiceContract;
use App\DTO\User\UpdateUserDTO;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceContract
{
    /**
     * Update an existing user.
     *
     * @param User $user
     * @param UpdateUserDTO $dto
     * @return UserResource
     */
    public function update(User $user, UpdateUserDTO $dto): UserResource
    {
        $data = $dto->toArray();
        
        $data = array_filter($data, fn($value) => $value !== null);
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return UserResource::make($user);
    }
}
