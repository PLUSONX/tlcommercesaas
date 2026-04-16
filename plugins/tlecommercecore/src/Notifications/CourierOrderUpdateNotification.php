<?php

namespace Plugin\TlcommerceCore\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourierOrderUpdateNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->data['message'],
            'link'    => $this->data['link'],
        ];
    }
}