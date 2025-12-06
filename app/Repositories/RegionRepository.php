<?php

namespace App\Repositories;

use App\Interfaces\RegionRepositoryInterface;
use App\Models\Areas\Region;
use Illuminate\Support\Facades\DB;

class RegionRepository implements RegionRepositoryInterface
{
    /**
     * Get all regions with filters and pagination
     */
    public function getAllRegions(array $filters = [], int $perPage = 15)
    {
        $query = Region::with(['city', 'translations']);

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%");
            });
        }

        // City filter
        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
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
     * Get region by ID
     */
    public function getRegionById(int $id)
    {
        return Region::with(['city', 'translations'])->findOrFail($id);
    }

    /**
     * Create a new region
     */
    public function createRegion(array $data)
    {
        return DB::transaction(function () use ($data) {
            $region = Region::create([
                'city_id' => $data['city_id'],
                'active' => $data['active'] ?? 0,
            ]);

            // Set translations from nested array
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $region->translations()->create([
                            'lang_id' => $langId,
                            'lang_key' => 'name',
                            'lang_value' => $translation['name'],
                        ]);
                    }
                }
            }
            return $region;
        });
    }

    /**
     * Update region
     */
    public function updateRegion(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $region = Region::findOrFail($id);

            $region->update([
                'city_id' => $data['city_id'],
                'active' => $data['active'] ?? 0,
            ]);

            // Update translations from nested array
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $langId => $translation) {
                    if (isset($translation['name'])) {
                        $region->translations()->updateOrCreate(
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

            $region->refresh();
            $region->load('translations');

            return $region;
        });
    }

    /**
     * Delete region
     */
    public function deleteRegion(int $id)
    {
        $region = Region::findOrFail($id);
        $region->translations()->delete();
        return $region->delete();
    }

    /**
     * Get active regions
     */
    public function getActiveRegions()
    {
        return Region::with('translations')->where('active', 1)->get();
    }

    /**
     * Get regions by city
     */
    public function getRegionsByCity(int $cityId)
    {
        return Region::where('city_id', $cityId)
            ->active()
            ->with('translations')
            ->get();
    }
}
