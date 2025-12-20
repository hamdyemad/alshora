<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use App\Services\StoreProductService;
use App\Services\StoreCategoryService;
use Illuminate\Http\Request;

class StoreProductController extends Controller
{
    protected $productService;
    protected $categoryService;

    public function __construct(StoreProductService $productService, StoreCategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category_id' => $request->input('category_id'),
            'active' => $request->input('active'),
        ];
        $products = $this->productService->getAllProducts($filters, 10);
        $categories = $this->categoryService->getAllCategories([], 0);
        return view('pages.store.products.index', compact('products', 'categories'))
            ->with([
                'search' => $filters['search'],
                'category_id' => $filters['category_id'],
                'active' => $filters['active'],
            ]);
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories([], 0);
        return view('pages.store.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:store_categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean'
        ]);

        $data = $request->only(['category_id', 'name_en', 'name_ar', 'description_en', 'description_ar', 'price', 'active']);
        $data['active'] = $request->has('active');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $this->productService->createProduct($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.created_successfully'),
                'redirect' => route('admin.store.products.index')
            ]);
        }

        return redirect()->route('admin.store.products.index')
            ->with('success', trans('common.created_successfully'));
    }

    public function show(StoreProduct $product)
    {
        return view('pages.store.products.show', compact('product'));
    }

    public function edit(StoreProduct $product)
    {
        $categories = $this->categoryService->getAllCategories([], 0);
        return view('pages.store.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, StoreProduct $product)
    {
        $request->validate([
            'category_id' => 'required|exists:store_categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean'
        ]);

        $data = $request->only(['category_id', 'name_en', 'name_ar', 'description_en', 'description_ar', 'price', 'active']);
        $data['active'] = $request->has('active');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $this->productService->updateProduct($product, $data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.updated_successfully'),
                'redirect' => route('admin.store.products.index')
            ]);
        }

        return redirect()->route('admin.store.products.index')
            ->with('success', trans('common.updated_successfully'));
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $products = StoreProduct::whereHas('translations', function ($query) use ($term) {
            $query->where('lang_key', 'name')
                  ->where('lang_value', 'like', '%' . $term . '%');
        })->get();

        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url,
            ];
        }

        return response()->json($results);
    }

    public function destroy(StoreProduct $product)
    {
        $this->productService->deleteProduct($product);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.deleted_successfully'),
                'redirect' => route('admin.store.products.index')
            ]);
        }

        return redirect()->route('admin.store.products.index')
            ->with('success', trans('common.deleted_successfully'));
    }
}

