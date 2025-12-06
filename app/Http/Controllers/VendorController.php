<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vendor\StoreVendorRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ActivityResource;
use App\Services\VendorService;
use App\Services\CountryService;
use App\Services\ActivityService;
use App\Services\LanguageService;
use Illuminate\Support\Facades\DB;
use Exception;

class VendorController extends Controller {

    public function __construct(
        protected VendorService $vendorService,
        protected CountryService $countryService,
        protected ActivityService $activityService,
        protected LanguageService $languageService
    ) {}

    public function index() {
        return view('pages.vendors.index');
    }

    public function create() {
        // Get all countries and activities for select dropdowns
        $countriesData = $this->countryService->getAllCountries([], 1000);
        $activitiesData = $this->activityService->getAllActivities([], 1000);
        
        // Extract items from paginated results and transform to arrays
        $countries = CountryResource::collection($countriesData)->resolve();
        $activities = ActivityResource::collection($activitiesData)->resolve();
        
        // Get languages for translations
        $languages = $this->languageService->getAll();
        
        return view('pages.vendors.form', compact('countries', 'activities', 'languages'));
    }

    public function store(StoreVendorRequest $request)
    {
        try {
            $vendor = $this->vendorService->createVendor($request->validated());

            // Check if it's an AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Vendor created successfully!'),
                    'redirect' => route('admin.vendors.index'),
                    'vendor' => $vendor
                ]);
            }

            return redirect()
                ->route('admin.vendors.index')
                ->with('success', __('Vendor created successfully!'));
        } catch (Exception $e) {
            // Check if it's an AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to create vendor. Please try again.'),
                    'error_details' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('Failed to create vendor. Please try again.'))
                ->with('error_details', $e->getMessage());
        }
    }

}
