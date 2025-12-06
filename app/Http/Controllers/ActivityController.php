<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use App\Http\Requests\ActivityRequest;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    public function __construct(protected ActivityService $activityService, protected LanguageService $languageService)
    {
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

        // Get activities with filters and pagination
        $activities = $this->activityService->getAllActivities($filters, 10);
        // Get languages for table headers
        $languages = $this->languageService->getAll();
        
        return view('pages.activities.index', compact('activities', 'search', 'active', 'dateFrom', 'dateTo', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        return view('pages.activities.form', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->activityService->createActivity($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Activity created successfully'),
                    'redirect' => route('admin.activities.index')
                ]);
            }
            
            return redirect()->route('admin.activities.index')
                ->with('success', __('Activity created successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error creating activity: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error creating activity: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $activity = $this->activityService->getActivityById($id);
            return view('pages.activities.view', compact('activity'));
        } catch (\Exception $e) {
            return redirect()->route('admin.activities.index')
                ->with('error', __('Activity not found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $activity = $this->activityService->getActivityById($id);
            return view('pages.activities.form', compact('activity', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.activities.index')
                ->with('error', __('Activity not found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActivityRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $this->activityService->updateActivity($id, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Activity updated successfully'),
                    'redirect' => route('admin.activities.index')
                ]);
            }
            
            return redirect()->route('admin.activities.index')
                ->with('success', __('Activity updated successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error updating activity: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating activity: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $this->activityService->deleteActivity($id);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Activity deleted successfully'),
                    'redirect' => route('admin.activities.index')
                ]);
            }
            
            return redirect()->route('admin.activities.index')
                ->with('success', __('Activity deleted successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting activity: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.activities.index')
                ->with('error', __('Error deleting activity: ') . $e->getMessage());
        }
    }
}
