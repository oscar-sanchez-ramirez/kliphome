<?php

namespace App\Notifications\OneSignal;

use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;
use Illuminate\Notifications\Notification;

class ApproveOrderFixerman extends Notification
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

    public function toOneSignal($notifiable)
    {
        //now you can build your message with the $this->data information
        return OneSignalMessage::create()
            ->subject("Tu solicitud de trabajo fue aceptada")
            ->body("Mantente al tanto de la comunicación con el cliente");
    }
}
