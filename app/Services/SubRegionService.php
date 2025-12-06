<?php

namespace App\Services;

use App\Interfaces\SubRegionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SubRegionService
{
    protected $subregionRepository;

    public function __construct(SubRegionRepositoryInterface $subregionRepository)
    {
        $this->subregionRepository = $subregionRepository;
    }

    /**
     * Get all subregions with filters and pagination
     */
    public function getAllSubRegions(array $filters = [], int $perPage = 15)
    {
        try {
            return $this->subregionRepository->getAllSubRegions($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching subregions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get subregion by ID
     */
    public function getSubRegionById(int $id)
    {
        try {
            return $this->subregionRepository->getSubRegionById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching subregion: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new subregion with translations
     */
    public function createSubRegion(array $data)
    {
        try {
            $preparedData = $this->prepareSubRegionData($data);
            return $this->subregionRepository->createSubRegion($preparedData);
        } catch (\Exception $e) {
            Log::error('Error creating subregion: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update subregion with translations
     */
    public function updateSubRegion(int $id, array $data)
    {
        try {
            $preparedData = $this->prepareSubRegionData($data);
            return $this->subregionRepository->updateSubRegion($id, $preparedData);
        } catch (\Exception $e) {
            Log::error('Error updating subregion: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete subregion
     */
    public function deleteSubRegion(int $id)
    {
        try {
            return $this->subregionRepository->deleteSubRegion($id);
        } catch (\Exception $e) {
            Log::error('Error deleting subregion: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get active subregions
     */
    public function getActiveSubRegions()
    {
        try {
            return $this->subregionRepository->getActiveSubRegions();
        } catch (\Exception $e) {
            Log::error('Error fetching active subregions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get subregions by region
     */
    public function getSubRegionsByRegion(int $regionId)
    {
        try {
            return $this->subregionRepository->getSubRegionsByRegion($regionId);
        } catch (\Exception $e) {
            Log::error('Error fetching subregions by region: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepare subregion data for storage
     */
    private function prepareSubRegionData(array $data): array
    {
        return [
            'translations' => $data['translations'] ?? [],
            'region_id' => $data['region_id'],
            'active' => $data['active'] ?? 0,
        ];
    }
}
