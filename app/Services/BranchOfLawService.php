<?php

namespace App\Services;

use App\Interfaces\BranchOfLawRepositoryInterface;
use App\Models\BranchOfLaw;
use Illuminate\Support\Facades\Log;

class BranchOfLawService
{
    protected $branchOfLawRepository;

    public function __construct(BranchOfLawRepositoryInterface $branchOfLawRepository)
    {
        $this->branchOfLawRepository = $branchOfLawRepository;
    }

    /**
     * Get all branches of laws with filters
     */
    public function getAllBranchesOfLaws(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->branchOfLawRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching branches of laws: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all branches of laws
     */
    public function getAll()
    {
        try {
            return $this->branchOfLawRepository->getAll();
        } catch (\Exception $e) {
            Log::error('Error fetching branches of laws: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get branch of law by ID
     */
    public function getBranchOfLawById(int $id)
    {
        try {
            return $this->branchOfLawRepository->getBranchOfLawById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching branch of law: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create branch of law
     */
    public function createBranchOfLaw(array $data)
    {
        try {
            return $this->branchOfLawRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating branch of law: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update branch of law
     */
    public function updateBranchOfLaw(BranchOfLaw $branchOfLaw, array $data)
    {
        try {
            return $this->branchOfLawRepository->update($branchOfLaw, $data);
        } catch (\Exception $e) {
            Log::error('Error updating branch of law: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete branch of law
     */
    public function deleteBranchOfLaw(BranchOfLaw $branchOfLaw)
    {
        try {
            return $this->branchOfLawRepository->delete($branchOfLaw);
        } catch (\Exception $e) {
            Log::error('Error deleting branch of law: ' . $e->getMessage());
            throw $e;
        }
    }
}
