<?php

namespace App\Http\Controllers\AreaSettings;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Http\Requests\AreaRequests\CountryRequest;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    public function __construct(protected CountryService $countryService, protected LanguageService $languageService)
    {
        $this->middleware('can:countries.view')->only(['index', 'show']);
        $this->middleware('can:countries.create')->only(['create', 'store']);
        $this->middleware('can:countries.edit')->only(['edit', 'update']);
        $this->middleware('can:countries.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $active = $request->get('active');
        $default = $request->get('default');
        $dateFrom = $request->get('created_date_from');
        $dateTo = $request->get('created_date_to');

        // Prepare filters array
        $filters = [
            'search' => $search,
            'active' => $active,
            'default' => $default,
            'created_date_from' => $dateFrom,
            'created_date_to' => $dateTo,
        ];

        // Get countries with filters and pagination
        $countries = $this->countryService->getAllCountries($filters, 10);
        // Get languages for table headers
        $languages = $this->languageService->getAll();
        
        return view('pages.areas.country.index', compact('countries', 'search', 'active', 'dateFrom', 'dateTo', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        return view('pages.areas.country.form', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->countryService->createCountry($validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Country created successfully'),
                    'redirect' => route('admin.area-settings.countries.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.countries.index')
                ->with('success', __('Country created successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error creating country: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error creating country: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $country = $this->countryService->getCountryById($id);
            return view('pages.areas.country.view', compact('country'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.countries.index')
                ->with('error', __('Country not found'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $languages = $this->languageService->getAll();
            $country = $this->countryService->getCountryById($id);
            return view('pages.areas.country.form', compact('country', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.area-settings.countries.index')
                ->with('error', __('Country not found'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $this->countryService->updateCountry($id, $validated);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Country updated successfully'),
                    'redirect' => route('admin.area-settings.countries.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.countries.index')
                ->with('success', __('Country updated successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error updating country: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating country: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $this->countryService->deleteCountry($id);
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Country deleted successfully'),
                    'redirect' => route('admin.area-settings.countries.index')
                ]);
            }
            
            return redirect()->route('admin.area-settings.countries.index')
                ->with('success', __('Country deleted successfully'));
        } catch (\Exception $e) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting country: ') . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.area-settings.countries.index')
                ->with('error', __('Error deleting country: ') . $e->getMessage());
        }
    }
}
