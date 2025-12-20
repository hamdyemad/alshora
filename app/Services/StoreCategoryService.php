<?php

namespace App\Services;

use App\Interfaces\StoreCategoryRepositoryInterface;
use App\Models\StoreCategory;
use Illuminate\Support\Facades\Log;

class StoreCategoryService
{
    protected $categoryRepository;

    public function __construct(StoreCategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->categoryRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getCategoryById(int $id)
    {
        try {
            return $this->categoryRepository->getCategoryById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching category: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createCategory(array $data)
    {
        try {
            return $this->categoryRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateCategory(StoreCategory $category, array $data)
    {
        try {
            return $this->categoryRepository->update($category, $data);
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteCategory(StoreCategory $category)
    {
        try {
            return $this->categoryRepository->delete($category);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            throw $e;
        }
    }
}

