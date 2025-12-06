<?php

namespace App\Services;

use App\Interfaces\RegionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class RegionService
{
    protected $regionRepository;

    public function __construct(RegionRepositoryInterface $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * Get all regions with filters and pagination
     */
    public function getAllRegions(array $filters = [], int $perPage = 15)
    {
        try {
            return $this->regionRepository->getAllRegions($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching regions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get region by ID
     */
    public function getRegionById(int $id)
    {
        try {
            return $this->regionRepository->getRegionById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching region: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new region with translations
     */
    public function createRegion(array $data)
    {
        try {
            $preparedData = $this->prepareRegionData($data);
            return $this->regionRepository->createRegion($preparedData);
        } catch (\Exception $e) {
            Log::error('Error creating region: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update region with translations
     */
    public function updateRegion(int $id, array $data)
    {
        try {
            $preparedData = $this->prepareRegionData($data);
            return $this->regionRepository->updateRegion($id, $preparedData);
        } catch (\Exception $e) {
            Log::error('Error updating region: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete region
     */
    public function deleteRegion(int $id)
    {
        try {
            return $this->regionRepository->deleteRegion($id);
        } catch (\Exception $e) {
            Log::error('Error deleting region: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get active regions
     */
    public function getActiveRegions()
    {
        try {
            return $this->regionRepository->getActiveRegions();
        } catch (\Exception $e) {
            Log::error('Error fetching active regions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get regions by city
     */
    public function getRegionsByCity(int $cityId)
    {
        try {
            return $this->regionRepository->getRegionsByCity($cityId);
        } catch (\Exception $e) {
            Log::error('Error fetching regions by city: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepare region data for storage
     */
    private function prepareRegionData(array $data): array
    {
        return [
            'translations' => $data['translations'] ?? [],
            'city_id' => $data['city_id'],
            'active' => $data['active'] ?? 0,
        ];
    }
}
