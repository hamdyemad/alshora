<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreCategoryResource;
use App\Services\StoreCategoryService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreCategoryController extends Controller
{
    use Res;

    public function __construct(
        protected StoreCategoryService $categoryService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $filters = [
                'search' => $request->input('search'),
                'active' => $request->input('active'),
            ];

            $categories = $this->categoryService->getAllCategories($filters, $perPage);

            return $this->sendRes(
                __('common.success'),
                true,
                [
                    'items' => StoreCategoryResource::collection($categories->items()),
                    'pagination' => [
                        'total' => $categories->total(),
                        'per_page' => $categories->perPage(),
                        'current_page' => $categories->currentPage(),
                        'last_page' => $categories->lastPage(),
                        'from' => $categories->firstItem(),
                        'to' => $categories->lastItem(),
                    ]
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return $this->sendRes(
                __('common.success'),
                true,
                new StoreCategoryResource($category)
            );
        } catch (\Exception $e) {
            Log::error('Error fetching category: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}

