<?php

namespace App\Notifications\Database;

use OneSignal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CancelOrder extends Notification
{
    use Queueable;
    protected $order;
    protected $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order,$email)
    {
        $this->order = $order;
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
        $content = $this->order;
        OneSignal::sendNotificationUsingTags(
            "Tu orden de servicio ha sido cancelada",
            array(
                ["field" => "tag", "key" => "email",'relation'=> "=", "value" => $this->email],
            ),
            $url = null,
            $content,
            $data = null,
            $buttons = null,
            $schedule = null
        );
        return $this->order;
    }
}
