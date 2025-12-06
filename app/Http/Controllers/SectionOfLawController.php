<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionOfLawRequest;
use App\Services\SectionOfLawService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class SectionOfLawController extends Controller
{
    public function __construct(
        protected SectionOfLawService $sectionOfLawService,
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

        // Get sections of laws with filters and pagination
        $sectionsOfLaws = $this->sectionOfLawService->getAll($filters, 10);
        
        // Get languages for table headers
        $languages = $this->languageService->getAll();
        
        return view('pages.sections_of_laws.index', compact('sectionsOfLaws', 'search', 'active', 'dateFrom', 'dateTo', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        return view('pages.sections_of_laws.form', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionOfLawRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->sectionOfLawService->createSectionOfLaw($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('sections_of_laws.created_successfully'),
                    'redirect' => route('admin.sections-of-laws.index')
                ]);
            }
            
            return redirect()->route('admin.sections-of-laws.index')
                ->with('success', __('sections_of_laws.created_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('sections_of_laws.error_creating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('sections_of_laws.error_creating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $sectionOfLaw = $this->sectionOfLawService->getSectionOfLawById($id);
            $languages = $this->languageService->getAll();
            return view('pages.sections_of_laws.view', compact('sectionOfLaw', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.sections-of-laws.index')
                ->with('error', __('sections_of_laws.not_found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $sectionOfLaw = $this->sectionOfLawService->getSectionOfLawById($id);
            return view('pages.sections_of_laws.form', compact('sectionOfLaw', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.sections-of-laws.index')
                ->with('error', __('sections_of_laws.not_found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionOfLawRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $sectionOfLaw = $this->sectionOfLawService->getSectionOfLawById($id);
            $this->sectionOfLawService->updateSectionOfLaw($sectionOfLaw, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('sections_of_laws.updated_successfully'),
                    'redirect' => route('admin.sections-of-laws.index')
                ]);
            }
            
            return redirect()->route('admin.sections-of-laws.index')
                ->with('success', __('sections_of_laws.updated_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('sections_of_laws.error_updating') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('sections_of_laws.error_updating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $sectionOfLaw = $this->sectionOfLawService->getSectionOfLawById($id);
            $this->sectionOfLawService->deleteSectionOfLaw($sectionOfLaw);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('sections_of_laws.deleted_successfully'),
                    'redirect' => route('admin.sections-of-laws.index')
                ]);
            }
            
            return redirect()->route('admin.sections-of-laws.index')
                ->with('success', __('sections_of_laws.deleted_successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('sections_of_laws.error_deleting') . ': ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.sections-of-laws.index')
                ->with('error', __('sections_of_laws.error_deleting') . ': ' . $e->getMessage());
        }
    }
}
