<?php

namespace App\Notifications\Backend\Access;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserAccountActive.
 */
class UserApproveChanged extends Notification
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
    protected $admin;
    /**
     * UserApproveChanged constructor.
     *
     * @param $approve
     */
    public function __construct($admin, $approve)
    {
        $this->approve = $approve;
        $this->admin = $admin;
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
        $message = "Your account has been rejected.\nPlease contact to the administrator.";
        if($this->admin == 1)
        {
            $message = "Your admin permission has been rejected.\nPlease contact to the administrator.";
        }

        if($this->approve == 1)
        {
            if($this->admin == 1)
            {
                $message = "Your admin permission has been approved!";
            }
            else
            {
                $message = "Your account has been approved!";
            }
        }
        return (new MailMessage())
            ->subject(app_name())
            ->line($message)
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
