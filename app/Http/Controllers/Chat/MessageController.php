<?php

namespace App\Http\Controllers\Chat;

use DB;
use Auth;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function __construct()
	{
	    $this->middleware(['auth']);
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
        return Message::select('id',DB::raw('IF(from_id='.$userId.',1,0) as written_by_me'),'created_at','content')
        ->where(function ($query) use ($userId,$contactId){
        $query->where('from_id',$userId)->where('to_id',$contactId);
        })->orWhere(function ($query) use ($userId,$contactId){
        $query->where('to_id',$userId)->where('from_id',$contactId);
        })->get();
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
}
