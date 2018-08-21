<?php

namespace App\Jobs;

use App\Models\Access\User\User;
use App\Notifications\Backend\Alert\AlertCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use LaravelFCM\Response\Exceptions\InvalidRequestException;
use Nexmo\Client\Exception\Exception;
use Nexmo\Laravel\Facade\Nexmo;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class SendAlertCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $alert;

    public function __construct($alert)
    {
        $this->alert = $alert;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->attempts() > 1)
        {
            return;
        }
        $users = User::where('organization', $this->alert->organization)
                    ->where('isadmin', 0)
                    ->where('approve', 1)
                    ->where('status', 1)
                    ->get();

        if($users->count() > 0)
        {
            foreach ($users as $user)
            {
                if($this->alert->push == 1)
                {
                    if($user->fcmtoken != "")
                    {
                        $optionBuilder = new OptionsBuilder();
                        $optionBuilder->setTimeToLive(60*20);
                        $option = $optionBuilder->build();
                        $notificationBuilder = new PayloadNotificationBuilder('');

                        $notificationBuilder->setBody($this->alert->content)
                            ->setSound('default');
                        $notificationBuilder->setTitle($this->alert->title);
                        $notificationBuilder->setClickAction("AlertCreated");
                        $notification = $notificationBuilder->build();

                        $dataBuilder = new PayloadDataBuilder();
                        $dataBuilder->addData(['alertInfo' => $this->alert]);
                        $data = $dataBuilder->build();

                        try{
                            $downstreamResponse = FCM::sendTo($user->fcmtoken, $option, $notification, $data);
                        }
                        catch (InvalidRequestException $exception)
                        {

                        }
                    }
                }

                if($this->alert->text == 1)
                {
                    $message = $this->alert->title."\r\n".$this->alert->content;

                    if($user->phonenumber != null)
                    {
                        $phonenumber = "";
                        if(strlen($user->phonenumber) > 0)
                        {
                            if(strlen($user->phonenumber) <= 10 )
                            {
                                $phonenumber = "1".$user->phonenumber;
                            }
                            else
                            {
                                $phonenumber = $user->phonenumber;
                            }
                            try{
                                Nexmo::message()->send([
                                    'to' => $phonenumber,
                                    'from' => '16672061655',
                                    'text' => $message
                                ]);
                            }
                            catch (Exception $exception)
                            {

                            }
                        }
                    }
                }

                if($this->alert->email == 1)
                {
                    $user->notify(new AlertCreated($this->alert));
                }
            }
        }
    }
}
