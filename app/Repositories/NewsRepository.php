<?php

namespace App\Repositories;

use App\Interfaces\NewsRepositoryInterface;
use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NewsRepository implements NewsRepositoryInterface
{
    /**
     * Get all news with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = News::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->whereIn('lang_key', ['title', 'source']);
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Apply date from filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }

        // Apply date to filter
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        return $query->latest('date')->paginate($perPage);
    }

    /**
     * Find a news item by ID
     */
    public function findById(int $id): ?News
    {
        return News::find($id);
    }

    /**
     * Create a new news item
     */
    public function create(array $data): News
    {
        return DB::transaction(function () use ($data) {
            // Create news record
            $news = News::create([
                'source_link' => $data['source_link'] ?? null,
                'date' => $data['date'],
                'active' => $data['active'] ?? false,
            ]);

            // Set translations using the Translation trait
            $news->setTranslation('title', 'en', $data['title_en']);
            $news->setTranslation('title', 'ar', $data['title_ar']);
            $news->setTranslation('details', 'en', $data['details_en']);
            $news->setTranslation('details', 'ar', $data['details_ar']);
            
            if (!empty($data['source_en'])) {
                $news->setTranslation('source', 'en', $data['source_en']);
            }
            if (!empty($data['source_ar'])) {
                $news->setTranslation('source', 'ar', $data['source_ar']);
            }
            
            $news->save();

            return $news->fresh();
        });
    }

    /**
     * Update a news item
     */
    public function update(News $news, array $data): News
    {
        return DB::transaction(function () use ($news, $data) {
            // Update basic fields
            $news->update([
                'source_link' => $data['source_link'] ?? null,
                'date' => $data['date'],
                'active' => $data['active'] ?? false,
            ]);

            // Update translations using the Translation trait
            $news->setTranslation('title', 'en', $data['title_en']);
            $news->setTranslation('title', 'ar', $data['title_ar']);
            $news->setTranslation('details', 'en', $data['details_en']);
            $news->setTranslation('details', 'ar', $data['details_ar']);
            
            if (!empty($data['source_en'])) {
                $news->setTranslation('source', 'en', $data['source_en']);
            }
            if (!empty($data['source_ar'])) {
                $news->setTranslation('source', 'ar', $data['source_ar']);
            }
            
            $news->save();

            return $news->fresh();
        });
    }

    /**
     * Delete a news item
     */
    public function delete(News $news): bool
    {
        return $news->delete();
    }

    /**
     * Get all news without pagination
     */
    public function getAll(): Collection
    {
        return News::latest('date')->get();
    }
}
