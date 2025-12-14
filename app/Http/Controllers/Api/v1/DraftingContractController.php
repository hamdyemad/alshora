<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DraftingContractResource;
use App\Services\DraftingContractService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DraftingContractController extends Controller
{
    use Res;

    public function __construct(
        protected DraftingContractService $draftingContractService
    ) {
    }

    /**
     * Get all drafting contracts
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

            $contracts = $this->draftingContractService->getAllDraftingContracts($filters);

            return $this->sendRes(
                __('common.success'),
                true,
                [
                    'items' => DraftingContractResource::collection($contracts->items()),
                    'pagination' => [
                        'total' => $contracts->total(),
                        'per_page' => $contracts->perPage(),
                        'current_page' => $contracts->currentPage(),
                        'last_page' => $contracts->lastPage(),
                        'from' => $contracts->firstItem(),
                        'to' => $contracts->lastItem(),
                    ]
                ]
            );

        } catch (\Exception $e) {
            Log::error('Error fetching drafting contracts: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get a specific drafting contract
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $contract = $this->draftingContractService->getDraftingContractById($id);

            if (!$contract) {
                return $this->sendRes(__('drafting_contracts.not_found'), false, [], [], 404);
            }

            return $this->sendRes(
                __('common.success'),
                true,
                new DraftingContractResource($contract)
            );

        } catch (\Exception $e) {
            Log::error('Error fetching drafting contract: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
