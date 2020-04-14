<?php

namespace App\Jobs;
use App\Conversation;
use App\User;
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
        // $last_message = Conversation::where('id',$this->message->conversation_id)->orderBy('id', 'desc')->first();
        Log::notice($this->user);
        Log::notice($this->message);
        if($this->user->id == $this->message->from_id){
            $user = User::where('id',$this->message->to_id)->first();
            $user->notify(new NNewMessageNotification($last_message));
        }
    }
}
