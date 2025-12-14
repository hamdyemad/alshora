<?php

namespace App\Services;

use App\Interfaces\DraftingContractRepositoryInterface;
use App\Models\DraftingContract;
use Illuminate\Support\Facades\Log;

class DraftingContractService
{
    protected $draftingContractRepository;

    public function __construct(DraftingContractRepositoryInterface $draftingContractRepository)
    {
        $this->draftingContractRepository = $draftingContractRepository;
    }

    /**
     * Get all drafting contracts with filters
     */
    public function getAllDraftingContracts(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->draftingContractRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching drafting contracts: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all drafting contracts
     */
    public function getAll()
    {
        try {
            return $this->draftingContractRepository->getAll();
        } catch (\Exception $e) {
            Log::error('Error fetching drafting contracts: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get drafting contract by ID
     */
    public function getDraftingContractById(int $id)
    {
        try {
            return $this->draftingContractRepository->getDraftingContractById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching drafting contract: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create drafting contract
     */
    public function createDraftingContract(array $data)
    {
        try {
            return $this->draftingContractRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating drafting contract: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update drafting contract
     */
    public function updateDraftingContract(DraftingContract $draftingContract, array $data)
    {
        try {
            return $this->draftingContractRepository->update($draftingContract, $data);
        } catch (\Exception $e) {
            Log::error('Error updating drafting contract: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete drafting contract
     */
    public function deleteDraftingContract(DraftingContract $draftingContract)
    {
        try {
            return $this->draftingContractRepository->delete($draftingContract);
        } catch (\Exception $e) {
            Log::error('Error deleting drafting contract: ' . $e->getMessage());
            throw $e;
        }
    }
}
