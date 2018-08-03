<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserNeedsConfirmation.
 */
class UserNeedsCodeverification extends Notification
{
    use Queueable;

    /**
     * @var
     */
    protected $verification_code;

    /**
     * UserNeedsConfirmation constructor.
     *
     * @param $confirmation_code
     */
    public function __construct($verification_code)
    {
        $this->verification_code = $verification_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(app_name().': '.trans('exceptions.frontend.auth.confirmation.confirm'))
            ->line('Your verification code is '.$this->verification_code)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
