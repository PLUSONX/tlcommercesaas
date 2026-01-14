<?php

namespace Core\Notifications;

use Illuminate\Notifications\Events\NotificationSent;
use Core\Services\PushNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPushNotificationOnDatabaseNotification implements ShouldQueue
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function handle(NotificationSent $event): void
    {
        // Only trigger when notification is stored in DB
        if ($event->channel !== 'database') {
            return;
        }

        $notification = $event->notification;
        $notifiable   = $event->notifiable;

        if (!method_exists($notification, 'toArray')) {
            return;
        }

        $data = $notification->toArray($notifiable);

        $this->pushService->sendToUser(
            $notifiable->id,
            config('app.name'),
            $data['message'] ?? 'New notification',
            $data
        );
    }
}
