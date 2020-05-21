<?php

namespace App\Notifications\FixerMan;

use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;
use Illuminate\Notifications\Notification;

class OrderCancelled extends Notification
{
    // use Queueable;
    protected $client;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($client)
    {
        //
        $this->client = $client;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [OneSignalChannel::class];
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
    public function toOneSignal($notifiable)
    {

        return OneSignalMessage::create()
        ->subject("Tu servicio con ".$this->user->name." ha sido cancelado")
        ->body("¡No olvides prepararte!")->setData("type",'App\\Notifications\\Database\\OrderCancelled')->setData('data',$this->client);
    }
}
