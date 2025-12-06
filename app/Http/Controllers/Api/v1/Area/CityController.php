<?php

namespace App\Http\Controllers\Api\v1\Area;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Services\CityService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    use Res;


    public function __construct(protected CityService $cityService)
    {
    }

    /**
     * Register a new lawyer
     */
    public function index(Request $request)
    {
        try {
            $cities = $this->cityService->getActiveCities($request->all(), 0);
            $cities = CityResource::collection($cities);
            return $this->sendRes(__('validation.success'), true, $cities);
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
