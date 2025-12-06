<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\UserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\ForgetPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\LawyerResource;
use App\Models\UserType;
use App\Services\CustomerService;
use App\Services\LawyerService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use Res;


    public function __construct(
        protected LawyerService $lawyerService,
        protected CustomerService $customerService,
        protected UserAction $userAction
        )
    {
    }

    /**
     * Register a new lawyer or customer
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Prepare data for registration
            $data = $request->validated();
            if($request->request_type == 'lawyer') {
                // Set active to 0 by default for new registrations (pending admin approval)
                $data['active'] = 0;
                // Create the lawyer
                $lawyer = $this->lawyerService->register($data);
                $message = __('auth.your register is success but the account is under review');
            } else {
                $data['active'] = 1;
                // Create the customer
                $customer = $this->customerService->register($data);
                $message = __('auth.registered success');
            }

            return $this->sendRes(
                $message,
                true,
                [],
                [],
                201
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        try {
            // Prepare data for lawyer creation
            $data = $request->validated();

            $res = $this->userAction->loginApi($data);
            return $this->sendRes(
                $res['message'],
                $res['status'],
                $res['data'],
                $res['errors'],
                200
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = $request->user();

            if($user->user_type_id == UserType::LAWYER_TYPE) {
                // Get lawyer data with relations
                $profile = $this->lawyerService->getLawyerById($user->lawyer->id);
                // Return successful response with lawyer resource
                $profile = new LawyerResource($profile);

            } else if($user->user_type_id == UserType::CUSTOMER_TYPE) {
                // Get customer data with relations
                $profile = $this->customerService->getCustomerById($user->customer->id);
                $profile = new CustomerResource($profile);

            }
            return $this->sendRes(
                    __('validation.success'),
                    true,
                    $profile,
                    [],
                    200
                );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function profile_update(UpdateProfileRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $request->user();
            $data['active'] = 1;
            // Update profile based on user type
            if($user->user_type_id == UserType::LAWYER_TYPE) {
                $this->lawyerService->updateLawyer($user->lawyer, $data);
            } else if($user->user_type_id == UserType::CUSTOMER_TYPE) {
                $this->customerService->updateCustomer($user->customer, $data);
            } else {
                return $this->sendRes(
                    __('auth.invalid_user_type'),
                    false,
                    [],
                    [],
                    400
                );
            }

            // Return successful response
            return $this->sendRes(
                __('common.success'),
                true,
                [],
                [],
                200
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function forgetPassword(ForgetPasswordRequest $request) {
        try {
            $res = $this->lawyerService->forgetPassword($request->all());
            // Return successful response with lawyer resource
            return $this->sendRes(
                $res['message'],
                $res['status'],
                $res['data'],
                [],
                200
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }

    }

    public function resetPassword(ResetPasswordRequest $request) {
        try {
            $uuid = $request->uuid;
            $res = $this->lawyerService->resetPassword($request->all(), $uuid);
            if($res['status']) {
                return $this->sendRes(
                    $res['message'],
                    $res['status'],
                    $res['data'],
                    [],
                    200
                );
            } else {
                return $this->sendRes(
                    $res['message'],
                    $res['status'],
                    $res['data'],
                    [],
                    400
                );
            }
            // Return successful response with lawyer resource
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }

    }

    public function changePassword(ChangePasswordRequest $request) {
        try {
            // Get authenticated user from token
            $user = $request->user();
            $res = $this->userAction->changePassword($user, $request->all());
            if($res['status']) {
                $code = 200;
            } else {
                $code = 400;
            }
            return $this->sendRes(
                $res['message'],
                $res['status'],
                $res['data'],
                [],
                $code
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

}
