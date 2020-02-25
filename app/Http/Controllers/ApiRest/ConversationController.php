<?php

namespace App\Http\Controllers\ApiRest;

use Auth;
use App\Order;
use App\User;
use Carbon\Carbon;
use App\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\Database\NewConversation;
use App\Notifications\Database\NewConversationAdmin;
use App\Http\Controllers\ApiController;

class ConversationController extends ApiController
{
    public function __construct()
  {
      $this->middleware('auth:api');
  }
  public function index()
  {
    return Conversation::where('user_id',Auth::user()->id)->orderBy('last_time',"DESC")->get([
        'id','contact_id','has_blocked','listen_notifications','last_message','last_time'
    ]);
  }
  public function indexRest($id){
    return Conversation::where('user_id',$id)->orderBy('last_time',"DESC")->get([
      'id','contact_id','has_blocked','listen_notifications','last_message','last_time'
    ]);
  }
  public function nueva_conversacion($id,$nombres,$id_anuncio){
    $check_conversation = Conversation::where('user_id',Auth::user()->id)->where('contact_id',$id)->first();
    $anuncio = Order::find($id_anuncio);
    if(!$check_conversation){
      $con_auth = new Conversation;
      $con_auth->user_id = Auth::user()->id;
      $con_auth->contact_id = $id;
      $con_auth->last_time = Carbon::now();
      $con_auth->last_message = "Bienvenido a KlipHome, aqui podrás conversar con ".$nombres;
      $con_auth->order_id = $id_anuncio;
      $con_auth->save();
      $con = new Conversation;
      $con->user_id = $id;
      $con->contact_id = Auth::user()->id;
      $con->last_time = Carbon::now();
      $con->last_message = "Bienvenido a KlipHome, aqui podrás conversar con ".ucfirst(strtolower(Auth::user()->nombres));
      $con->order_id = $id_anuncio;
      $con->save();

      $usuario_anuncio = User::find($id);
      $usuario_anuncio->notify(new NewConversation($con));
    }
    return Redirect::action('Chat\MessageController@messenger');
  }
  public function new_conversation(Request $request){

    if($request->to_id == "to_id"){
      $admin = User::where('type','ADMINISTRATOR')->first();
      $check_conversation = Conversation::where('user_id',$request->user_id)->where('contact_id',$user->id)->first();
      $order = 0;
      $contact = $admin->id;
      $admin->notify(new NewConversationAdmin($request->all()));
    }else{
      $check_conversation = Conversation::where('user_id',$request->user_id)->where('contact_id',$request->to_id)->first();
      $order = $request->order_id;
      $contact = $admin->user_id;
    }

    if(!$check_conversation){
      $con_auth = new Conversation;
      $con_auth->user_id = $request->user_id;
      $con_auth->contact_id = $contact;
      $con_auth->last_time = Carbon::now();
      $con_auth->last_message = "Pulsa aquí para empezar";
      $con_auth->order_id = $order;
      $con_auth->save();
      $con = new Conversation;
      $con->user_id = $request->to_id;
      $con->contact_id = $contact;
      $con->last_time = Carbon::now();
      $con_auth->last_message = "Pulsa aquí para empezar";
      $con->order_id = $order;
      $con->save();

      // $usuario_anuncio = User::find($request->to_id);
      // $usuario_anuncio->notify(new NewConversation($con));
    }
  }
}
