<?php

namespace App\Notifications\Backend\Organization;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserAccountActive.
 */
class OrganizationDeleteChanged extends Notification
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
    protected $group;

    /**
     * UserStatusChanged constructor.
     *
     * @param $status
     */
    public function __construct($status, $group)
    {
        $this->status = $status;
        $this->group = $group;
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
        $message = "Your organization has been restored!";

        if($this->status == 0)
        {
            $message = "Your organization has been deleted.";
        }
        else if($this->status == 2)
        {
            $message = "Your organization has been deleted permanently.";
        }

        return (new MailMessage())
            ->subject(app_name())
            ->line($message)
            ->line('Organization Name: '.$this->group->name)
            ->line('Organization ID #: '.$this->group->groupid)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
