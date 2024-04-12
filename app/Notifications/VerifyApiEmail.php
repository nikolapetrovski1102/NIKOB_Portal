<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyApiEmail extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct( $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->subject(Lang::get('Verify Email Address'))
                ->line(Lang::get('Please get the token from bellow and enter it into application'))
                ->action($this->otp, '')
                ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

}