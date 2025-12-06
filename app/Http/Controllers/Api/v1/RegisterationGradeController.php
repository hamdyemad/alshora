<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegisterGradeResource;
use App\Services\CountryService;
use App\Services\LanguageService;
use App\Services\RegisterGradeService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RegisterationGradeController extends Controller
{

    use Res;
    public function __construct(protected RegisterGradeService $registerGradeService)
    {
    }

    /**
     * Display a listing of countries
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $registerGrades = $this->registerGradeService->getAll();
            $registerGrades = RegisterGradeResource::collection($registerGrades);
            return $this->sendRes(__('validation.success'), true, $registerGrades);
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

}
