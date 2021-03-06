<?php

namespace App\Notifications\Database;
use OneSignal;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmArrive extends Notification
{
    use Queueable;
    protected $order_id;
    protected $fixerman_name;
    protected $client_email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order_id,$fixerman_name,$client_email)
    {
        $this->order_id = $order_id;
        $this->fixerman_name = $fixerman_name;
        $this->client_email = $client_email;
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
        $content = Order::where('id',$this->order_id)->first();
        $content["mensajeClient"] = "Tu kliper ha llegado a tu domicilio";
        $content["type"] = 'App\Notifications\Database\ConfirmArrive';
        OneSignal::sendNotificationUsingTags(
            "Tu kliper ha llegado a tu domicilio",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $this->client_email],
            ),
            $url = null,
            $content,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        return $content;
    }
}
