<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use App\User;
use App\Order;
use Illuminate\Support\Facades\Log;

class NotifyNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;

    /**
     * IN THIS JOB WILL NOTIFY TO FIXERMEN WITH CATEGORIES MATCHED
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info('entrando a dispatch');
        $order = Order::where('id',$this->id)->first();
        Log::notice($order);
        $category = $this->table($order->type_service,$order->selected_id);
        Log::notice($category);
        $user_match_categories = DB::table('users as u')->join('selected_categories as sc','u.id','sc.user_id')->select('u.*')->where('sc.category_id',$category[0]->id)->where('u.state',1)->get();
        Log::notice($user_match_categories);
        foreach ($user_match_categories as $key) {
            $user = User::where('id',$key->id)->first();
            $user->sendNotification($user->email,'sendNotificationOrderMatch');
        }

        Order::where('id',$this->id)->update([
            'state' => "FIXERMAN_NOTIFIED"
        ]);
    }

    private function table($type_service,$id){
        switch ($type_service) {
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id')->where('subse.id',$id)->get();
                return $category;
                break;
            case 'Category':
                $category = DB::table('categories')->select('title as service','id')->where('id',$id)->get();
                return $category;
                break;
            default:
                # code...
                break;
        }

    }
}
