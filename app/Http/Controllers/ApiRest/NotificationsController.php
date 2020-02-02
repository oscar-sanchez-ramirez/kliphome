<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;
use DB;

class NotificationsController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getNotifications($id){
        $notifications  = DB::table('notifications')->where('notifiable_id',$id)->orderby('created_at',"DESC")->get();
        return Response(json_encode(array('notifications' => $notifications)));
    }

    public function markAsRead($id){
        DB::table('notifications')->where('id',$id)->update([
            'read_at' => Carbon::now()
        ]);
    }

    public function deleteNotification($id){
        DB::table('notifications')->where('id',$id)->delete();
    }
}
