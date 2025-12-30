<?php

namespace App\Http\Controllers\AreaSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequests\SubRegionRequest;
use App\Services\SubRegionService;
use App\Services\RegionService;
use App\Services\LanguageService;
use App\Http\Resources\RegionResource;
use Illuminate\Http\Request;

class SubRegionController extends Controller
{
    protected $subregionService;
    protected $regionService;
    protected $languageService;

    public function __construct(
        SubRegionService $subregionService,
        RegionService $regionService,
        LanguageService $languageService
    ) {
        $this->subregionService = $subregionService;
        $this->regionService = $regionService;
        $this->languageService = $languageService;
        
        $this->middleware('can:subregions.view')->only(['index', 'show']);
        $this->middleware('can:subregions.create')->only(['create', 'store']);
        $this->middleware('can:subregions.edit')->only(['edit', 'update']);
        $this->middleware('can:subregions.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $regionId = $request->get('region_id');
        $active = $request->get('active');
        $dateFrom = $request->get('created_date_from');
        $dateTo = $request->get('created_date_to');

        // Prepare filters array
        $filters = [
            'search' => $search,
            'region_id' => $regionId,
            'active' => $active,
            'created_date_from' => $dateFrom,
            'created_date_to' => $dateTo,
        ];

        // Get subregions with filters and pagination
        $subregions = $this->subregionService->getAllSubRegions($filters, 10);
        
        // Get languages for table headers
        $languages = $this->languageService->getAll();

        // Get all regions for filter dropdown
        $regions = RegionResource::collection($this->regionService->getAllRegions())->resolve();

        return view('pages.areas.subregion.index', compact(
            'subregions',
            'regions',
            'languages',
            'search',
            'regionId',
            'active',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $regions = RegionResource::collection($this->regionService->getAllRegions())->resolve();
        try {
            $languages = $this->languageService->getAll();
            $selectedRegionId = $request->get('region_id');
            
            return view('pages.areas.subregion.form', compact('languages', 'regions', 'selectedRegionId'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.subregions.index')
                ->with('error', __('Error loading form'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubRegionRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->subregionService->createSubRegion($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('SubRegion created successfully'),
                    'redirect' => route('admin.area-settings.subregions.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.subregions.index')
                ->with('success', __('SubRegion created successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error creating subregion: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error creating subregion: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $subregion = $this->subregionService->getSubRegionById($id);
            return view('pages.areas.subregion.view', compact('subregion'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.subregions.index')
                ->with('error', __('SubRegion not found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $regions = RegionResource::collection($this->regionService->getAllRegions())->resolve();
            $subregion = $this->subregionService->getSubRegionById($id);
            return view('pages.areas.subregion.form', compact('subregion', 'languages', 'regions'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.subregions.index')
                ->with('error', __('SubRegion not found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubRegionRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $this->subregionService->updateSubRegion($id, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('SubRegion updated successfully'),
                    'redirect' => route('admin.area-settings.subregions.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.subregions.index')
                ->with('success', __('SubRegion updated successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error updating subregion: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating subregion: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $this->subregionService->deleteSubRegion($id);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('SubRegion deleted successfully'),
                    'redirect' => route('admin.area-settings.subregions.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.subregions.index')
                ->with('success', __('SubRegion deleted successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting subregion: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.area-settings.subregions.index')
                ->with('error', __('Error deleting subregion: ') . $e->getMessage());
        }
    }
}
