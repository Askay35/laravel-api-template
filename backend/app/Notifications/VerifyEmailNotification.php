<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Get the notification's delivery channels.
     *
     * @param  User  $notifiable
     * @return array<int, string>
     */
    public function via(User $notifiable)
    {
        return ['mail'];
    }

    public function viaQueues(): array
    {
        return [
            'mail' => 'emails',
        ];
    
    }
    
    /**
     * Get the mail representation of the notification.
     *
     * @param  User  $notifiable
     * @return MailMessage|null
     */
    public function toMail(User $notifiable): MailMessage
    {
        $hash = sha1($notifiable->email);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'user' => $notifiable->id,
                'hash' => $hash
            ]
        );

        return (new MailMessage)
        ->greeting('Здравствуйте, ' . $notifiable->name)
        ->line('Нажмите на кнопку ниже для подтверждения электронной почты')
        ->action('Подтвердить электронную почту', $verificationUrl)
        ->line('Если вы не создавали учетную запись, никаких дальнейших действий не требуется.');
    }
}
