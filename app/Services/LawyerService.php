<?php

namespace App\Services;

use App\Actions\UserAction;
use App\Interfaces\LawyerRepositoryInterface;
use App\Models\Lawyer;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LawyerService
{
    public function __construct(
        public LawyerRepositoryInterface $lawyerRepositoryInterface,
        protected UserAction $userAction
    )
    {
    }
    /**
     * Get all lawyers with filters and pagination
     */
    public function getAllLawyers(array $filters = [], int $perPage = 10)
    {
        return $this->lawyerRepositoryInterface->getAllWithFilters($filters, $perPage);
    }

    /**
     * Create a new lawyer
     */
    public function createLawyer(array $data)
    {
        $lawyer = $this->lawyerRepositoryInterface->create($data);
        return $lawyer;
    }

    /**
     * Get lawyer by ID
     */
    public function getLawyerById($id)
    {
        $lawyer = $this->lawyerRepositoryInterface->findById($id);

        if (!$lawyer) {
            throw new \Exception(__('Lawyer not found'));
        }

        return $lawyer;
    }

    /**
     * Update lawyer
     */
    public function updateLawyer(Lawyer $lawyer, array $data)
    {
        $lawyer = $this->lawyerRepositoryInterface->update($lawyer,$data);
        return $lawyer;
    }

    /**
     * Delete lawyer
     */
    public function deleteLawyer(Lawyer $lawyer)
    {
        return $this->lawyerRepositoryInterface->delete($lawyer);
    }

    public function register($data) {
        $lawyer = $this->lawyerRepositoryInterface->create($data);
        return $lawyer;
    }

    public function login($data) {
        return  $this->userAction->loginApi($data);
    }

    public function forgetPassword($data) {
        return  $this->userAction->forgetPassword($data);
    }

    public function resetPassword($data, $uuid) {
        return  $this->userAction->resetPassword($data, $uuid);
    }
    public function changePassword($user, $data) {
        return  $this->userAction->changePassword($user, $data);
    }

    /**
     * Update office hours for a lawyer
     */
    public function updateOfficeHours(Lawyer $lawyer, array $officeHoursData)
    {
        return $this->lawyerRepositoryInterface->updateOfficeHours($lawyer, $officeHoursData);
    }

    /**
     * Get featured lawyers by is_featured flag
     */
    public function getFeaturedLawyers(int $limit = 10)
    {
        return $this->lawyerRepositoryInterface->getFeaturedByRating($limit);
    }

    /**
     * Toggle featured status for a lawyer
     */
    public function toggleFeatured(Lawyer $lawyer): Lawyer
    {
        $lawyer->is_featured = !$lawyer->is_featured;
        $lawyer->save();
        return $lawyer;
    }
}
