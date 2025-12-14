<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FirebaseNotificationService
{
    private $projectId;
    private $serviceAccountPath;
    private $accessToken;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
        $this->serviceAccountPath = config('services.firebase.service_account_path');
    }

    /**
     * Get OAuth 2.0 access token for Firebase
     */
    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        if (!file_exists($this->serviceAccountPath)) {
            Log::error('Firebase service account file not found: ' . $this->serviceAccountPath);
            return null;
        }

        try {
            $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);

            $now = time();
            $payload = [
                'iss' => $serviceAccount['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600
            ];

            $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
            $payload = json_encode($payload);

            $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

            $signature = '';
            openssl_sign($base64Header . '.' . $base64Payload, $signature, $serviceAccount['private_key'], 'SHA256');
            $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

            $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
                return $this->accessToken;
            }

            Log::error('Failed to get Firebase access token', $response->json());
            return null;
        } catch (\Exception $e) {
            Log::error('Error getting Firebase access token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send notification to a single device
     */
    public function sendToDevice($fcmToken, $title, $body, $data = [])
    {

        if (!$fcmToken || !$this->projectId) {
            Log::warning('FCM token or project ID missing', [
                'has_token' => !empty($fcmToken),
                'has_project_id' => !empty($this->projectId)
            ]);
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error('Failed to get access token');
            return false;
        }

        Log::info('Got access token successfully');

        // Convert all data values to strings for Firebase
        $stringData = [];
        foreach ($data as $key => $value) {
            $stringData[$key] = (string)$value;
        }

        $message = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $stringData,
                'android' => [
                    'priority' => 'high'
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1
                        ]
                    ]
                ]
            ]
        ];

        Log::info('Sending Firebase message', ['message' => $message]);

        return $this->sendNotificationV1($message, $accessToken);
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToMultipleDevices($fcmTokens, $title, $body, $data = [])
    {
        if (empty($fcmTokens) || !$this->projectId) {
            Log::warning('FCM tokens or project ID missing');
            return false;
        }

        $results = [];
        foreach ($fcmTokens as $token) {
            $results[] = $this->sendToDevice($token, $title, $body, $data);
        }

        return $results;
    }

    /**
     * Send notification to a topic
     */
    public function sendToTopic($topic, $title, $body, $data = [])
    {
        if (!$topic || !$this->projectId) {
            Log::warning('Topic or project ID missing');
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $message = [
            'message' => [
                'topic' => $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $data,
                'android' => [
                    'priority' => 'high'
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1
                        ]
                    ]
                ]
            ]
        ];

        return $this->sendNotificationV1($message, $accessToken);
    }

    /**
     * Send the actual notification via HTTP v1 API
     */
    private function sendNotificationV1($message, $accessToken)
    {
        try {
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $message);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('FCM notification sent successfully', $result);
                return $result;
            } else {
                Log::error('FCM notification failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('FCM notification exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send appointment notification to lawyer
     */
    public function sendAppointmentNotificationToLawyer($lawyer, $appointment, $customer)
    {
        Log::info('Starting Firebase notification to lawyer', [
            'lawyer_id' => $lawyer->id,
            'appointment_id' => $appointment->id,
            'has_fcm_token' => !empty($lawyer->user->fcm_token),
            'project_id' => $this->projectId,
            'service_account_exists' => file_exists($this->serviceAccountPath)
        ]);

        if (!$lawyer->user->fcm_token) {
            Log::warning('Lawyer does not have FCM token', ['lawyer_id' => $lawyer->id]);
            return false;
        }

        if (!$this->projectId) {
            Log::error('Firebase project ID not configured');
            return false;
        }

        if (!file_exists($this->serviceAccountPath)) {
            Log::error('Firebase service account file not found', ['path' => $this->serviceAccountPath]);
            return false;
        }

        $title = trans('notification.new_appointment_title');
        $body = trans('notification.new_appointment_body', [
            'customer' => $customer->name,
            'date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y'),
            'time' => \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A')
        ]);

        Log::info('Firebase notification content', [
            'title' => $title,
            'body' => $body,
            'fcm_token' => substr($lawyer->user->fcm_token, 0, 20) . '...'
        ]);

        $data = [
            'type' => 'new_appointment',
            'appointment_id' => (string)$appointment->id,
            'customer_id' => (string)$customer->id,
            'lawyer_id' => (string)$lawyer->id,
            'appointment_date' => $appointment->appointment_date,
            'time_slot' => $appointment->time_slot
        ];

        return $this->sendToDevice($lawyer->user->fcm_token, $title, $body, $data);
    }

    /**
     * Send appointment status update notification
     */
    public function sendAppointmentStatusUpdate($user, $appointment, $status)
    {
        if (!$user->fcm_token) {
            Log::info('User does not have FCM token', ['user_id' => $user->id]);
            return false;
        }

        $title = trans('notification.appointment_status_title');
        $body = trans('notification.appointment_status_body', [
            'status' => trans('reservation.' . $status),
            'date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y')
        ]);

        $data = [
            'type' => 'appointment_status_update',
            'appointment_id' => $appointment->id,
            'status' => $status,
            'appointment_date' => $appointment->appointment_date
        ];

        return $this->sendToDevice($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send hosting slot reservation approved notification
     */
    public function sendHostingSlotApprovedNotification($lawyer, $reservation)
    {
        if (!$lawyer->user->fcm_token) {
            Log::warning('Lawyer does not have FCM token', ['lawyer_id' => $lawyer->id]);
            return false;
        }

        $title = trans('notification.hosting_slot_approved_title');
        $body = trans('notification.hosting_slot_approved_body', [
            'day' => trans('hosting.' . strtolower($reservation->hostingTime->day)),
            'time' => $reservation->hostingTime->from_time . ' - ' . $reservation->hostingTime->to_time
        ]);

        $data = [
            'type' => 'hosting_slot_approved',
            'reservation_id' => (string)$reservation->id,
            'hosting_time_id' => (string)$reservation->hosting_time_id,
            'day' => $reservation->hostingTime->day,
            'from_time' => $reservation->hostingTime->from_time,
            'to_time' => $reservation->hostingTime->to_time
        ];

        Log::info('Sending hosting slot approved notification', [
            'lawyer_id' => $lawyer->id,
            'reservation_id' => $reservation->id
        ]);

        return $this->sendToDevice($lawyer->user->fcm_token, $title, $body, $data);
    }

    /**
     * Send hosting slot reservation rejected notification
     */
    public function sendHostingSlotRejectedNotification($lawyer, $reservation)
    {
        if (!$lawyer->user->fcm_token) {
            Log::warning('Lawyer does not have FCM token', ['lawyer_id' => $lawyer->id]);
            return false;
        }

        $title = trans('notification.hosting_slot_rejected_title');
        $body = trans('notification.hosting_slot_rejected_body', [
            'day' => trans('hosting.' . strtolower($reservation->hostingTime->day)),
            'time' => $reservation->hostingTime->from_time . ' - ' . $reservation->hostingTime->to_time,
            'reason' => $reservation->admin_notes ?? trans('notification.no_reason_provided')
        ]);

        $data = [
            'type' => 'hosting_slot_rejected',
            'reservation_id' => (string)$reservation->id,
            'hosting_time_id' => (string)$reservation->hosting_time_id,
            'day' => $reservation->hostingTime->day,
            'from_time' => $reservation->hostingTime->from_time,
            'to_time' => $reservation->hostingTime->to_time,
            'reason' => $reservation->admin_notes ?? ''
        ];

        Log::info('Sending hosting slot rejected notification', [
            'lawyer_id' => $lawyer->id,
            'reservation_id' => $reservation->id
        ]);

        return $this->sendToDevice($lawyer->user->fcm_token, $title, $body, $data);
    }

    /**
     * Send appointment approved notification to customer
     */
    public function sendAppointmentApprovedNotification($customer, $appointment, $lawyer)
    {
        if (!$customer->fcm_token) {
            Log::warning('Customer does not have FCM token', ['customer_id' => $customer->id]);
            return false;
        }

        $title = trans('notification.appointment_approved_title');
        $body = trans('notification.appointment_approved_body', [
            'lawyer' => $lawyer->user->name ?? 'Lawyer',
            'date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y'),
            'time' => \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A')
        ]);

        $data = [
            'type' => 'appointment_approved',
            'appointment_id' => (string)$appointment->id,
            'lawyer_id' => (string)$lawyer->id,
            'lawyer_name' => $lawyer->user->name ?? '',
            'appointment_date' => $appointment->appointment_date,
            'time_slot' => $appointment->time_slot,
            'status' => 'approved'
        ];

        Log::info('Sending appointment approved notification to customer', [
            'customer_id' => $customer->id,
            'appointment_id' => $appointment->id
        ]);

        return $this->sendToDevice($customer->fcm_token, $title, $body, $data);
    }

    /**
     * Send appointment rejected notification to customer
     */
    public function sendAppointmentRejectedNotification($customer, $appointment, $lawyer, $reason)
    {
        if (!$customer->fcm_token) {
            Log::warning('Customer does not have FCM token', ['customer_id' => $customer->id]);
            return false;
        }

        $title = trans('notification.appointment_rejected_title');
        $body = trans('notification.appointment_rejected_body', [
            'lawyer' => $lawyer->user->name ?? 'Lawyer',
            'date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y'),
            'reason' => $reason
        ]);

        $data = [
            'type' => 'appointment_rejected',
            'appointment_id' => (string)$appointment->id,
            'lawyer_id' => (string)$lawyer->id,
            'lawyer_name' => $lawyer->user->name ?? '',
            'appointment_date' => $appointment->appointment_date,
            'time_slot' => $appointment->time_slot,
            'status' => 'rejected',
            'reason' => $reason
        ];

        Log::info('Sending appointment rejected notification to customer', [
            'customer_id' => $customer->id,
            'appointment_id' => $appointment->id
        ]);

        return $this->sendToDevice($customer->fcm_token, $title, $body, $data);
    }
}
