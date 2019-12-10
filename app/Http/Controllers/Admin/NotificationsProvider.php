<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use OneSignal;
use App\Jobs\NotifyNewOrder;

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

    public function testMatch(){
        $order = Order::where('id',19)->first();
        $user = new NotifyNewOrder($order);
    }
}
