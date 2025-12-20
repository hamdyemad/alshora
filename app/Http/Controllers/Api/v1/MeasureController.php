<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeasureResource;
use App\Services\MeasureService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeasureController extends Controller
{
    use Res;

    public function __construct(
        protected MeasureService $measureService
    ) {
    }

    /**
     * Get all measures
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $filters = [
                'search' => $request->input('search'),
                'active' => $request->input('active'),
            ];

            $measures = $this->measureService->getAllMeasures($filters, $perPage);

            return $this->sendRes(
                __('common.success'),
                true,
                [
                    'items' => MeasureResource::collection($measures->items()),
                    'pagination' => [
                        'total' => $measures->total(),
                        'per_page' => $measures->perPage(),
                        'current_page' => $measures->currentPage(),
                        'last_page' => $measures->lastPage(),
                        'from' => $measures->firstItem(),
                        'to' => $measures->lastItem(),
                    ]
                ]
            );

        } catch (\Exception $e) {
            Log::error('Error fetching measures: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get a specific measure
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $measure = $this->measureService->getMeasureById($id);

            if (!$measure) {
                return $this->sendRes(__('contracts.measure_not_found'), false, [], [], 404);
            }

            return $this->sendRes(
                __('common.success'),
                true,
                new MeasureResource($measure)
            );

        } catch (\Exception $e) {
            Log::error('Error fetching measure: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}

