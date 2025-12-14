<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(User $user, string $hash)
    {
        if (!hash_equals($hash, sha1($user->email))) {
            abort(403, __('messages.invalid_verification_link'));
        }

        // Проверяем, что email еще не верифицирован
        if ($user->hasVerifiedEmail()) {
            return abort(403, __('messages.email_already_verified'));
        }

        $user->markEmailAsVerified();

        return response(__('messages.email_verified'), 200);
    }
}
