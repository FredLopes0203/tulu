<?php

namespace App\Notifications\Backend\Access;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserAccountActive.
 */
class UserSetInitial extends Notification
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

    protected $organization;
    protected $status;
    /**
     * UserApproveChanged constructor.
     *
     * @param $approve
     */
    public function __construct($status, $organization)
    {
        $this->organization = $organization;
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
        $message = "You are the inital admin of the organization.";

        return (new MailMessage())
            ->subject(app_name())
            ->line($message)
            ->line('Organization Name: '.$this->organization->name)
            ->line('Organization ID #: '.$this->organization->groupid)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
