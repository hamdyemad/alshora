<?php

namespace App\Repositories;

use App\Interfaces\MeasureRepositoryInterface;
use App\Models\Measure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MeasureRepository implements MeasureRepositoryInterface
{
    /**
     * Get all measures with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Measure::with(['translations']);

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->where('lang_key', 'title');
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get measure by ID
     */
    public function getMeasureById(int $id): Measure
    {
        return Measure::with(['translations'])->findOrFail($id);
    }

    /**
     * Get all measures
     */
    public function getAll()
    {
        return Measure::with(['translations'])->get();
    }

    /**
     * Create a new measure
     */
    public function create(array $data): Measure
    {
        return DB::transaction(function () use ($data) {
            $measure = Measure::create([
                'active' => $data['active'] ?? true,
            ]);

            $measure->setTranslation('title', 'en', $data['title_en']);
            $measure->setTranslation('title', 'ar', $data['title_ar']);
            $measure->setTranslation('description', 'en', $data['description_en'] ?? '');
            $measure->setTranslation('description', 'ar', $data['description_ar'] ?? '');
            $measure->save();

            return $measure->fresh(['translations']);
        });
    }

    /**
     * Update a measure
     */
    public function update(Measure $measure, array $data): Measure
    {
        return DB::transaction(function () use ($measure, $data) {
            $measure->update([
                'active' => $data['active'] ?? true,
            ]);

            $measure->setTranslation('title', 'en', $data['title_en']);
            $measure->setTranslation('title', 'ar', $data['title_ar']);
            $measure->setTranslation('description', 'en', $data['description_en'] ?? '');
            $measure->setTranslation('description', 'ar', $data['description_ar'] ?? '');
            $measure->save();

            return $measure->fresh(['translations']);
        });
    }

    /**
     * Delete a measure
     */
    public function delete(Measure $measure): bool
    {
        return $measure->delete();
    }
}
