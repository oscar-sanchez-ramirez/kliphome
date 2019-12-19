<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use DB;

class NotificationsController extends ApiController
{
    public function getNotifications($id){
        $notifications  = DB::table('notifications')->where('notifiable_id',$id)->get();
        return Response(json_encode(array('notifications' => $notifications)));
    }
}
