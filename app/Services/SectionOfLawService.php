<?php

namespace App\Services;

use App\Interfaces\SectionOfLawRepositoryInterface;
use App\Models\SectionOfLaw;
use Illuminate\Support\Facades\Log;

class SectionOfLawService
{
    protected $sectionOfLawRepository;

    public function __construct(SectionOfLawRepositoryInterface $sectionOfLawRepository)
    {
        $this->sectionOfLawRepository = $sectionOfLawRepository;
    }

    /**
     * Get all sections of laws with filters
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->sectionOfLawRepository->getAll($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching sections of laws: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Get section of law by ID
     */
    public function getSectionOfLawById(int $id)
    {
        try {
            return $this->sectionOfLawRepository->getSectionOfLawById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching section of law: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create section of law
     */
    public function createSectionOfLaw(array $data)
    {
        try {
            return $this->sectionOfLawRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating section of law: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update section of law
     */
    public function updateSectionOfLaw(SectionOfLaw $sectionOfLaw, array $data)
    {
        try {
            return $this->sectionOfLawRepository->update($sectionOfLaw, $data);
        } catch (\Exception $e) {
            Log::error('Error updating section of law: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete section of law
     */
    public function deleteSectionOfLaw(SectionOfLaw $sectionOfLaw)
    {
        try {
            return $this->sectionOfLawRepository->delete($sectionOfLaw);
        } catch (\Exception $e) {
            Log::error('Error deleting section of law: ' . $e->getMessage());
            throw $e;
        }
    }
}
