<?php

namespace App\Notifications\Backend\Alert;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserAccountActive.
 */
class AlertCreated extends Notification
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

    protected $alert;
    /**
     * UserApproveChanged constructor.
     *
     * @param $approve
     */
    public function __construct($alert)
    {
        $this->alert = $alert;
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
            ->subject(app_name(). ": ".$this->alert->title)
            ->line($this->alert->title)
            ->line($this->alert->content)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
