<?php

namespace App\Repositories;

use App\Interfaces\SubRegionRepositoryInterface;
use App\Models\Areas\SubRegion;
use Illuminate\Support\Facades\DB;

class SubRegionRepository implements SubRegionRepositoryInterface
{
    /**
     * Get all subregions with filters and pagination
     */
    public function getAllSubRegions(array $filters = [], int $perPage = 15)
    {
        $query = SubRegion::with(['region', 'translations']);

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%");
            });
        }

        // Region filter
        if (!empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }

        // Active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Date from filter
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_date_from']);
        }

        // Date to filter
        if (!empty($filters['created_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_date_to']);
        }

        // Order by latest
        $query->latest();

        return $query->paginate($perPage);
    }

    /**
     * Get subregion by ID
     */
    public function getSubRegionById(int $id)
    {
        return SubRegion::with(['region', 'translations'])->findOrFail($id);
    }

    /**
     * Create a new subregion
     */
    public function createSubRegion(array $data)
    {
        return DB::transaction(function () use ($data) {
            $subregion = SubRegion::create([
                'region_id' => $data['region_id'],
                'active' => $data['active'] ?? 0,
            ]);

            // Set translations from nested array
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $subregion->translations()->create([
                            'lang_id' => $langId,
                            'lang_key' => 'name',
                            'lang_value' => $translation['name'],
                        ]);
                    }
                }
            }
            return $subregion;
        });
    }

    /**
     * Update subregion
     */
    public function updateSubRegion(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $subregion = SubRegion::findOrFail($id);

            $subregion->update([
                'region_id' => $data['region_id'],
                'active' => $data['active'] ?? 0,
            ]);

            // Update translations from nested array
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $subregion->translations()->updateOrCreate(
                            [
                                'lang_id' => $langId,
                                'lang_key' => 'name',
                            ],
                            [
                                'lang_value' => $translation['name'],
                            ]
                        );
                    }
                }
            }

            $subregion->refresh();
            $subregion->load('translations');

            return $subregion;
        });
    }

    /**
     * Delete subregion
     */
    public function deleteSubRegion(int $id)
    {
        $subregion = SubRegion::findOrFail($id);
        $subregion->translations()->delete();
        return $subregion->delete();
    }

    /**
     * Get active subregions
     */
    public function getActiveSubRegions()
    {
        return SubRegion::with('translations')->where('active', 1)->get();
    }

    /**
     * Get subregions by region
     */
    public function getSubRegionsByRegion(int $regionId)
    {
        return SubRegion::where('region_id', $regionId)
            ->where('active', 1)
            ->with('translations')
            ->get();
    }
}
