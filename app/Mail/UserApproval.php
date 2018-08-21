<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $permission;
    public $group;

    public function __construct($user, $group)
    {
        $this->user = $user;
        $this->group = $group;

        if($user->isadmin == 0)
        {
            $this->permission = "User";
        }
        else
        {
            $this->permission = "Admin";
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('frontend.mail.userapproval')
                    ->subject('A new user registered and waiting for your approval.');
    }
}
