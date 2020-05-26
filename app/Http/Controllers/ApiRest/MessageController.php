<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use Auth;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Jobs\NewMessageNotification;
use Illuminate\Support\Facades\Log;


class MessageController extends ApiController
{
    public function __construct()
	{
	    $this->middleware('auth:api');
    }

    public function indexRest($userId,$contactId,$conversationId,$page){
        Log::notice($page);
        if($page == 0)
        {
            $page = 1;
        }
        $page = (5 * $page)-5;
        Log::notice($page);


        return DB::table('conversations as c')
        ->join('messages as m','c.id','m.conversation_id')
        ->select('m.id',DB::raw('IF(m.from_id='.$userId.',1,0) as written_by_me'),'m.created_at','m.content','m.type')
        ->where('c.id',$conversationId)->offset($page)->take(5)->orderBy('m.id',"DESC")->get();
    }

    public function storeRest(Request $request){
        $user = $request->user();
        $message = new Message;
        $message->from_id = $request->user_id;
        $message->to_id = $request->to_id;
        $message->content = $request->content;
        $message->conversation_id = $request->conversation_id;
        if($request->filled('type')){
            $message->type = $request->type;
        }
        $saved = $message->save();
        $data = [];
        $data['success'] = $saved;
        $data['message'] = $message;
        dispatch(new NewMessageNotification($message,$user));
        return $data;
    }
}
