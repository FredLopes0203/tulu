<?php

namespace App\Notifications\Backend\Access;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserAccountActive.
 */
class AccountCreated extends Notification
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

    protected $isadmin;
    protected $groupname;
    /**
     * UserApproveChanged constructor.
     *
     * @param $approve
     */
    public function __construct($isadmin, $groupname)
    {
        $this->isadmin = $isadmin;
        $this->groupname = $groupname;
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
        $message = "You were registered as USER of ".$this->groupname.".\n";
        if($this->isadmin == 1)
        {
            $message = "You were registered as ADMIN of ".$this->groupname.".\n";
        }
        $message = "You can login to your account using your email address. Your temporary password is 123456. You can reset your password after you log on";

        if($this->isadmin)
        {
            return (new MailMessage())
                ->subject(app_name())
                ->line($message)
                ->action(trans('labels.frontend.auth.login_button'), route('frontend.auth.login'))
                ->line(trans('strings.emails.auth.thank_you_for_using_app'));
        }
        else
        {
            return (new MailMessage())
                ->subject(app_name())
                ->line($message)
                ->line(trans('strings.emails.auth.thank_you_for_using_app'));
        }
    }
}
