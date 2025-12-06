<?php

namespace App\Services;

use App\Interfaces\ActivityRepositoryInterface;
use App\Interfaces\RegisterGradeInterface;
use Illuminate\Support\Facades\Log;

class RegisterGradeService
{

    public function __construct(protected RegisterGradeInterface $registerGradeInteface)
    {
    }

    /**
     * Get all activities with filters and pagination
     */
    public function getAll()
    {
        try {
            return $this->registerGradeInteface->getAll();
        } catch (\Exception $e) {
            Log::error('Error fetching activities: ' . $e->getMessage());
            throw $e;
        }
    }

  
}
