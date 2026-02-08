<?php

namespace Core\Notifications;

use Illuminate\Notifications\Events\NotificationSent;
use Core\Services\PushNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

// class SendPushNotificationOnDatabaseNotification implements ShouldQueue
class SendPushNotificationOnDatabaseNotification
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function handle(NotificationSent $event): void
    {
        try {
            \Log::info('SendPushNotificationOnDatabaseNotification started', [
                'channel' => $event->channel,
                'notifiable_id' => $event->notifiable->id ?? null,
                'has_response' => $event->response !== null
            ]);

            // 1. Only trigger when notification is stored in the database
            if ($event->channel !== 'database') {
                \Log::info('Skipping - not database channel');
                return;
            }

            $notifiable = $event->notifiable;

            // 2. Check tenant context
            if (!tenancy()->initialized) {
                \Log::warning('Tenant not initialized in listener');
                return;
            }

            \Log::info('Tenant context verified', [
                'tenant_id' => tenant('id')
            ]);

            // 3. Fetch the most recent notification from the database
            // Don't rely on $event->response as it may be null in multi-tenant context
            $dbNotification = $notifiable->notifications()
                ->latest()
                ->first();

            if (!$dbNotification) {
                \Log::warning('No notification found in database', [
                    'notifiable_id' => $notifiable->id
                ]);
                return;
            }

            \Log::info('Database notification retrieved', [
                'notification_id' => $dbNotification->id,
                'data' => $dbNotification->data
            ]);

            // 4. Get the data array stored in the DB
            $data = $dbNotification->data ?? [];

            if (empty($data)) {
                \Log::warning('Notification data is empty');
                return;
            }

            // 5. Send push notification
            \Log::info('Sending push notification', [
                'user_id' => $notifiable->id,
                'message' => $data['message'] ?? 'New notification'
            ]);

            $this->pushService->sendToUser(
                $notifiable->id,
                config('app.name'),
                $data['message'] ?? 'New notification',
                $data
            );

            \Log::info('Push notification sent successfully');

        } catch (\Throwable $e) {
            \Log::error('SendPushNotificationOnDatabaseNotification failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // DO NOT rethrow - this prevents the main notification from failing
            // Push notifications should be "best effort" and not break the app
        }
    }
}