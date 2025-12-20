<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreProductResource;
use App\Services\StoreProductService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreProductController extends Controller
{
    use Res;

    public function __construct(
        protected StoreProductService $productService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $filters = [
                'search' => $request->input('search'),
                'category_id' => $request->input('category_id'),
                'active' => $request->input('active'),
            ];

            $products = $this->productService->getAllProducts($filters, $perPage);

            return $this->sendRes(
                __('common.success'),
                true,
                [
                    'items' => StoreProductResource::collection($products->items()),
                    'pagination' => [
                        'total' => $products->total(),
                        'per_page' => $products->perPage(),
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'from' => $products->firstItem(),
                        'to' => $products->lastItem(),
                    ]
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);
            return $this->sendRes(
                __('common.success'),
                true,
                new StoreProductResource($product)
            );
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}

