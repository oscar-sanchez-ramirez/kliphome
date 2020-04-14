<?php

namespace App\Jobs;
use App\Conversation;
use App\User;
use App\Message;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Database\NewMessageNotification as NNewMessageNotification;
use Illuminate\Support\Facades\Log;

class NewMessageNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $message;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message,$user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->user->id == $this->message->from_id){
            $lastmessage = Message::where('conversation_id',$this->message->conversation_id)->orderBy('id',"desc")->offset(1)->limit(2)->first();
            $startTime = $lastmessage->created_at;
            $finishTime = Carbon::now();
            $totalDuration = ($finishTime->diffInSeconds($startTime))/60;
            if($totalDuration > 1){
                $user = User::where('id',$this->message->to_id)->first();
                $this->message["mensajeClient"] = "Tienes un nuevo mensaje";
                $this->message["mensajeFixerMan"] = "Tienes un nuevo mensaje";
                $user->notify(new NNewMessageNotification($this->message,$this->message->to_id));
            }

        }
    }
}
