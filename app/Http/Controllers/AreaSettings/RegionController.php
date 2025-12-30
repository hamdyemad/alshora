<?php

namespace App\Http\Controllers\AreaSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequests\RegionRequest;
use App\Services\RegionService;
use App\Services\CityService;
use App\Services\LanguageService;
use App\Http\Resources\CityResource;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    protected $regionService;
    protected $cityService;
    protected $languageService;

    public function __construct(
        RegionService $regionService,
        CityService $cityService,
        LanguageService $languageService
    ) {
        $this->regionService = $regionService;
        $this->cityService = $cityService;
        $this->languageService = $languageService;
        
        $this->middleware('can:regions.view')->only(['index', 'show']);
        $this->middleware('can:regions.create')->only(['create', 'store']);
        $this->middleware('can:regions.edit')->only(['edit', 'update']);
        $this->middleware('can:regions.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $cityId = $request->get('city_id');
        $active = $request->get('active');
        $dateFrom = $request->get('created_date_from');
        $dateTo = $request->get('created_date_to');

        // Prepare filters array
        $filters = [
            'search' => $search,
            'city_id' => $cityId,
            'active' => $active,
            'created_date_from' => $dateFrom,
            'created_date_to' => $dateTo,
        ];

        // Get regions with filters and pagination
        $regions = $this->regionService->getAllRegions($filters, 10);
        
        // Get languages for table headers
        $languages = $this->languageService->getAll();

        // Get all cities for filter dropdown
        $cities = CityResource::collection($this->cityService->getActiveCities())->resolve();

        return view('pages.areas.region.index', compact(
            'regions',
            'cities',
            'languages',
            'search',
            'cityId',
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
        $cities = CityResource::collection($this->cityService->getActiveCities())->resolve();
        try {
            $languages = $this->languageService->getAll();
            $selectedCityId = $request->get('city_id');
            
            return view('pages.areas.region.form', compact('languages', 'cities', 'selectedCityId'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.regions.index')
                ->with('error', __('Error loading form'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegionRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->regionService->createRegion($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Region created successfully'),
                    'redirect' => route('admin.area-settings.regions.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.regions.index')
                ->with('success', __('Region created successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error creating region: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error creating region: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $region = $this->regionService->getRegionById($id);
            return view('pages.areas.region.view', compact('region'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.regions.index')
                ->with('error', __('Region not found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $cities = CityResource::collection($this->cityService->getActiveCities())->resolve();
            $region = $this->regionService->getRegionById($id);
            return view('pages.areas.region.form', compact('region', 'languages', 'cities'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.regions.index')
                ->with('error', __('Region not found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegionRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $this->regionService->updateRegion($id, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Region updated successfully'),
                    'redirect' => route('admin.area-settings.regions.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.regions.index')
                ->with('success', __('Region updated successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error updating region: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating region: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $this->regionService->deleteRegion($id);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Region deleted successfully'),
                    'redirect' => route('admin.area-settings.regions.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.regions.index')
                ->with('success', __('Region deleted successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting region: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.area-settings.regions.index')
                ->with('error', __('Error deleting region: ') . $e->getMessage());
        }
    }
}
