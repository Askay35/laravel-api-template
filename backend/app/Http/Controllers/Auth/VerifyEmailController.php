<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(User $user)
    {
        $user->markEmailAsVerified();

        return MessageResource::make(__('messages.email_verified'));
    }
}
