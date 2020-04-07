<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;
use App\Message;
use App\Conversation;
use App\Events\MessageSent;

class MessageObserver
{
    public function created(Message $message)
    {
      Log::notice($message);
        $conversation = Conversation::where('user_id',$message->from_id)->where('contact_id',$message->to_id)->first();

        if ($conversation) {
          if($message->type == "image"){
            $conversation->last_message = 'Tú: Imagen';
          }else{
            $conversation->last_message = 'Tú: '.$message->content;
          }
          $conversation->last_time = $message->created_at;
          $conversation->save();
        }
        $conversation = Conversation::where('contact_id',$message->from_id)->where('user_id',$message->to_id)->first();
        if ($conversation) {
          if($message->type == "image"){
            $conversation->last_message = $conversation->contact_name["name"].': Imagen';
          }else{
            $conversation->last_message = $conversation->contact_name["name"].': '.$message->content;
          }
          $conversation->last_time = $message->created_at;
          $conversation->save();
        }
        event(new MessageSent($message));
    }
}
