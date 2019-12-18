<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OneSignal;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Log;

class ApproveOrderFixerMan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $fixerman_id;
    protected $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fixerman_id,$order_id)
    {
        $this->fixerman_id = $fixerman_id;
        $this->order_id = $order_id;
        Log::notice("2");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Notification for Client
        $order = Order::where('id',$this->order_id)->first();
        $user_order = User::where('id',$order->user_id)->first();
        Log::notice("3");
        Log::notice($order);
        Log::notice($user_order);
        // OneSignal::sendNotificationToAll(
        //     "this is a test from laravel",
        //     $url = null,
        //     $data = null,
        //     $buttons = null,
        //     $schedule = null
        // );
        OneSignal::sendNotificationUsingTags(
            "Un Técnico ha aceptado la solicitud para tu solicitud",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $user_order->email],
            ),
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        Log::notice("4");
        //Notification for Fixerman
        $fixerman = User::where('id',$this->fixerman_id)->first();
        $client = new Berkayk\OneSignal\OneSignalClient(
            getenv('a8a80cb1-1654-4ccc-92be-54dff3e0171e'),
            getenv('NTRkOGJkNGUtOGJhMy00NTMyLWEyYWQtOTk2MTMyM2ZiYTA1'),
            getenv('OGVmYmUxMmYtMDMzYi00ZGJlLTk1NjMtYzY5ZjQ0Y2JkNmZl'));

        Log::notice($client->testCredentials());
        OneSignal::sendNotificationUsingTags(
            "Un Técnico ha aceptado la solicitud para tu solicitud",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $fixerman->email],
            ),
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        // $fixerman->sendNotification($fixerman->email,'ApproveOrderFixerMan');
        //
        Log::notice("5");
        Order::where('id',$this->order_id)->update([
            'state' => 'FIXERMAN_APPROVED'
        ]);
    }
}
