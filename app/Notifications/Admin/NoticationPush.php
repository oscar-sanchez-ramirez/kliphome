<?php

namespace App\Notifications\Admin;

use OneSignal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NoticationPush extends Notification
{
    use Queueable;
    protected $cliente;
    protected $mensaje;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cliente,$mensaje)
    {
        $this->cliente = $cliente;
        $this->mensaje = $mensaje;
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
        if($this->cliente->type == "AppUser"){
            $type = "App\\Notifications\\Database\\CustomNotification";
            $content = $this->cliente;
            $url = 'file:///android_asset/www/index.html';
            OneSignal::sendNotificationUsingTags(
                $this->mensaje,
                array(
                    ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $this->cliente->email],
                ),
                $type,
                $content,
                $url=null,
                $data = null,
                $buttons = null,
                $schedule = null
            );
        }
        return $this->cliente;
    }
}
