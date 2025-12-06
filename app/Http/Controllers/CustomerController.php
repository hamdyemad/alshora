<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Services\CustomerService;
use App\Services\CountryService;
use App\Services\CityService;
use App\Services\RegionService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService,
        protected CountryService $countryService,
        protected CityService $cityService,
        protected RegionService $regionService,
        protected LanguageService $languageService
    ) {
    }

    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        // Get filters from request
        $filters = [
            'search' => $request->input('search'),
            'active' => $request->input('active'),
            'city_id' => $request->input('city_id'),
            'region_id' => $request->input('region_id'),
            'created_date_from' => $request->input('created_date_from'),
            'created_date_to' => $request->input('created_date_to'),
        ];

        // Get customers with filters and pagination
        $customers = $this->customerService->getAll($filters, 10);
        
        // Get additional data for filters
        $cities = $this->cityService->getAllCities(['active' => 1], 0);
        
        return view('pages.customers.index', compact('customers', 'cities', 'filters'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        $languages = $this->languageService->getAll();
        $countries = $this->countryService->getAll(['active' => 1], 0);
        $cities = $this->cityService->getAllCities(['active' => 1], 0);
        
        return view('pages.customers.form', compact('languages', 'countries', 'cities'));
    }

    /**
     * Store a newly created customer
     */
    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $customer = $this->customerService->createCustomer($data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('customer.created_successfully'),
                    'redirect' => route('admin.customers.index')
                ]);
            }

            return redirect()->route('admin.customers.index')
                           ->with('success', __('customer.created_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('customer.error_creating') . ': ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                        ->with('error', __('customer.error_creating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified customer
     */
    public function show(string $id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            return view('pages.customers.view', compact('customer'));
        } catch (\Exception $e) {
            return back()->with('error', __('customer.not_found'));
        }
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(string $id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            $languages = $this->languageService->getAll();
            $countries = $this->countryService->getAll(['active' => 1], 0);
            $cities = $this->cityService->getAllCities(['active' => 1], 0);
            
            return view('pages.customers.form', compact('customer', 'languages', 'countries', 'cities'));
        } catch (\Exception $e) {
            return back()->with('error', __('customer.not_found'));
        }
    }

    /**
     * Update the specified customer
     */
    public function update(CustomerRequest $request, string $id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            $data = $request->validated();
            $this->customerService->updateCustomer($customer, $data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('customer.updated_successfully'),
                    'redirect' => route('admin.customers.index')
                ]);
            }

            return redirect()->route('admin.customers.index')
                           ->with('success', __('customer.updated_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('customer.error_updating') . ': ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                        ->with('error', __('customer.error_updating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified customer
     */
    public function destroy(string $id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            $this->customerService->deleteCustomer($customer);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('customer.deleted_successfully')
                ]);
            }

            return redirect()->route('admin.customers.index')
                           ->with('success', __('customer.deleted_successfully'));
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('customer.error_deleting') . ': ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', __('customer.error_deleting') . ': ' . $e->getMessage());
        }
    }

    /**
     * Toggle customer active status
     */
    public function toggleActive(string $id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            $this->customerService->toggleActive($customer);

            return response()->json([
                'success' => true,
                'message' => __('customer.status_updated_successfully'),
                'active' => $customer->active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('customer.error_updating_status') . ': ' . $e->getMessage()
            ], 422);
        }
    }
}
