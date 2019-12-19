<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class NotificationsController extends Controller
{
    public function getNotifications($id){
        $notifications  = DB::table('notifications')->where('notifiable_id',$id)->get();
        return Response(json_encode(array('notifications' => $notifications)));
    }
}
