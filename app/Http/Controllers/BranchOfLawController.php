<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchOfLawRequest;
use App\Services\BranchOfLawService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class BranchOfLawController extends Controller
{
    public function __construct(
        protected BranchOfLawService $branchOfLawService,
        protected LanguageService $languageService
    ) {
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

        // Get branches of laws with filters and pagination
        $branchesOfLaws = $this->branchOfLawService->getAllBranchesOfLaws($filters, 10);
        
        // Get languages for table headers
        $languages = $this->languageService->getAll();
        
        return view('pages.branches_of_laws.index', compact('branchesOfLaws', 'search', 'active', 'dateFrom', 'dateTo', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        return view('pages.branches_of_laws.form', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchOfLawRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->branchOfLawService->createBranchOfLaw($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('branches_of_laws.created_successfully'),
                    'redirect' => route('admin.branches-of-laws.index')
                ]);
            }
            
            return redirect()->route('admin.branches-of-laws.index')
                ->with('success', __('branches_of_laws.created_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('branches_of_laws.error_creating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('branches_of_laws.error_creating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $branchOfLaw = $this->branchOfLawService->getBranchOfLawById($id);
            $languages = $this->languageService->getAll();
            return view('pages.branches_of_laws.view', compact('branchOfLaw', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.branches-of-laws.index')
                ->with('error', __('branches_of_laws.not_found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $branchOfLaw = $this->branchOfLawService->getBranchOfLawById($id);
            return view('pages.branches_of_laws.form', compact('branchOfLaw', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.branches-of-laws.index')
                ->with('error', __('branches_of_laws.not_found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchOfLawRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $branchOfLaw = $this->branchOfLawService->getBranchOfLawById($id);
            $this->branchOfLawService->updateBranchOfLaw($branchOfLaw, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('branches_of_laws.updated_successfully'),
                    'redirect' => route('admin.branches-of-laws.index')
                ]);
            }
            
            return redirect()->route('admin.branches-of-laws.index')
                ->with('success', __('branches_of_laws.updated_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('branches_of_laws.error_updating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('branches_of_laws.error_updating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $branchOfLaw = $this->branchOfLawService->getBranchOfLawById($id);
            $this->branchOfLawService->deleteBranchOfLaw($branchOfLaw);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('branches_of_laws.deleted_successfully'),
                    'redirect' => route('admin.branches-of-laws.index')
                ]);
            }
            
            return redirect()->route('admin.branches-of-laws.index')
                ->with('success', __('branches_of_laws.deleted_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('branches_of_laws.error_deleting') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.branches-of-laws.index')
                ->with('error', __('branches_of_laws.error_deleting') . ': ' . $e->getMessage());
        }
    }
}
