<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Traits\Res;
use Illuminate\Http\Request;

class SupportMessageController extends Controller
{
    use Res;

    /**
     * Store a new support message
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            $supportMessage = SupportMessage::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            return $this->sendRes(
                __('support.message_sent_successfully'),
                true,
                [
                    'id' => $supportMessage->id,
                    'title' => $supportMessage->title,
                    'message' => $supportMessage->message,
                    'status' => $supportMessage->status,
                    'created_at' => $supportMessage->created_at->format('Y-m-d H:i:s'),
                ],
                [],
                201
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get user's support messages
     */
    public function myMessages(Request $request)
    {
        try {
            $messages = SupportMessage::where('user_id', auth()->id())
                ->latest()
                ->paginate($request->input('per_page', 15));

            return $this->sendRes(
                __('validation.success'),
                true,
                [
                    'data' => $messages->items(),
                    'pagination' => [
                        'current_page' => $messages->currentPage(),
                        'last_page' => $messages->lastPage(),
                        'per_page' => $messages->perPage(),
                        'total' => $messages->total(),
                    ]
                ]
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
