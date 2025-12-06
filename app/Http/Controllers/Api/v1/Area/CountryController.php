<?php

namespace App\Http\Controllers\Api\v1\Area;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Services\CityService;
use App\Services\CountryService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    use Res;


    public function __construct(protected CountryService $countryService)
    {
    }

    /**
     * Register a new lawyer
     */
    public function index(Request $request)
    {
        try {
            $countryService = $this->countryService->getAllCountries($request->all(), 0);
            $countryService = CountryResource::collection($countryService);
            return $this->sendRes(__('validation.success'), true, $countryService);
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Login lawyer
     */
    public function login(Request $request): JsonResponse
    {
        // TODO: Implement login logic
        return $this->sendRes('Login endpoint - to be implemented', false, [], [], 501);
    }
}
