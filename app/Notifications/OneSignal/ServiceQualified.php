<?php

namespace App\Notifications\OneSignal;

use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;
use Illuminate\Notifications\Notification;

class ServiceQualified extends Notification
{
   // use Queueable;
   private $data;
   /**
    * Create a new notification instance.
    *
    * @return void
    */
   public function __construct($data)
   {
       $this->data = $data;
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
        //now you can build your message with the $this->data information
        return OneSignalMessage::create()
            ->subject("Tu servicio fue calificado, ¡Échale un vistazo!")
            ->body("Mantente al tanto de tus servicios");
    }
}
