<?php

namespace App\Http\Controllers\Api\v1\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Lawyer\UpdateOfficeWorkRequest;
use App\Http\Resources\LawyerResource;
use App\Services\LawyerService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LawyerController extends Controller
{
    use Res;


    public function __construct(protected LawyerService $lawyerService)
    {
    }


    public function index(Request $request)
    {
        try {
            $filters = [
                'active' => '1',
            ];
            $per_page = request('per_page') ?? 0;
            $lawyers = $this->lawyerService->getAllLawyers($filters, $per_page);

            // Handle paginated response
            if($per_page > 0) {
                $data = [
                    'data' => LawyerResource::collection($lawyers->items()),
                    'pagination' => [
                        'current_page' => $lawyers->currentPage(),
                        'last_page' => $lawyers->lastPage(),
                        'per_page' => $lawyers->perPage(),
                        'total' => $lawyers->total(),
                        'from' => $lawyers->firstItem(),
                        'to' => $lawyers->lastItem(),
                    ]
                ];
                return $this->sendRes(__('validation.success'), true, $data);
            }

            // Return all items without pagination
            return $this->sendRes(__('validation.success'), true, LawyerResource::collection($lawyers));
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function show(Request $request, $lawyer_id)
    {
        try {
            $lawyer = $this->lawyerService->getLawyerById($lawyer_id);
            return $this->sendRes(__('validation.success'), true, new LawyerResource($lawyer));
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }



}
