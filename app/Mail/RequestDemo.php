<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestDemo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $useremail;
    public $username;
    public $content;

    public function __construct($useremail, $username, $content)
    {
        $this->useremail = $useremail;
        $this->username = $username;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('frontend.mail.requestdemo')
                    ->subject('A Customer Requested Demo For TULU App.');
    }
}
