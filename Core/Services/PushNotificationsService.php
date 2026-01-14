<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected string $fcmUrl = 'https://fcm.googleapis.com/v1/projects/{project-id}/messages:send';
    
    /**
     * Send push notification to a specific user.
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        // Get all active device tokens for this user
        $deviceTokens = DeviceToken::forUser($userId)
            ->active()
            ->get();

        if ($deviceTokens->isEmpty()) {
            Log::info("No device tokens found for user {$userId}");
            return;
        }

        foreach ($deviceTokens as $deviceToken) {
            try {
                $this->sendToToken($deviceToken->token, $title, $body, $data);
                $deviceToken->markAsUsed();
            } catch (\Exception $e) {
                Log::error("Failed to send notification to token {$deviceToken->id}: {$e->getMessage()}");
                
                // If token is invalid, remove it
                if ($this->isInvalidTokenError($e)) {
                    $deviceToken->delete();
                }
            }
        }
    }

    /**
     * Send push notification to a specific device token.
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        $accessToken = $this->getAccessToken();

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $this->prepareData($data),
                'android' => [
                    'priority' => 'high',
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->post($this->fcmUrl, $payload);

        if (!$response->successful()) {
            Log::error('FCM Error: ' . $response->body());
            throw new \Exception('Failed to send push notification: ' . $response->body());
        }
    }

    /**
     * Send push notification to multiple users.
     */
    public function sendToUsers(array $userIds, string $title, string $body, array $data = []): void
    {
        foreach ($userIds as $userId) {
            $this->sendToUser($userId, $title, $body, $data);
        }
    }

    /**
     * Get FCM access token using service account.
     */
    protected function getAccessToken(): string
    {
        // Implement your OAuth2 token retrieval here
        // You can use Google's PHP client library or cache the token
        
        // Example using cached token:
        return cache()->remember('fcm_access_token', 3000, function () {
            // Your token generation logic here
            // return $client->fetchAccessTokenWithAssertion()['access_token'];
        });
    }

    /**
     * Prepare data array for FCM (all values must be strings).
     */
    protected function prepareData(array $data): array
    {
        return array_map(function ($value) {
            return is_array($value) ? json_encode($value) : (string) $value;
        }, $data);
    }

    /**
     * Check if the error is due to an invalid token.
     */
    protected function isInvalidTokenError(\Exception $e): bool
    {
        $message = $e->getMessage();
        return str_contains($message, 'NOT_FOUND') 
            || str_contains($message, 'UNREGISTERED')
            || str_contains($message, 'INVALID_ARGUMENT');
    }
}

// class PushNotificationService
// {
//     protected string $projectId;
//     protected string $serviceAccountPath;

//     public function __construct()
//     {
//         $this->projectId = config('services.fcm.project_id');
//         $this->serviceAccountPath = config('services.fcm.service_account');
//     }

//     /**
//      * Send push notification to a user (tenant-aware)
//      */
//     public function sendToUser(
//         int|string $tenantId,
//         int|string $userId,
//         string $title,
//         string $body,
//         array $data = []
//     ): void {
//         $tokens = DeviceToken::query()
//             ->where('tenant_id', $tenantId)
//             ->where('user_id', $userId)
//             ->where('is_active', true)
//             ->pluck('device_token')
//             ->all();

//         if (empty($tokens)) {
//             Log::info("FCM: No active tokens for user {$userId} (tenant {$tenantId})");
//             return;
//         }

//         foreach ($tokens as $token) {
//             $this->sendToToken($token, $title, $body, $data);
//         }
//     }

//     /**
//      * Send push notification to a single device token
//      */
//     protected function sendToToken(
//         string $deviceToken,
//         string $title,
//         string $body,
//         array $data = []
//     ): void {
//         try {
//             $accessToken = $this->getAccessToken();

//             $response = Http::withToken($accessToken)
//                 ->post(
//                     "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
//                     [
//                         'message' => [
//                             'token' => $deviceToken,
//                             'notification' => [
//                                 'title' => $title,
//                                 'body'  => $body,
//                             ],
//                             'data' => array_map('strval', $data),
//                         ],
//                     ]
//                 );

//             if ($response->failed()) {
//                 $this->handleFailure($deviceToken, $response->json());
//             }
//         } catch (\Throwable $e) {
//             Log::error('FCM exception: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Get OAuth2 access token from service account
//      */
//     protected function getAccessToken(): string
//     {
//         $credentials = new ServiceAccountCredentials(
//             ['https://www.googleapis.com/auth/firebase.messaging'],
//             json_decode(file_get_contents($this->serviceAccountPath), true)
//         );

//         $token = $credentials->fetchAuthToken();

//         return $token['access_token'];
//     }

//     /**
//      * Handle invalid / expired tokens
//      */
//     protected function handleFailure(string $token, ?array $response): void
//     {
//         $errorCode = $response['error']['status'] ?? null;

//         if (in_array($errorCode, ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
//             DeviceToken::where('device_token', $token)
//                 ->update(['is_active' => false]);

//             Log::info("FCM: Deactivated invalid token {$token}");
//         } else {
//             Log::error('FCM send failed', $response ?? []);
//         }
//     }
// }
// class PushNotificationService
// {
//     protected $fcmServerKey;
//     protected $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

//     public function __construct()
//     {
//         $this->fcmServerKey = config('services.fcm.server_key');
//     }

//     public function sendToUser($userId, $title, $body, $data = [])
//     {
//         $deviceTokens = DeviceToken::where('user_id', $userId)
//             ->where('is_active', true)
//             ->pluck('device_token')
//             ->toArray();

//         if (empty($deviceTokens)) {
//             Log::info("No active device tokens found for user: {$userId}");
//             return false;
//         }

//         return $this->sendToDevices($deviceTokens, $title, $body, $data);
//     }

//     public function sendToDevices($deviceTokens, $title, $body, $data = [])
//     {
//         try {
//             $notification = [
//                 'title' => $title,
//                 'body' => $body,
//                 'sound' => 'default',
//                 'badge' => '1'
//             ];

//             $payload = [
//                 'registration_ids' => $deviceTokens,
//                 'notification' => $notification,
//                 'data' => array_merge($data, [
//                     'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//                 ])
//             ];

//             $response = Http::withHeaders([
//                 'Authorization' => 'key=' . $this->fcmServerKey,
//                 'Content-Type' => 'application/json',
//             ])->post($this->fcmUrl, $payload);

//             if ($response->successful()) {
//                 Log::info('Push notification sent successfully');
//                 $this->handleInvalidTokens($response->json(), $deviceTokens);
//                 return true;
//             }

//             Log::error('FCM request failed: ' . $response->body());
//             return false;

//         } catch (\Exception $e) {
//             Log::error('Push notification error: ' . $e->getMessage());
//             return false;
//         }
//     }

//     protected function handleInvalidTokens($response, $deviceTokens)
//     {
//         if (isset($response['results'])) {
//             foreach ($response['results'] as $index => $result) {
//                 if (isset($result['error']) && in_array($result['error'], ['InvalidRegistration', 'NotRegistered'])) {
//                     DeviceToken::where('device_token', $deviceTokens[$index])
//                         ->update(['is_active' => false]);
//                 }
//             }
//         }
//     }
// }