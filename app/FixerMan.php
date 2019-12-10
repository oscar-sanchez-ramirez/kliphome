<?php

namespace App;


class FixerMan extends User
{
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
