<?php

namespace Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Core\Http\Requests\StoreDeviceTokenRequest;
use Illuminate\Support\Facades\Log;

class DeviceTokenController extends Controller
{
     
      public function store(StoreDeviceTokenRequest $request): JsonResponse
    {
        try {

        

            $validated = $request->validated();

            // Handle old token if provided (deactivate or remove)
            if (!empty($validated['old_token'])) {
                $this->handleOldToken($validated['old_token']);
            }

            // Pre-login registration: only touch the anonymous row for this token
            $existingToken = DeviceToken::where('token', $validated['new_token'])
                ->whereNull('tenant_id')
                ->first();

            if ($existingToken) {
                // Reactivate if exists
                $existingToken->update([
                    'platform' => $validated['platform'],
                    'is_active' => true,
                    'last_used_at' => now(),
                ]);
                
                $deviceToken = $existingToken;
            } else {
                // Create new token (without user_id and tenant_id initially)
                $deviceToken = DeviceToken::create([
                    'token' => $validated['new_token'],
                    'platform' => $validated['platform'],
                    'is_active' => true,
                    'last_used_at' => now(),
                ]);
            }

            // DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Device token registered successfully',
                'data' => [
                    'id' => $deviceToken->id,
                    'token' => $deviceToken->token,
                    'platform' => $deviceToken->platform,
                ]
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to register device token',
            ], 500);
        }
    }

    /**
     * Handle old token - mark as inactive or delete
     */
    private function handleOldToken(string $oldToken): void
    {
        DeviceToken::where('token', $oldToken)
            ->update(['is_active' => false]);
    }

    /**
     * This method would be called after successful login
     * to associate the device token with user and tenant
     */
    public function associateWithUser(string $token, int $userId, ?string $tenantId = null): void
    {

        Log::info('associateWithUser method called!!!', [
            'token' => $token,
            'userId' => $userId,
            'tenantId' => $tenantId,
        ]);

        $deviceToken = DeviceToken::firstOrNew([
            'token' => $token,
            'tenant_id' => $tenantId,
        ]);
        $wasCreated = !$deviceToken->exists;

        $deviceToken->user_id = $userId;
        $deviceToken->is_active = true;
        $deviceToken->last_used_at = now();

        if ($wasCreated) {
            $deviceToken->platform = 'android';
        }

        $deviceToken->save();

        // Drop orphaned pre-login row now that this token is tenant-bound
        DeviceToken::where('token', $token)
            ->whereNull('tenant_id')
            ->whereNull('user_id')
            ->where('id', '!=', $deviceToken->id)
            ->delete();

        Log::info('associateWithUser completed', [
            'wasRecentlyCreated' => $wasCreated,
            'id' => $deviceToken->id,
            'user_id' => $deviceToken->user_id,
            'tenant_id' => $deviceToken->tenant_id,
            'is_active' => $deviceToken->is_active,
        ]);
    }



    //  public function updateStore(Request $request): JsonResponse
    // {
    //     try {
    //             $id = $request->device_uuid;
    //             $token = $request->token;
    //             $user = auth()->user();
    //             $user_id = $user->id;

    //             $deviceToken = DeviceToken::where('uuid', $id)->first();

    //             if ($deviceToken) {

    //                 if($deviceToken->token == $token) {

    //                     $deviceToken->update([
    //                         'user_id'      => $user->id,
    //                         'tenant_id'    => $user->tenant_id, // Recommended for your SaaS logic
    //                         'last_used_at' => now(),
    //                     ]);
    //                 }
    //                 else {
    //                     $deviceToken->update([
    //                         'token'        => $token,
    //                         'user_id'      => $user->id,
    //                         'tenant_id'    => $user->tenant_id, // Recommended for your SaaS logic
    //                         'last_used_at' => now(),
    //                     ]);
    //                 }
                    
    //                 return response()->json([
    //                     'success' => true,
    //                     'message' => 'Device successfully linked to user',
    //                     'data'    => $deviceToken
    //                 ]);
    //             }


    //     }
    //     catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to update record',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    /**
     * Update a device token.
     */

    // public function updateStore(Request $request): JsonResponse
    // {
    //     // 1. Strict Validation: Prevent empty or null tokens
    //     $validator = Validator::make($request->all(), [
    //         'device_uuid' => 'required|string|exists:device_tokens,uuid',
    //         'token'       => 'required|string|min:10', // FCM tokens are long; min:10 prevents empty strings
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    //     }

    //     try {
    //         $uuid = $request->device_uuid;
    //         $newToken = $request->token;
    //         $user = auth()->user();

    //         if (!$user) {
    //             return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    //         }

    //         $deviceToken = DeviceToken::where('uuid', $uuid)->first();

    //         // 2. The "Redundant check" + "Token Change" Logic
    //         // We only proceed if: 
    //         // a) The user_id is different (linking for the first time)
    //         // b) The token has changed (FCM rotated)
    //         // c) It's been a long time since the last sync (e.g., > 24 hours)
            
    //         $isUserDifferent = $deviceToken->user_id !== $user->id;
    //         $isTokenDifferent = $deviceToken->token !== $newToken;
    //         $isTimeForSync = $deviceToken->last_used_at < now()->subDay();

    //         if ($isUserDifferent || $isTokenDifferent || $isTimeForSync) {
    //             $deviceToken->update([
    //                 'token'        => $newToken, // Won't be empty due to validation
    //                 'user_id'      => $user->id,
    //                 'tenant_id'    => $user->tenant_id,
    //                 'last_used_at' => now(),
    //             ]);

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Device record synchronized',
    //                 'synced'  => true
    //             ]);
    //         }

    //         // 3. If everything was already correct, just return success without hitting the DB
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Device already up to date',
    //             'synced'  => false
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    //     }
    // }

    // /**
    //  * Register a device token.
    //  */
    // public function store(Request $request): JsonResponse
    // {
        
    //     $validator = Validator::make($request->all(), [
    //         'token' => 'required|string|max:500',
    //         'platform' => 'required|in:ios,android,web',
    //         'device_name' => 'nullable|string|max:255',
    //         'device_info' => 'nullable|array',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     try {
           
    //         // Create new token
    //         $deviceToken = DeviceToken::create([
    //             // 'user_id' => $user->id,
    //             'uuid' => (string) Str::uuid(),
    //             'token' => $request->token,
    //             'platform' => $request->platform,
    //             'device_name' => $request->device_name,
    //             'device_info' => $request->device_info,
    //             'last_used_at' => now(),
    //         ]);
            

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Device token registered successfully',
    //             'device_uuid' => $deviceToken->uuid,
    //         ], 201);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to register device token',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    // public function store(Request $request): JsonResponse
    // {
        
    //     $validator = Validator::make($request->all(), [
    //         'token' => 'required|string|max:500',
    //         'platform' => 'required|in:ios,android,web',
    //         'device_name' => 'nullable|string|max:255',
    //         'device_info' => 'nullable|array',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     try {
    //         $user = auth()->user();

            

    //         // Check if token already exists for this user
    //         if($user != null) {

    //         }
    //         $deviceToken = DeviceToken::where('token', $request->token)
    //             ->where('user_id', $user->id)
    //             ->first();

    //         if ($deviceToken) {
    //             // Update existing token
    //             $deviceToken->update([
    //                 'platform' => $request->platform,
    //                 'device_name' => $request->device_name,
    //                 'device_info' => $request->device_info,
    //                 'last_used_at' => now(),
    //             ]);
    //         } else {
    //             // Create new token
    //             $deviceToken = DeviceToken::create([
    //                 'user_id' => $user->id,
    //                 'token' => $request->token,
    //                 'platform' => $request->platform,
    //                 'device_name' => $request->device_name,
    //                 'device_info' => $request->device_info,
    //                 'last_used_at' => now(),
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Device token registered successfully',
    //             'data' => $deviceToken,
    //         ], 201);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to register device token',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    /**
     * Get all device tokens for the authenticated user.
     */
    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            $tokens = DeviceToken::forUser($user->id)
                ->orderBy('last_used_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tokens,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve device tokens',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a specific device token.
     */
    public function destroy(string $token): JsonResponse
    {
        try {
            $user = auth()->user();

            $deviceToken = DeviceToken::where('token', $token)
                ->where('user_id', $user->id)
                ->first();

            if (!$deviceToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device token not found',
                ], 404);
            }

            $deviceToken->delete();

            return response()->json([
                'success' => true,
                'message' => 'Device token removed successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove device token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete all device tokens for the authenticated user.
     */
    public function destroyAll(): JsonResponse
    {
        try {
            $user = auth()->user();
            $deleted = DeviceToken::where('user_id', $user->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'All device tokens removed successfully',
                'deleted_count' => $deleted,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove device tokens',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}