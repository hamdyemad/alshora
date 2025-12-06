<?php

namespace App\Actions;

use App\Interfaces\UserInterface;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Models\UserType;
use App\Traits\Res;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserAction {

    use Res;
    public function login($request) {
        $remember = $request->filled('remember');
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password], $remember)){
            return $this->sendData('',true);
        }else{
            return $this->sendData('',false);
        }
    }


    public function loginApi($data) {
        $user = User::where('email', $data['email'])->first();
        if($user) {
            if($user->user_type_id == UserType::LAWYER_TYPE) {
                if(!$user->lawyer->active) {
                    return $this->sendData(__('auth.your account is unactive'), false, [], [], 401);
                }
            } else if($user->user_type_id == UserType::CUSTOMER_TYPE) {
                if(!$user->customer->active) {
                    return $this->sendData(__('auth.your account is unactive'), false, [], [], 401);
                }
            }
            if($user->is_blocked == 1) {
                return $this->sendData(__('auth.the user is blocked'), false, [], [], 401);
            }
            if(!Hash::check($data['password'], $user->password)) {
                return $this->sendData(__('auth.password'), false, [], [], 401);
            }
            $token = $user->createToken('Login');
            $data = [
                'token' => $token->plainTextToken
            ];
            return $this->sendData(__('validation.success'), true, $data, [], 200);
        } else {
            return $this->sendData(__('auth.Email Not Exists'), false, [], [], 401);
        }
    }

    public function forgetPassword($data) {
        $user = User::where('email', $data['email'])->first();
        try {
            $reset_code = rand(100000, 900000);
            $data = [
                'email' => $user->email,
                'code' => $reset_code
            ];
            $user->reset_code = Hash::make($reset_code);
            $user->reset_code_timestamp = Carbon::now()->addMinutes(15);
            $user->save();
            Mail::to("$user->email")->send(new ResetPasswordMail($data));
        } catch(Exception $e) {
        }
        return $this->sendData(__('auth.reset password sent to your email please check it'),true, ['uuid' => $user->uuid]);
    }

    public function resetPassword($data, $uuid) {
        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            return $this->sendData(__('auth.Email Not Exists'),false);
        }
        if (!Hash::check($data['reset_code'], $user->reset_code)) {
            return $this->sendData(__('auth.The provided reset code is invalid.'),false);
        }
        if (Carbon::now()->gt($user->reset_code_timestamp)) {
            return $this->sendData(__('auth.reset code is ignored please reset again'),false);
        }
        $user->update([
            'password' => Hash::make($data['password']),
            'reset_code' => null,
            'reset_code_timestamp' => null,
        ]);
        $user->tokens()->delete();

        return $this->sendData(__('auth.your password changed success please login'),true);
    }

    public function changePassword($user, $data) {

        if (!Hash::check($data['old_password'], $user->password)) {
            return $this->sendData(__('auth.old password is invalid'),false);
        }
        $user->update([
            'password' => Hash::make($data['password']),
            'reset_code' => null,
            'reset_code_timestamp' => null,
        ]);
        return $this->sendData(__('auth.your password changed success please login'),true);
    }

    public function logout() {
        Auth::logout();
        return $this->sendData(__('auth.logout success'),true);

    }

}
