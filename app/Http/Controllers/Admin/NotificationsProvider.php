<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use OneSignal;


class NotificationsProvider extends Controller
{
    public function test(){
        // OneSignal::sendNotificationToAll(
        //     "this is a test from laravel",
        //     $url = null,
        //     $data = null,
        //     $buttons = null,
        //     $schedule = null
        // );
        OneSignal::sendNotificationUsingTags(
            "Some Message",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => "germanruelas17@gmail.com"],
            ),
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
    }
}
