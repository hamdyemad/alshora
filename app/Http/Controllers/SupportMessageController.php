<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:support-messages.view')->only(['index', 'show']);
        $this->middleware('can:support-messages.edit')->only(['updateStatus']);
        $this->middleware('can:support-messages.delete')->only(['destroy']);
    }

    /**
     * Display a listing of support messages
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $messages = SupportMessage::with('user')
            ->filter($filters)
            ->latest()
            ->paginate(15);

        return view('pages.support-messages.index', compact('messages', 'filters'));
    }

    /**
     * Display the specified support message
     */
    public function show(string $id)
    {
        $message = SupportMessage::with('user')->findOrFail($id);

        // Mark as read if pending
        if ($message->status === 'pending') {
            $message->update(['status' => 'read']);
        }

        return view('pages.support-messages.show', compact('message'));
    }

    /**
     * Update message status
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,read,resolved',
        ]);

        $message = SupportMessage::findOrFail($id);
        $message->update(['status' => $request->status]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('support.status_updated_successfully'),
            ]);
        }

        return back()->with('success', __('support.status_updated_successfully'));
    }

    /**
     * Remove the specified support message
     */
    public function destroy(string $id)
    {
        $message = SupportMessage::findOrFail($id);
        $message->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('support.deleted_successfully'),
            ]);
        }

        return redirect()->route('admin.support-messages.index')
            ->with('success', __('support.deleted_successfully'));
    }
}
