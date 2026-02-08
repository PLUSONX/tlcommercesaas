<?php

namespace Plugin\TlcommerceCore\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// use Stancl\Tenancy\Contracts\SyncMaster;

// class CustomerOrderCreateNotification extends Notification
class CustomerOrderCreateNotification extends Notification 
{
    use Queueable;

    protected $data;

    // public $tenantId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        \Log::info('CustomerOrderCreateNotification __construct called', [
            'data' => $data
        ]);

        $this->data = $data;

        \Log::info('CustomerOrderCreateNotification __construct completed');

        // $this->tenantId = tenant('id');

        // $this->onConnection('tenant'); 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        \Log::info('via() method called', [
            'notifiable_id' => $notifiable->id ?? 'unknown',
            'notifiable_class' => get_class($notifiable)
        ]);

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
    public function toDatabase($notifiable)
    {
        \Log::info('toDatabase() method called', [
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'data' => $this->data,
            'current_tenant' => tenant('id'),
            'db_connection' => \DB::connection()->getDatabaseName()
        ]);
        
        $result = [
            'message' => $this->data['message'],
            'link' => $this->data['link'],
        ];
        
        \Log::info('toDatabase() returning', ['result' => $result]);
        
        return $result;
        // \Log::info('toDatabase called', [
        //     'notifiable_id' => $notifiable->id,
        //     'notifiable_type' => get_class($notifiable),
        //     'class' => $notifiable->getMorphClass(),
        //     'data' => $this->data,
        //     'current_tenant' => tenant('id'),
        //     'db_connection' => \DB::connection()->getDatabaseName()
        // ]);
        // return [
        //     'message' => $this->data['message'],
        //     'link' => $this->data['link'],
        // ];
    }
}
