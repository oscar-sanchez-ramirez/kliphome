<?php

namespace App\Jobs;

use App\User;
use App\Notifications\Database\ApproveFixerMan;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AproveFixerMan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where('id',$this->user_id)->first();
        $user->mensajeFixerMan = "Tu cuenta fue aprobada";
        $user->notify(new ApproveFixerMan($user));
        // $notification = $user->notifications()->first();
        // $user->created_at = $notification->id;
        // $user->sendNotification($user->email,'ApproveFixerMan',$user);
    }
}
