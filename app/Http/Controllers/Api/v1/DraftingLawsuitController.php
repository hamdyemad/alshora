<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DraftingLawsuitResource;
use App\Services\DraftingLawsuitService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DraftingLawsuitController extends Controller
{
    use Res;

    public function __construct(
        protected DraftingLawsuitService $draftingLawsuitService
    ) {
    }

    /**
     * Get all drafting lawsuits
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'search' => $request->input('search'),
                'active' => $request->input('active'),
                'per_page' => $request->input('per_page', 15),
            ];

            $lawsuits = $this->draftingLawsuitService->getAllDraftingLawsuits($filters);

            return $this->sendRes(
                __('common.success'),
                true,
                [
                    'items' => DraftingLawsuitResource::collection($lawsuits->items()),
                    'pagination' => [
                        'total' => $lawsuits->total(),
                        'per_page' => $lawsuits->perPage(),
                        'current_page' => $lawsuits->currentPage(),
                        'last_page' => $lawsuits->lastPage(),
                        'from' => $lawsuits->firstItem(),
                        'to' => $lawsuits->lastItem(),
                    ]
                ]
            );

        } catch (\Exception $e) {
            Log::error('Error fetching drafting lawsuits: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get a specific drafting lawsuit
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $lawsuit = $this->draftingLawsuitService->getDraftingLawsuitById($id);

            if (!$lawsuit) {
                return $this->sendRes(__('drafting_lawsuits.not_found'), false, [], [], 404);
            }

            return $this->sendRes(
                __('common.success'),
                true,
                new DraftingLawsuitResource($lawsuit)
            );

        } catch (\Exception $e) {
            Log::error('Error fetching drafting lawsuit: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
