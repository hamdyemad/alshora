<?php

namespace App\Http\Controllers;

use App\Models\StoreCategory;
use App\Services\StoreCategoryService;
use Illuminate\Http\Request;

class StoreCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(StoreCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'active' => $request->input('active'),
        ];
        $categories = $this->categoryService->getAllCategories($filters, 10);
        return view('pages.store.categories.index', compact('categories'))
            ->with([
                'search' => $filters['search'],
                'active' => $filters['active'],
            ]);
    }

    public function create()
    {
        return view('pages.store.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean'
        ]);

        $data = $request->only(['name_en', 'name_ar', 'active']);
        $data['active'] = $request->has('active');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $this->categoryService->createCategory($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.created_successfully'),
                'redirect' => route('admin.store.categories.index')
            ]);
        }

        return redirect()->route('admin.store.categories.index')
            ->with('success', trans('common.created_successfully'));
    }

    public function show(StoreCategory $category)
    {
        return view('pages.store.categories.show', compact('category'));
    }

    public function edit(StoreCategory $category)
    {
        return view('pages.store.categories.edit', compact('category'));
    }

    public function update(Request $request, StoreCategory $category)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean'
        ]);

        $data = $request->only(['name_en', 'name_ar', 'active']);
        $data['active'] = $request->has('active');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $this->categoryService->updateCategory($category, $data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.updated_successfully'),
                'redirect' => route('admin.store.categories.index')
            ]);
        }

        return redirect()->route('admin.store.categories.index')
            ->with('success', trans('common.updated_successfully'));
    }

    public function destroy(StoreCategory $category)
    {
        $this->categoryService->deleteCategory($category);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.deleted_successfully'),
                'redirect' => route('admin.store.categories.index')
            ]);
        }

        return redirect()->route('admin.store.categories.index')
            ->with('success', trans('common.deleted_successfully'));
    }
}

