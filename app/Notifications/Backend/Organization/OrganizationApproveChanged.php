<?php

namespace App\Notifications\Backend\Organization;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserAccountActive.
 */
class OrganizationApproveChanged extends Notification
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

    protected $approve;
    protected $group;
    /**
     * UserApproveChanged constructor.
     *
     * @param $approve
     */
    public function __construct($approve, $group)
    {
        $this->approve = $approve;
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
        $message = "Organization Approved!";

        if($this->approve == 0)
        {
            $message = "Organization Rejected";
        }

        return (new MailMessage())
            ->subject(app_name())
            ->line($message)
            ->line('Organization Name: '.$this->group->name)
            ->line('Organization ID #: '.$this->group->groupid)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
