<?php

namespace App\Repositories;

use App\Interfaces\StoreProductRepositoryInterface;
use App\Models\StoreProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoreProductRepository implements StoreProductRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = StoreProduct::with(['translations', 'category']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->where('lang_key', 'name');
            });
        }

        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAll()
    {
        return StoreProduct::with(['translations', 'category'])->get();
    }

    public function getProductById(int $id): StoreProduct
    {
        return StoreProduct::with(['translations', 'category'])->findOrFail($id);
    }

    public function create(array $data): StoreProduct
    {
        return DB::transaction(function () use ($data) {
            $product = StoreProduct::create([
                'category_id' => $data['category_id'],
                'price' => $data['price'] ?? 0,
                'active' => $data['active'] ?? true,
            ]);

            if (isset($data['image']) && $data['image']) {
                $product->image = $data['image']->store('store/products', 'public');
                $product->save();
            }

            $product->setTranslation('name', 'en', $data['name_en']);
            $product->setTranslation('name', 'ar', $data['name_ar']);
            $product->setTranslation('description', 'en', $data['description_en'] ?? '');
            $product->setTranslation('description', 'ar', $data['description_ar'] ?? '');
            $product->save();

            return $product->fresh(['translations', 'category']);
        });
    }

    public function update(StoreProduct $product, array $data): StoreProduct
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update([
                'category_id' => $data['category_id'] ?? $product->category_id,
                'price' => $data['price'] ?? $product->price,
                'active' => $data['active'] ?? $product->active,
            ]);

            if (isset($data['image']) && $data['image']) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->image = $data['image']->store('store/products', 'public');
                $product->save();
            }

            $product->setTranslation('name', 'en', $data['name_en']);
            $product->setTranslation('name', 'ar', $data['name_ar']);
            $product->setTranslation('description', 'en', $data['description_en'] ?? '');
            $product->setTranslation('description', 'ar', $data['description_ar'] ?? '');
            $product->save();

            return $product->fresh(['translations', 'category']);
        });
    }

    public function delete(StoreProduct $product): bool
    {
        return DB::transaction(function () use ($product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            return $product->delete();
        });
    }
}

