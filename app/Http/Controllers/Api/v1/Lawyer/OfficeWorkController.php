<?php

namespace App\Http\Controllers\Api\v1\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateOfficeWorkRequest;
use App\Http\Resources\LawyerResource;
use App\Services\LawyerService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OfficeWorkController extends Controller
{
    use Res;


    public function __construct(protected LawyerService $lawyerService)
    {
    }

    /**
     * Register a new lawyer
     */
    public function updateOfficeWork(UpdateOfficeWorkRequest $request)
    {
        try {
            $lawyer = request()->user()->lawyer;
            // Prepare data for lawyer creation
            $data = $request->validated();

            $officeHoursData = [
                $data['day'] => [
                    $data['period'] => [
                        'from_time' => $data['from_time'],
                        'to_time' => $data['to_time'],
                        'is_available' => $data['is_available'],
                    ]
                ]
            ];
            $lawyer = $this->lawyerService->updateOfficeHours($lawyer,$officeHoursData);

            return $this->sendRes(
                __('validation.success'),
                true,
                [],
                [],
                201
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }


}
