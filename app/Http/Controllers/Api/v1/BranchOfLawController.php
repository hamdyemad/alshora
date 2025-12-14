<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchOfLawResource;
use App\Services\BranchOfLawService;
use App\Traits\Res;
use Illuminate\Http\Request;

class BranchOfLawController extends Controller
{
    use Res;

    public function __construct(
        protected BranchOfLawService $branchOfLawService
    ) {
    }

    /**
     * Get all branches of laws
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

            $branches = $this->branchOfLawService->getAllBranchesOfLaws($filters);

            return $this->sendRes(
                __('branches_of_laws.retrieved_successfully'),
                true,
                [
                    'branches' => BranchOfLawResource::collection($branches->items()),
                    'pagination' => [
                        'total' => $branches->total(),
                        'per_page' => $branches->perPage(),
                        'current_page' => $branches->currentPage(),
                        'last_page' => $branches->lastPage(),
                        'from' => $branches->firstItem(),
                        'to' => $branches->lastItem(),
                    ]
                ]
            );

        } catch (\Exception $e) {
            \Log::error('Error fetching branches of laws: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get a specific branch of law with its laws
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $branch = $this->branchOfLawService->getBranchOfLawById($id);

            if (!$branch) {
                return $this->sendRes(__('branches_of_laws.not_found'), false, [], [], 404);
            }

            // Load laws relationship
            $branch->load(['laws' => function ($query) {
                $query->where('active', true)->with('translations');
            }]);

            return $this->sendRes(
                __('branches_of_laws.retrieved_successfully'),
                true,
                new BranchOfLawResource($branch)
            );

        } catch (\Exception $e) {
            \Log::error('Error fetching branch of law: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
