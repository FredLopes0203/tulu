<?php

namespace App\Notifications\Backend\Access;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserAccountActive.
 */
class UserDeleteChanged extends Notification
{
    use Queueable;

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

    protected $status;

    /**
     * UserStatusChanged constructor.
     *
     * @param $status
     */
    public function __construct($status)
    {
        $this->status = $status;
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
        $message = "Your account has been restored!";

        if($this->status == 0)
        {
            $message = "Your account has been deleted.\nPlease contact to the administrator.";
        }
        else if($this->status == 2)
        {
            $message = "Your account has been deleted permanently.";
        }

        return (new MailMessage())
            ->subject(app_name())
            ->line($message)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
