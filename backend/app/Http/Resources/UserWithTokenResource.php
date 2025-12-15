<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class UserWithTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'token' => Cache::remember('token_'.$this->id, config('auth.token_lifetime'), function () {
                return $this->createToken('auth-token')->plainTextToken;
            }),
        ];
    }
}
