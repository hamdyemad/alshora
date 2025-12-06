<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegisterGradeResource;
use App\Http\Resources\SectionOfLawResource;
use App\Services\CountryService;
use App\Services\LanguageService;
use App\Services\RegisterGradeService;
use App\Services\SectionOfLawService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SectionOfLawController extends Controller
{

    use Res;
    public function __construct(protected SectionOfLawService $sectionOfLawService)
    {
    }

    /**
     * Display a listing of countries
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'active' => '1',
            ];
            $per_page = request('per_page') ?? 0;
            $sectionOfLaws = $this->sectionOfLawService->getAll($filters, $per_page);
            
            // Handle paginated response
            if($per_page > 0) {
                $data = [
                    'data' => SectionOfLawResource::collection($sectionOfLaws->items()),
                    'pagination' => [
                        'current_page' => $sectionOfLaws->currentPage(),
                        'last_page' => $sectionOfLaws->lastPage(),
                        'per_page' => $sectionOfLaws->perPage(),
                        'total' => $sectionOfLaws->total(),
                        'from' => $sectionOfLaws->firstItem(),
                        'to' => $sectionOfLaws->lastItem(),
                    ]
                ];
                return $this->sendRes(__('validation.success'), true, $data);
            }
            
            // Return all items without pagination
            return $this->sendRes(__('validation.success'), true, SectionOfLawResource::collection($sectionOfLaws));
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

}
