<?php

namespace App\Http\Controllers\Api\v1\Area;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\RegionResource;
use App\Services\CityService;
use App\Services\RegionService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    use Res;


    public function __construct(
        protected RegionService $regionService,
        protected CityService $cityService,
        )
    {
    }

    /**
     * Register a new lawyer
     */
    public function getRegionsByCity($city_id)
    {
        $city = $this->cityService->getCityById($city_id);
        try {
            if($city) {
                $regions = $this->regionService->getRegionsByCity($city_id);
                $regions = RegionResource::collection($regions);
                return $this->sendRes(__('validation.success'), true, $regions);
            } else {
                return $this->sendRes(__('areas/city.city_not_found'), false, [], [], 500);
            }
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

}
