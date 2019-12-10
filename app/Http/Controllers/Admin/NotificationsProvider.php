<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use OneSignal;
use App\Jobs\NotifyNewOrder;
use App\Order;

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
        $order = Order::where('id',10)->first();
        $category = $this->table($order->type_service,$order->selected_id);
        $user_match_categories = DB::table('users as u')->join('selected_categories as sc','u.id','sc.user_id')->select('u.*')->where('sc.category_id',$category[0]->id)->where('u.state',1)->get();
        foreach ($user_match_categories as $key) {
            $user = User::where('id',$key->id)->first();
            return $user->sendNotificationOrderMatch($user->email);
        }
        Order::where('id',$this->order->id)->update([
            'state' => "FIXERMAN_NOTIFIED"
        ]);
    }


    private function table($type_service,$id){
        switch ($type_service) {
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id')->where('subse.id',$id)->get();
                return $category;
                break;

            default:
                # code...
                break;
        }

    }
}
