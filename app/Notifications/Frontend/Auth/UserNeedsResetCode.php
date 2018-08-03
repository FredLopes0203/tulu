<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserNeedsPasswordReset.
 */
class UserNeedsResetCode extends Notification
{
    use Queueable;
    /**
     * The password reset token.
     *
     * @var string
     */
    public $resetcode;

    /**
     * UserNeedsPasswordReset constructor.
     *
     * @param $token
     */
    public function __construct($resetcode)
    {
        $this->resetcode = $resetcode;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(app_name().': '.trans('strings.emails.auth.password_reset_subject'))
            ->line('Password reset code is '.$this->resetcode)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
