<?php

namespace Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Will return admin notifications
     * 
     * @return mixed
     */
//     public function adminNotifications()
// {
//     try {
//         // 1. Check if user is actually logged in
//         $user = auth()->user();
        
//         if (!$user) {
//             return response()->json([
//                 'success' => false, 
//                 'message' => 'Unauthenticated'
//             ], 401);
//         }

//         // 2. Get notifications
//         $notifications = $user->unreadNotifications;

//         // 3. Return the exact structure your JS expects
//         return response()->json([
//             'success' => true,
//             'notifications' => $notifications // Ensure this key matches your JS
//         ]);

//     } catch (\Exception $e) {
//         // This will log the actual error so you can see it in storage/logs/laravel.log
//         Log::error("Notification Error: " . $e->getMessage());
        
//         return response()->json([
//             'success' => false, 
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }
    public function adminNotifications()
    {
        try {
            // dd("Admin notifications method working!");

            $notifications = auth()->user()->unreadNotifications;

            // dd($notifications);

            $notifications = $notifications->map(function ($item) {
                return [
                    'id' => $item->id,
                    'message' => $item->data['message'],
                    'link' => $item->data['link'],
                    'time' => $item->created_at->diffForHumans()
                ];
            });

            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    /**
     * Will mark as read single notification
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminNotificationMarkAsRead(Request $request)
    {
        try {
            $notification = auth()->user()->unreadNotifications()->where('id', $request['id'])->first();

            if ($notification != null) {
                $notification->markAsRead();

                //Link for Admin
                if (auth()->user()->user_type != config('tlecommercecore.user_type.seller')) {
                    if (strpos($notification->data['link'], '/admin' . '/') !== false || strpos($notification->data['link'], getSaasPrefix()) !== false) {
                        $link = $notification->data['link'];
                    } else {
                        $link = '/' . getAdminPrefix() . $notification->data['link'];
                    }
                }

                //Link For Seller 
                if (auth()->user()->user_type == config('tlecommercecore.user_type.seller')) {
                    $link = $notification->data['link'];
                }

                $unread_notification = auth()->user()->unreadNotifications;
                $unread_notification = $unread_notification->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'message' => $item->data['message'],
                        'link' => $item->data['link'],
                        'time' => $item->created_at->diffForHumans()
                    ];
                });

                if (strpos($link, "/admin/admin") !== false || strpos($link, "/admin/user") !== false) {
                    // Replace the first occurrence of "/admin" with an empty string
                    $link = preg_replace('/\/admin/', '', $link, 1);
                }

                return response()->json(
                    [
                        'success' => true,
                        'link' => $link,
                        'unread_notification' => $unread_notification
                    ]
                );
            } else {
                return response()->json(
                    [
                        'success' => false
                    ]
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false
                ]
            );
        }
    }
    /**
     * Will mark as read all notifications
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminAllNotificationMarkAsRead(Request $request)
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json(
                [
                    'success' => true
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false
                ]
            );
        }
    }
}
