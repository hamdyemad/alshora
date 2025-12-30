<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Services\SubscriptionService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected LanguageService $languageService
    ) {
        $this->middleware('can:subscriptions.view')->only(['index', 'show']);
        $this->middleware('can:subscriptions.create')->only(['create', 'store']);
        $this->middleware('can:subscriptions.edit')->only(['edit', 'update']);
        $this->middleware('can:subscriptions.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $active = $request->get('active');
        $dateFrom = $request->get('created_date_from');
        $dateTo = $request->get('created_date_to');

        // Prepare filters array
        $filters = [
            'search' => $search,
            'active' => $active,
            'created_date_from' => $dateFrom,
            'created_date_to' => $dateTo,
        ];

        // Get subscriptions with filters and pagination
        $subscriptions = $this->subscriptionService->getAllSubscriptions($filters, 10);
        
        // Get languages for table headers
        $languages = $this->languageService->getAll();
        
        return view('pages.subscriptions.index', compact('subscriptions', 'search', 'active', 'dateFrom', 'dateTo', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        return view('pages.subscriptions.form', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubscriptionRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->subscriptionService->createSubscription($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('subscription.created_successfully'),
                    'redirect' => route('admin.subscriptions.index')
                ]);
            }
            
            return redirect()->route('admin.subscriptions.index')
                ->with('success', __('subscription.created_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('subscription.error_creating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('subscription.error_creating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $subscription = $this->subscriptionService->getSubscriptionById($id);
            return view('pages.subscriptions.view', compact('subscription'));
        } catch (\Exception $e) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', __('subscription.not_found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $subscription = $this->subscriptionService->getSubscriptionById($id);
            return view('pages.subscriptions.form', compact('subscription', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', __('subscription.not_found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubscriptionRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $this->subscriptionService->updateSubscription($id, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('subscription.updated_successfully'),
                    'redirect' => route('admin.subscriptions.index')
                ]);
            }
            
            return redirect()->route('admin.subscriptions.index')
                ->with('success', __('subscription.updated_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('subscription.error_updating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('subscription.error_updating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $this->subscriptionService->deleteSubscription($id);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('subscription.deleted_successfully'),
                    'redirect' => route('admin.subscriptions.index')
                ]);
            }
            
            return redirect()->route('admin.subscriptions.index')
                ->with('success', __('subscription.deleted_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('subscription.error_deleting') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.subscriptions.index')
                ->with('error', __('subscription.error_deleting') . ': ' . $e->getMessage());
        }
    }
}
