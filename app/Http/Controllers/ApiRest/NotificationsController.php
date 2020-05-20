<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;

class NotificationsController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function getNotifications(Request $request,$page){

        Log::notice($page);

        $page = (5 * $page);
        $user = $request->user();
        $notifications  = DB::table('notifications')->where('notifiable_id',$user->id)
        ->offset($page)->take(5)->orderby('created_at',"DESC")->get();
        return Response(json_encode(array('notifications' => $notifications)));
    }

    public function markAllAsRead(Request $request){
        $user = $request->user();
        DB::table('notifications')->where('notifiable_id',$user->id)->update([
            'read_at' => Carbon::now()
        ]);
    }

    public function deleteAllNotifications(Request $request){
        $user = $request->user();
        DB::table('notifications')->where('notifiable_id',$user->id)->delete();
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
