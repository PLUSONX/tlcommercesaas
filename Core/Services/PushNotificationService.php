<?php

namespace Core\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class PushNotificationService
{
    protected string $fcmUrl = 'https://fcm.googleapis.com/v1/projects/platepilot-a0df3/messages:send';
    /**
     * Send push notification to a specific user.
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        \Log::info('sendToUser method called !!!!');
        // dd($userId);
        // Get all active device tokens for this user
        $deviceTokens = DeviceToken::forUser($userId)
            ->active()
            ->get();

        \Log::info('Device Token data', [
                'deviceTokens' => json_encode($deviceTokens),
        ]);

        // dd($deviceTokens);

        if ($deviceTokens->isEmpty()) {
            Log::info("No device tokens found for user {$userId}");
            return;
        }

        foreach ($deviceTokens as $deviceToken) {
            try {

                // $platform = strtolower($deviceToken->platform);
        
                $this->sendToToken($deviceToken->token, $title, $body, $data);

                \Log::info('notification Successfully Sent to firebase!!!!');


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
    // protected function sendToToken($token, $title, $body, array $data): void
    // {
    //     $accessToken = $this->getGoogleAccessToken();

    //     \Log::info('Google Token fetched!!!!');

    //     \Log::info('DEBUG: Sending FCM request', [
    //         'url' => $this->fcmUrl,
    //         'token_start' => substr($accessToken, 0, 10) . '...',
    //     ]);

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $accessToken,
    //         'Content-Type' => 'application/json',
    //     ])->post($this->fcmUrl, [
    //         'message' => [
    //             'token' => $token,
    //             'notification' => [
    //                 'title' => $title,
    //                 'body' => $body,
    //             ],
    //             'android' => [
    //                 'priority' => 'high',
    //             ],
    //             'apns' => [
    //                 'payload' => [
    //                     'aps' => [
    //                         'sound' => 'default',
    //                         'content-available' => 1,
    //                         'mutable-content' => 1,
    //                     ],
    //                 ],
    //                 'headers' => [
    //                     'apns-push-type' => 'alert',
    //                     'apns-priority' => '10',
    //                     'apns-topic' => 'com.plusonx.platepilots',
    //                 ],
    //             ],
    //             'data' => array_map('strval', $data),
    //         ],
    //     ]);

    //     // $response = Http::withToken($accessToken)->post($this->fcmUrl, [
    //     //     'message' => [
    //     //         'token' => $token,
    //     //         'notification' => [
    //     //             'title' => $title,
    //     //             'body'  => $body,
    //     //         ],
    //     //         'android' => [
    //     //             'priority' => 'high',
    //     //         ],
    //     //         'apns' => [
    //     //             'payload' => [
    //     //                 'aps' => [
    //     //                     'sound' => 'default',
    //     //                     'content-available' => 1,
    //     //                     'mutable-content' => 1,
    //     //                 ],
    //     //             ],
    //     //             'headers' => [
    //     //                 'apns-push-type' => 'alert',
    //     //                 'apns-priority' => '10',
    //     //                 'apns-topic' => 'com.plusonx.platepilots',
    //     //             ],
    //     //         ],
    //     //         'data' => array_map('strval', $data),
    //     //     ],
    //     // ]);

    //     if ($response->failed()) {

    //         \Log::error("Google api system crashed!", [
    //                 'Full_Error_Body' => $response->json(), // This is the gold mine
    //                 'Status' => $response->status(),
    //                 'headers' => $response->headers(),
    //             ]);
    //     }

    //     if (!$response->successful()) {
    //         throw new \Exception("FCM Error: " . $response->body());
    //     }
    // }

    protected function sendToToken($token, $title, $body, array $data): void 
{
    $accessToken = $this->getGoogleAccessToken();
    
    $headers = [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ];
    
    $payload = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'android' => [
                'priority' => 'high',
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => 'default',
                        'content-available' => 1,
                        'mutable-content' => 1,
                    ],
                ],
                'headers' => [
                    'apns-push-type' => 'alert',
                    'apns-priority' => '10',
                    'apns-topic' => 'com.plusonx.platepilots',
                ],
            ],
            'data' => array_map('strval', $data),
        ],
    ];
    
    // DEBUG: Log what we're actually sending
    \Log::info('FCM Request Details', [
        'url' => $this->fcmUrl,
        'authorization_header_present' => isset($headers['Authorization']),
        'authorization_starts_with_bearer' => str_starts_with($headers['Authorization'] ?? '', 'Bearer '),
        'token_length' => strlen($accessToken),
        'headers' => array_map(function($h) {
            return substr($h, 0, 30) . '...';
        }, $headers),
    ]);

    $response = Http::withHeaders($headers)->post($this->fcmUrl, $payload);

    // DEBUG: Log the actual request that was sent
    \Log::info('FCM Response Details', [
        'status' => $response->status(),
        'request_headers_sent' => $response->transferStats?->getRequest()?->getHeaders() ?? 'Not available',
    ]);

    if ($response->failed()) {
        \Log::error("FCM request failed", [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);
    }

    if (!$response->successful()) {
        throw new \Exception("FCM Error: " . $response->body());
    }
    
    \Log::info('FCM notification sent successfully');
}


    /**
     * Generate a short-lived OAuth2 access token for FCM v1.
     */
    protected function getGoogleAccessToken(): string
    {
        $keyPath = base_path('storage/app/platepilot-a0df3-firebase-adminsdk-fbsvc-7fefa4d366.json');
        // $keyPath = base_path('storage/app/google-services.json');
        
        $jsonContent = json_decode(file_get_contents($keyPath), true);

        \Log::info('Service Account Info', [
            'project_id' => $jsonContent['project_id'] ?? 'MISSING',
            'client_email' => $jsonContent['client_email'] ?? 'MISSING',
            'private_key_exists' => isset($jsonContent['private_key']),
            'type' => $jsonContent['type'] ?? 'MISSING',
        ]);

        if (!isset($jsonContent['type']) || $jsonContent['type'] !== 'service_account') {
            throw new \Exception("Invalid JSON: not a service_account type");
        }
        // Use the broader scope to rule out permission gaps
        $scope = ['https://www.googleapis.com/auth/cloud-platform'];
        //  $scope = ['https://www.googleapis.com/auth/firebase.messaging'];

         $credentials = new ServiceAccountCredentials($scope, $jsonContent);

        // $credentials = new ServiceAccountCredentials($scope, $keyPath);

        // Ensure SSL is handled for Windows/Laragon
        $certPath = "C:/laragon/etc/ssl/cacert.pem";
        if (file_exists($certPath)) {
            ini_set('curl.cainfo', $certPath);
            ini_set('openssl.cafile', $certPath);
        }

        $token = $credentials->fetchAuthToken(HttpHandlerFactory::build());

        \Log::info('Token Response', [
            'has_access_token' => isset($token['access_token']),
            'token_type' => $token['token_type'] ?? 'N/A',
            'expires_in' => $token['expires_in'] ?? 'N/A',
            'error' => $token['error'] ?? null,
        ]);

        if (!isset($token['access_token'])) {
            throw new \Exception("Auth failed: " . ($token['error_description'] ?? 'Unknown Error'));
        }

        return $token['access_token'];
    }
    // protected function getGoogleAccessToken(): string
    // {
    //     // the path to the secure JSON key
    //     $keyPath = base_path('storage/app/platepilot-a0df3-firebase-adminsdk-fbsvc-7fefa4d366.json');

    //     if (!file_exists($keyPath)) {
    //         Log::error("FCM Service Account file not found at: " . $keyPath);
    //         throw new \Exception("FCM Service Account file not found.");
    //     }
    //     // the required scope for Firebase Messaging
    //     $scope = 'https://www.googleapis.com/auth/firebase.messaging';

    //     // Initializing the Service Account Credentials
    //     $credentials = new ServiceAccountCredentials($scope, $keyPath);

    //     $certPath = "C:/laragon/etc/ssl/cacert.pem";
    //     ini_set('curl.cainfo', $certPath);
    //     ini_set('openssl.cafile', $certPath);
     
    //     $token = $credentials->fetchAuthToken(HttpHandlerFactory::build());

    //     \Log::info('Token initialized!!!!');

        
    //     return $token['access_token'];
    // }

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
