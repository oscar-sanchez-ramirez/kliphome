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
	    // $this->middleware(['auth']);
    }
    public function messenger(){
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        DB::table('notifications')->where('type',"App\Notifications\NotificacionNuevaConversacion")->whereNull('read_at')->where('notifiable_id',Auth::user()->id)->update([
        'read_at' => $date = $carbon->now()
        ]);
        return view('admin.chat.index');
    }
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $contactId = $request->contact_id;
        return Message::select('id',DB::raw('IF(from_id='.$userId.',1,0) as written_by_me'),'created_at','content','type')
        ->where(function ($query) use ($userId,$contactId){
        $query->where('from_id',$userId)->where('to_id',$contactId);
        })->orWhere(function ($query) use ($userId,$contactId){
        $query->where('to_id',$userId)->where('from_id',$contactId);
        })->get();
    }

    public function indexRest($userId,$contactId,$page){
        if($page == 0)
        {
            $page = 1;
        }

        $page = (5 * $page)-5;


        return Message::select('id',DB::raw('IF(from_id='.$userId.',1,0) as written_by_me'),'created_at','content','type')
        ->where(function ($query) use ($userId,$contactId){
        $query->where('from_id',$userId)->where('to_id',$contactId);
        })->orWhere(function ($query) use ($userId,$contactId){
        $query->where('to_id',$userId)->where('from_id',$contactId);
        })->offset($page)->take(5)->orderBy('id',"ASC")->get();
    }
    public function store(Request $request)
    {
        $message = new Message;
        $message->from_id = Auth::user()->id;
        $message->to_id = $request->to_id;
        $message->content = $request->content;
        $saved = $message->save();
        $data = [];
        $data['success'] = $saved;
        $data['message'] = $message;
        return $data;
    }
    public function storeRest(Request $request){
        $message = new Message;
        $message->from_id = $request->user_id;
        $message->to_id = $request->to_id;
        $message->content = $request->content;
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
