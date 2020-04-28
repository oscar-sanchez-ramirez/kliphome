<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use Auth;
use App\Order;
use App\User;
use Carbon\Carbon;
use App\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\Database\NewConversationAdmin;

class ConversationController extends ApiController
{
  public function __construct(){
      $this->middleware('auth:api');
  }

  public function indexRest($id){
    return DB::table('conversations as c')->join('users as u','c.contact_id','u.id')->join('users as us','c.user_id','us.id')->join('orders as o','c.order_id','o.id')
    ->select('c.id','c.contact_id','c.user_id','c.has_blocked','c.listen_notifications','c.last_readed','c.last_message','c.last_time','c.order_id','u.name','u.lastName','u.avatar','us.name as name_to','us.lastName as lastName_to','us.avatar as avatar_to',DB::raw('IF(c.user_id='.$id.',1,0) as written_by_me'))
    ->where('c.user_id',$id)->orWhere('c.contact_id',$id)
    ->where(function ($query){
      $query->where('o.state','!=','CANCELLED')->orWhere('o.state','!=','QUALIFIED')->orWhere('o.state','!=','FIXERMAN_DONE');
    })
    ->get();
  }

  public function markConversationAsRead(Request $request){
    Conversation::where('id',$request->conversation_id)->update([
      'last_readed' => Carbon::now()
    ]);
  }

  public function new_conversation(Request $request){

    if($request->to_id == "admin"){
      $admin = User::where('type','ADMINISTRATOR')->first();
      $check_conversation = Conversation::where('user_id',$request->user_id)->where('contact_id',$admin->id)->where('order_id',$request->order_id)->first();
      if(!$check_conversation){
        $con_auth = new Conversation;
        $con_auth->user_id = $request->user_id;
        $con_auth->contact_id = $admin->id;
        $con_auth->last_time = Carbon::now();
        $con_auth->last_message = "Pulsa aquí para empezar";
        $con_auth->order_id = $request->order_id;
        $con_auth->type = 'admin';
        $con_auth->save();
        $admin->notify(new NewConversationAdmin($request->all()));
        return response()->json([
          'conversation' => $con_auth
        ]);
      }else{
        return response()->json([
          'conversation' => $check_conversation
        ]);
      }
    }else{
      $check_conversation = Conversation::where('user_id',$request->user_id)->where('contact_id',$request->to_id)->where('order_id',$request->order_id)->first();
      if(!$check_conversation){
        $con_auth = new Conversation;
        $con_auth->user_id = $request->user_id;
        $con_auth->contact_id = $request->to_id;
        $con_auth->last_time = Carbon::now();
        $con_auth->last_message = "Pulsa aquí para empezar";
        $con_auth->order_id = $request->order_id;
        $con_auth->type = 'user';
        $con_auth->save();
        return response()->json([
          'conversation' => $con_auth
        ]);
      }else{
        return response()->json([
          'conversation' => $check_conversation
        ]);
      }
    }
  }
}
