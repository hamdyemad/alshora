<?php

namespace App\Services;

use App\Interfaces\MeasureRepositoryInterface;
use App\Models\Measure;
use Illuminate\Support\Facades\Log;

class MeasureService
{
    protected $measureRepository;

    public function __construct(MeasureRepositoryInterface $measureRepository)
    {
        $this->measureRepository = $measureRepository;
    }

    /**
     * Get all measures with filters
     */
    public function getAllMeasures(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->measureRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching measures: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all measures
     */
    public function getAll()
    {
        try {
            return $this->measureRepository->getAll();
        } catch (\Exception $e) {
            Log::error('Error fetching measures: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get measure by ID
     */
    public function getMeasureById(int $id)
    {
        try {
            return $this->measureRepository->getMeasureById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching measure: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create measure
     */
    public function createMeasure(array $data)
    {
        try {
            return $this->measureRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating measure: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update measure
     */
    public function updateMeasure(Measure $measure, array $data)
    {
        try {
            return $this->measureRepository->update($measure, $data);
        } catch (\Exception $e) {
            Log::error('Error updating measure: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete measure
     */
    public function deleteMeasure(Measure $measure)
    {
        try {
            return $this->measureRepository->delete($measure);
        } catch (\Exception $e) {
            Log::error('Error deleting measure: ' . $e->getMessage());
            throw $e;
        }
    }
}

