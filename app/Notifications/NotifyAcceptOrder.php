<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use OneSignal;

class NotifyAcceptOrder extends Notification
{
    use Queueable;
    protected $selected_order;
    protected $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($selected_order,$email)
    {
        $this->selected_order = $selected_order;
        $this->email = $email;
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
        OneSignal::sendNotificationUsingTags(
            "Un Técnico ha aceptado la solicitud para tu solicitud",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $this->email],
            ),
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        return $this->selected_order;
    }
}
