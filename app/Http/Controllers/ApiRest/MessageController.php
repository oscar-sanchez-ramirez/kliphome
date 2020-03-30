<?php

namespace App\Http\Controllers\ApiRest;

use DB;
use Auth;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MessageController extends ApiController
{
    public function __construct()
	{
	    $this->middleware('auth:api');
    }
    // public function index(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $contactId = $request->contact_id;
    //     return Message::select('id',DB::raw('IF(from_id='.$userId.',1,0) as written_by_me'),'created_at','content','type')
    //     ->where(function ($query) use ($userId,$contactId){
    //     $query->where('from_id',$userId)->where('to_id',$contactId);
    //     })->orWhere(function ($query) use ($userId,$contactId){
    //     $query->where('to_id',$userId)->where('from_id',$contactId);
    //     })->get();
    // }

    public function indexRest($userId,$contactId,$order_id,$page){
        if($page == 0)
        {
            $page = 1;
        }

        $page = (5 * $page)-5;

        return DB::table('conversations as c')
        ->join('messages as m','c.id','m.conversation_id')
        ->select('m.id',DB::raw('IF(m.from_id='.$userId.',1,0) as written_by_me'),'m.created_at','m.content','m.type')
        ->where('c.order_id',$order_id)->offset($page)->take(5)->orderBy('m.id',"DESC")->get();

    }
    // public function store(Request $request)
    // {
    //     $message = new Message;
    //     $message->conversation_id = $request->conversation_id;
    //     $message->from_id = Auth::user()->id;
    //     $message->to_id = $request->to_id;
    //     $message->content = $request->content;
    //     $saved = $message->save();
    //     $data = [];
    //     $data['success'] = $saved;
    //     $data['message'] = $message;
    //     return $data;
    // }
    public function storeRest(Request $request){
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
        return $data;
    }
}
