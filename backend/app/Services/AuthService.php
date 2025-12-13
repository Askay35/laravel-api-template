<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceContract;
use App\DTO\Auth\RegisterDTO;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class AuthService implements AuthServiceContract
{
    public function register(RegisterDTO $registerDTO, string $locale): UserResource
    {
        $user = User::create($registerDTO->toArray());

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'locale' => $locale,
                'user' => $user->id,
                'hash' => sha1($user->email)
            ]
        );

        (new MailMessage)->greeting('Здравствуйте, '.$user->name)
        ->line('Нажмите на кнопку ниже для подтверждения электронной почты')
        ->action('Подтвердить электронную почту', $verificationUrl)
        ->line('Если вы не создавали учетную запись, никаких дальнейших действий не требуется.');       
 
        event(new Registered($user));

        return UserResource::make($user)->additional(['token' => $user->createToken()->plainTextToken]);
    }
}