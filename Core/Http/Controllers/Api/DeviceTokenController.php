<?php

namespace Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Core\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceTokenController extends Controller
{
     public function store(Request $request): JsonResponse
    {
         return response()->json([
        'status' => 'success3',
        'user' => auth()->user(),
        'message' => 'Authenticated!'
    ]);
    }
    /**
     * Register or update a device token.
     */
    // public function store(Request $request): JsonResponse
    // {
    //     // dd($request);
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