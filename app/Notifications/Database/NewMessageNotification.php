<?php

namespace App\Notifications\Database;

use OneSignal;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMessageNotification extends Notification
{
    use Queueable;
    protected $message;
    protected $user_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message,$user_id)
    {
        $this->message = $message;
        $this->user_id = $user_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $user = User::where('id',$this->user_id)->first();
        if($user->type == "AppFixerMan"){
            $user->sendNotification($user->email,'NewMessageNotification',$this->message);
        }else{
            $type = "App\\Notifications\\Database\\NewMessageNotification";
            $content = $this->message;
            OneSignal::sendNotificationUsingTags(
                "¡Tienes un nuevo mensaje!",
                array(
                    ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $user->email],
                ),
                $type,
                $content,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );
        }
        return $this->message;
    }
}
