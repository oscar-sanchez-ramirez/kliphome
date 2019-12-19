<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\Order;

class DisapproveOrderFixerMan implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Notification for Fixerman
        $fixerman = User::where('id',$this->fixerman_id)->first();
        $fixerman->sendNotification($fixerman->email,'DisapproveOrderFixerMan');
        //
        Order::where('id',$this->order_id)->update([
            'state' => 'FIXERMAN_NOTIFIED'
        ]);
        SelectedOrders::where('user_id',$this->fixerman_id)->where('order_id',$this->order_id)->update([
            'state' => 0
        ]);
    }
}
