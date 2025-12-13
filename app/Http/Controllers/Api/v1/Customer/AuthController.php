<?php

namespace App\Http\Controllers\Api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Lawyer\ChangePasswordRequest;
use App\Http\Requests\Api\Lawyer\ForgetPasswordRequest;
use App\Http\Requests\Api\Lawyer\LoginRequest;
use App\Http\Requests\Api\Lawyer\RegisterRequest;
use App\Http\Requests\Api\Lawyer\ResetPasswordRequest;
use App\Http\Requests\Api\Lawyer\UpdateProfileRequest;
use App\Http\Requests\Api\RegisterRequest as ApiRegisterRequest;
use App\Http\Resources\LawyerResource;
use App\Services\LawyerService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use Res;


    public function __construct(protected LawyerService $lawyerService)
    {
    }

    /**
     * Register a new lawyer
     */
    public function register(ApiRegisterRequest $request)
    {
        try {
            // Prepare data for lawyer creation
            $data = $request->validated();

            // Set active to 0 by default for new registrations (pending admin approval)
            $data['active'] = 0;

            // Create the lawyer
            $lawyer = $this->lawyerService->register($data);

            return $this->sendRes(
                __('auth.your register is success but the account is under review'),
                true,
                [],
                [],
                201
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    // /**
    //  * Login a  lawyer
    //  */
    // public function login(LoginRequest $request)
    // {
    //     try {
    //         // Prepare data for lawyer creation
    //         $data = $request->validated();
    //         $res = $this->lawyerService->login($data);
    //         return $this->sendRes(
    //             $res['message'],
    //             $res['status'],
    //             $res['data'],
    //             $res['errors'],
    //             200
    //         );
    //     } catch (\Exception $e) {
    //         return $this->sendRes($e->getMessage(), false, [], [], 500);
    //     }
    // }

    // public function profile(Request $request)
    // {
    //     try {
    //         // Get authenticated user from token
    //         $user = $request->user();
    //         // Get lawyer data with relations
    //         $lawyer = $this->lawyerService->getLawyerById($user->lawyer->id);
    //         // Return successful response with lawyer resource
    //         return $this->sendRes(
    //             __('validation.success'),
    //             true,
    //             new LawyerResource($lawyer),
    //             [],
    //             200
    //         );
    //     } catch (\Exception $e) {
    //         return $this->sendRes($e->getMessage(), false, [], [], 500);
    //     }
    // }

    // public function profile_update(UpdateProfileRequest $request)
    // {
    //     try {
    //         $data = $request->validated();
    //         $user = request()->user();
    //         $this->lawyerService->updateLawyer($user->lawyer, $data);
    //         // Return successful response with lawyer resource
    //         return $this->sendRes(
    //             __('common.success'),
    //             true,
    //             [],
    //             [],
    //             200
    //         );
    //     } catch (\Exception $e) {
    //         return $this->sendRes($e->getMessage(), false, [], [], 500);
    //     }
    // }

    // public function forgetPassword(ForgetPasswordRequest $request) {
    //     try {
    //         $res = $this->lawyerService->forgetPassword($request->all());
    //         // Return successful response with lawyer resource
    //         return $this->sendRes(
    //             $res['message'],
    //             $res['status'],
    //             $res['data'],
    //             [],
    //             200
    //         );
    //     } catch (\Exception $e) {
    //         return $this->sendRes($e->getMessage(), false, [], [], 500);
    //     }

    // }

    // public function resetPassword(ResetPasswordRequest $request) {
    //     try {
    //         $uuid = $request->uuid;
    //         $res = $this->lawyerService->resetPassword($request->all(), $uuid);
    //         if($res['status']) {
    //             return $this->sendRes(
    //                 $res['message'],
    //                 $res['status'],
    //                 $res['data'],
    //                 [],
    //                 200
    //             );
    //         } else {
    //             return $this->sendRes(
    //                 $res['message'],
    //                 $res['status'],
    //                 $res['data'],
    //                 [],
    //                 400
    //             );
    //         }
    //         // Return successful response with lawyer resource
    //     } catch (\Exception $e) {
    //         return $this->sendRes($e->getMessage(), false, [], [], 500);
    //     }

    // }

    // public function changePassword(ChangePasswordRequest $request) {
    //     try {
    //         // Get authenticated user from token
    //         $user = $request->user();
    //         $res = $this->lawyerService->changePassword($user, $request->all());
    //         if($res['status']) {
    //             return $this->sendRes(
    //                 $res['message'],
    //                 $res['status'],
    //                 $res['data'],
    //                 [],
    //                 200
    //             );
    //         } else {
    //             return $this->sendRes(
    //                 $res['message'],
    //                 $res['status'],
    //                 $res['data'],
    //                 [],
    //                 400
    //             );
    //         }
    //         // Return successful response with lawyer resource
    //     } catch (\Exception $e) {
    //         return $this->sendRes($e->getMessage(), false, [], [], 500);
    //     }

    // }

}
