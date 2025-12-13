<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Update user's FCM token
     */
    public function updateFcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update FCM token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove user's FCM token (logout)
     */
    public function removeFcmToken(Request $request)
    {
        try {
            $user = Auth::user();
            $user->fcm_token = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'FCM token removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove FCM token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's notification settings
     */
    public function getNotificationSettings(Request $request)
    {
        try {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'data' => [
                    'fcm_token' => $user->fcm_token ? 'set' : 'not_set',
                    'notifications_enabled' => !empty($user->fcm_token)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get notification settings: ' . $e->getMessage()
            ], 500);
        }
    }
}
