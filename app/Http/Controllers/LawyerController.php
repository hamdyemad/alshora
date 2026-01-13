<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LawyerService;
use App\Http\Requests\LawyerRequest;
use App\Http\Resources\RegisterGradeResource;
use App\Services\LanguageService;
use App\Services\RegisterGradeService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOfficeHoursRequest;
use Illuminate\Support\Facades\Log;

class LawyerController extends Controller
{
    public function __construct(
        protected LawyerService $lawyerService,
        protected LanguageService $languageService,
        protected RegisterGradeService $registerGradeService
    ) {
        $this->middleware('can:lawyers.view')->only(['index', 'show']);
        $this->middleware('can:lawyers.create')->only(['create', 'store']);
        $this->middleware('can:lawyers.edit')->only(['edit', 'update', 'updateOfficeHours', 'toggleAds', 'toggleBlock', 'renewSubscription', 'updateSpecializations', 'toggleFeatured']);
        $this->middleware('can:lawyers.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $registerGrades = $this->registerGradeService->getAll();
        $registerGrades = RegisterGradeResource::collection($registerGrades)->resolve();
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

        // Get lawyers with filters and pagination
        $lawyers = $this->lawyerService->getAllLawyers($filters, 10);

        // Get languages for table headers
        $languages = $this->languageService->getAll();

        return view('pages.lawyers.index', compact('lawyers', 'search', 'active', 'dateFrom', 'dateTo', 'languages', 'registerGrades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        $registerGrades = $this->registerGradeService->getAll();
        $registerGrades = RegisterGradeResource::collection($registerGrades)->resolve();
        $sectionsOfLaws = \App\Models\SectionOfLaw::where('active', 1)->get();
        return view('pages.lawyers.form', compact('languages', 'registerGrades', 'sectionsOfLaws'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LawyerRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->lawyerService->createLawyer($validated);

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Lawyer created successfully'),
                    'redirect' => route('admin.lawyers.index')
                ]);
            }

            return redirect()->route('admin.lawyers.index')
                ->with('success', __('Lawyer created successfully'));
        } catch (\Exception $e) {

            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error creating lawyer: ') . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', __('Error creating lawyer: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $lawyer = $this->lawyerService->getLawyerById($id);
            return view('pages.lawyers.view', compact('lawyer'));
        } catch (\Exception $e) {
            return redirect()->route('admin.lawyers.index')
                ->with('error', __('Lawyer not found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $registerGrades = $this->registerGradeService->getAll();
            $registerGrades = RegisterGradeResource::collection($registerGrades)->resolve();
            $languages = $this->languageService->getAll();
            $lawyer = $this->lawyerService->getLawyerById($id);
            $sectionsOfLaws = \App\Models\SectionOfLaw::where('active', 1)->get();
            return view('pages.lawyers.form', compact('lawyer', 'languages', 'registerGrades', 'sectionsOfLaws'));
        } catch (\Exception $e) {
            return redirect()->route('admin.lawyers.index')
                ->with('error', __('Lawyer not found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LawyerRequest $request, string $id)
    {
        $validated = $request->validated();
        $lawyer = $this->lawyerService->getLawyerById($id);

        try {
            $this->lawyerService->updateLawyer($lawyer, $validated);

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Lawyer updated successfully'),
                    'redirect' => route('admin.lawyers.index')
                ]);
            }

            return redirect()->route('admin.lawyers.index')
                ->with('success', __('Lawyer updated successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error updating lawyer: ') . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating lawyer: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $lawyer = $this->lawyerService->getLawyerById($id);
            $this->lawyerService->deleteLawyer($lawyer);

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Lawyer deleted successfully'),
                    'redirect' => route('admin.lawyers.index')
                ]);
            }

            return redirect()->route('admin.lawyers.index')
                ->with('success', __('Lawyer deleted successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting lawyer: ') . $e->getMessage()
                ], 422);
            }

            return redirect()->route('admin.lawyers.index')
                ->with('error', __('Error deleting lawyer: ') . $e->getMessage());
        }
    }

    /**
     * Update office hours for a lawyer via AJAX
     */
    public function updateOfficeHours(UpdateOfficeHoursRequest $request, string $id)
    {
        try {
            $lawyer = $this->lawyerService->getLawyerById($id);

            // Get validated office hours data (validation already handled by UpdateOfficeHoursRequest)
            $officeHoursData = $request->validated()['office_hours'];

            // Update office hours
            $this->lawyerService->updateOfficeHours($lawyer, $officeHoursData);

            return response()->json([
                'success' => true,
                'message' => __('lawyer.office_hours_updated_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lawyer.error_updating_office_hours') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Toggle ads enabled status
     */
    public function toggleAds(Request $request, string $id)
    {
        try {
            $lawyer = $this->lawyerService->getLawyerById($id);
            $lawyer->ads_enabled = !$lawyer->ads_enabled;
            $lawyer->save();

            return response()->json([
                'success' => true,
                'message' => $lawyer->ads_enabled
                    ? __('lawyer.ads_enabled_successfully')
                    : __('lawyer.ads_disabled_successfully'),
                'ads_enabled' => $lawyer->ads_enabled
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lawyer.error_toggling_ads') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Toggle blocked status
     */
    public function toggleBlock(Request $request, string $id)
    {
        try {
            $lawyer = $this->lawyerService->getLawyerById($id);
            $user = $lawyer->user;
            $user->is_blocked = !$user->is_blocked;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => $user->is_blocked
                    ? __('lawyer.blocked_successfully')
                    : __('lawyer.unblocked_successfully'),
                'is_blocked' => $user->is_blocked
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lawyer.error_toggling_block') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Renew lawyer subscription
     */
    public function renewSubscription(Request $request, string $id)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        try {
            $lawyer = $this->lawyerService->getLawyerById($id);
            $subscription = \App\Models\Subscription::findOrFail($request->subscription_id);

            // Calculate expiry date
            $startDate = $lawyer->subscription_expires_at && $lawyer->subscription_expires_at->isFuture()
                ? $lawyer->subscription_expires_at
                : now();

            $expiryDate = $startDate->copy()->addMonths($subscription->number_of_months);

            // Update lawyer subscription
            $lawyer->subscription_id = $subscription->id;
            $lawyer->subscription_expires_at = $expiryDate;
            $lawyer->save();

            return response()->json([
                'success' => true,
                'message' => __('lawyer.subscription_renewed_successfully'),
                'subscription' => $subscription->getTranslation('name', app()->getLocale()),
                'expires_at' => $expiryDate->format('Y-m-d')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lawyer.error_renewing_subscription') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update specializations (sections of laws) for a lawyer via AJAX
     */
    public function updateSpecializations(Request $request, string $id)
    {
        $request->validate([
            'sections_of_laws' => 'required|array|min:1',
            'sections_of_laws.*' => 'required|exists:sections_of_laws,id',
        ]);

        try {
            $lawyer = $this->lawyerService->getLawyerById($id);

            // Sync sections of laws
            $lawyer->sectionsOfLaws()->sync($request->sections_of_laws);

            return response()->json([
                'success' => true,
                'message' => __('lawyer.specializations_updated_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lawyer.error_updating_specializations') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Toggle featured status for a lawyer
     */
    public function toggleFeatured(Request $request, string $id)
    {
        try {
            $lawyer = $this->lawyerService->getLawyerById($id);
            $lawyer = $this->lawyerService->toggleFeatured($lawyer);

            return response()->json([
                'success' => true,
                'message' => $lawyer->is_featured 
                    ? __('lawyer.marked_as_featured') 
                    : __('lawyer.removed_from_featured'),
                'is_featured' => $lawyer->is_featured
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
