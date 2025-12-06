<?php

namespace App\Repositories;

use App\Interfaces\RegisterGradeInterface;
use App\Models\Areas\Region;
use App\Models\RegisterationGrades;
use Illuminate\Support\Facades\DB;

class RegisterGradeRepository implements RegisterGradeInterface
{
    /**
     * Get all regions with filters and pagination
     */
    public function getAll()
    {
        return RegisterationGrades::all();
    }

}
