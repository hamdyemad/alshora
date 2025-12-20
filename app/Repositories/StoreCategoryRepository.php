<?php

namespace App\Repositories;

use App\Interfaces\StoreCategoryRepositoryInterface;
use App\Models\StoreCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoreCategoryRepository implements StoreCategoryRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = StoreCategory::with(['translations']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->where('lang_key', 'name');
            });
        }

        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAll()
    {
        return StoreCategory::with(['translations'])->get();
    }

    public function getCategoryById(int $id): StoreCategory
    {
        return StoreCategory::with(['translations'])->findOrFail($id);
    }

    public function create(array $data): StoreCategory
    {
        return DB::transaction(function () use ($data) {
            $category = StoreCategory::create([
                'active' => $data['active'] ?? true,
            ]);

            if (isset($data['image']) && $data['image']) {
                $category->image = $data['image']->store('store/categories', 'public');
                $category->save();
            }

            $category->setTranslation('name', 'en', $data['name_en']);
            $category->setTranslation('name', 'ar', $data['name_ar']);
            $category->save();

            return $category->fresh(['translations']);
        });
    }

    public function update(StoreCategory $category, array $data): StoreCategory
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update([
                'active' => $data['active'] ?? true,
            ]);

            if (isset($data['image']) && $data['image']) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $category->image = $data['image']->store('store/categories', 'public');
                $category->save();
            }

            $category->setTranslation('name', 'en', $data['name_en']);
            $category->setTranslation('name', 'ar', $data['name_ar']);
            $category->save();

            return $category->fresh(['translations']);
        });
    }

    public function delete(StoreCategory $category): bool
    {
        return DB::transaction(function () use ($category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            return $category->delete();
        });
    }
}

