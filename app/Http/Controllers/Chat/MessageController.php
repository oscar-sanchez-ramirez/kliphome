<?php

namespace App\Http\Controllers\Chat;

use DB;
use Auth;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;

class MessageController extends ApiController
{
    public function __construct()
	{
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }
    public function messenger(Request $request){
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        if($request->filled('notification_id')){
            DB::table('notifications')->where('id',$request->notification_id)->update([
                'read_at' => $date = $carbon->now()
            ]);
        }
        return view('admin.chat.index');
    }
    public function index(Request $request)
    {
        $userId = $request->user_id;
        $contactId = $request->contact_id;
        $conversationId = $request->conversation_id;
        return DB::table('conversations as c')
        ->join('messages as m','c.id','m.conversation_id')
        ->select('m.id',DB::raw('IF(m.from_id='.$userId.',1,0) as written_by_me'),'m.created_at','m.content','m.type')
        ->where('c.id',$conversationId)
        ->where(function ($query) use ($userId,$contactId){
            $query->where('m.from_id',$userId)->where('m.to_id',$contactId);
            })->orWhere(function ($query) use ($userId,$contactId){
            $query->where('m.to_id',$userId)->where('m.from_id',$contactId);
            })->orderBy('m.id',"ASC")->get();


        // return Message::select('id',DB::raw('IF(from_id='.$userId.',1,0) as written_by_me'),'created_at','content','type')
        // ->where(function ($query) use ($userId,$contactId){
        // $query->where('from_id',$userId)->where('to_id',$contactId);
        // })->orWhere(function ($query) use ($userId,$contactId){
        // $query->where('to_id',$userId)->where('from_id',$contactId);
        // })->get();
    }
    public function store(Request $request)
    {
        $message = new Message;
        $message->from_id = Auth::user()->id;
        $message->to_id = $request->to_id;
        $message->content = $request->content;
        $message->conversation_id = $request->conversation_id;
        $saved = $message->save();
        $data = [];
        $data['success'] = $saved;
        $data['message'] = $message;
        return $data;
    }
}
