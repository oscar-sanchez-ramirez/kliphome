<?php
namespace App;
use App\User;
use App\Notifications\AproveFixerMan;
use Illuminate\Notifications\Notifiable;


class FixerMan extends User
{
    use Notifiable;
    public function sendNotification($email)
    {
        $this->email = $email;
        $this->notify(new AproveFixerMan($this)); //Pass the model data to the OneSignal Notificator
    }
    public function routeNotificationForOneSignal()
    {
        return ['tags' => ['key' => 'email', 'relation' => '=', 'value' => $this->email]];
    }
}
