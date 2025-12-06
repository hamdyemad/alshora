<?php

namespace App\Http\Controllers\AreaSettings;

use App\Http\Controllers\Controller;
use App\Services\CityService;
use App\Services\CountryService;
use App\Http\Requests\AreaRequests\CityRequest;
use App\Services\LanguageService;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function __construct(
        protected CityService $cityService, 
        protected CountryService $countryService,
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
        $countryId = $request->get('country_id');
        $active = $request->get('active');
        $dateFrom = $request->get('created_date_from');
        $dateTo = $request->get('created_date_to');

        // Prepare filters array
        $filters = [
            'search' => $search,
            'country_id' => $countryId,
            'active' => $active,
            'created_date_from' => $dateFrom,
            'created_date_to' => $dateTo,
        ];

        // Get cities with filters and pagination
        $cities = $this->cityService->getAllCities($filters, 10);
        
        // Get languages for table headers
        $languages = $this->languageService->getAll();
        
        // Get countries for filter dropdown
        $countries = $this->countryService->getAll();
        
        return view('pages.areas.city.index', compact('cities', 'search', 'countryId', 'active', 'dateFrom', 'dateTo', 'languages', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $languages = $this->languageService->getAll();
        $countries = CountryResource::collection($this->countryService->getAll())->resolve();
        // Get country_id from query parameter if passed
        $selectedCountryId = $request->get('country_id');
        
        return view('pages.areas.city.form', compact('languages', 'countries', 'selectedCountryId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->cityService->createCity($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('City created successfully'),
                    'redirect' => route('admin.area-settings.cities.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.cities.index')
                ->with('success', __('City created successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error creating city: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error creating city: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $city = $this->cityService->getCityById($id);
            return view('pages.areas.city.view', compact('city'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.cities.index')
                ->with('error', __('City not found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $countries = CountryResource::collection($this->countryService->getAll())->resolve();
            $city = $this->cityService->getCityById($id);
            return view('pages.areas.city.form', compact('city', 'languages', 'countries'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.cities.index')
                ->with('error', __('City not found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, string $id)
    {
        $validated = $request->validated();
        
        \Log::info('Validated data', ['validated' => $validated]);

        try {
            $this->cityService->updateCity($id, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('City updated successfully'),
                    'redirect' => route('admin.area-settings.cities.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.cities.index')
                ->with('success', __('City updated successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error updating city: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating city: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $this->cityService->deleteCity($id);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('City deleted successfully'),
                    'redirect' => route('admin.area-settings.cities.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.cities.index')
                ->with('success', __('City deleted successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting city: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.area-settings.cities.index')
                ->with('error', __('Error deleting city: ') . $e->getMessage());
        }
    }
}
