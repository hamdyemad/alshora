<?php

namespace App\Services;

use App\Interfaces\DraftingLawsuitRepositoryInterface;
use App\Models\DraftingLawsuit;
use Illuminate\Support\Facades\Log;

class DraftingLawsuitService
{
    protected $draftingLawsuitRepository;

    public function __construct(DraftingLawsuitRepositoryInterface $draftingLawsuitRepository)
    {
        $this->draftingLawsuitRepository = $draftingLawsuitRepository;
    }

    /**
     * Get all drafting lawsuits with filters
     */
    public function getAllDraftingLawsuits(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->draftingLawsuitRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching drafting lawsuits: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all drafting lawsuits
     */
    public function getAll()
    {
        try {
            return $this->draftingLawsuitRepository->getAll();
        } catch (\Exception $e) {
            Log::error('Error fetching drafting lawsuits: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get drafting lawsuit by ID
     */
    public function getDraftingLawsuitById(int $id)
    {
        try {
            return $this->draftingLawsuitRepository->getDraftingLawsuitById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching drafting lawsuit: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create drafting lawsuit
     */
    public function createDraftingLawsuit(array $data)
    {
        try {
            return $this->draftingLawsuitRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating drafting lawsuit: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update drafting lawsuit
     */
    public function updateDraftingLawsuit(DraftingLawsuit $draftingLawsuit, array $data)
    {
        try {
            return $this->draftingLawsuitRepository->update($draftingLawsuit, $data);
        } catch (\Exception $e) {
            Log::error('Error updating drafting lawsuit: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete drafting lawsuit
     */
    public function deleteDraftingLawsuit(DraftingLawsuit $draftingLawsuit)
    {
        try {
            return $this->draftingLawsuitRepository->delete($draftingLawsuit);
        } catch (\Exception $e) {
            Log::error('Error deleting drafting lawsuit: ' . $e->getMessage());
            throw $e;
        }
    }
}
