<?php

namespace App\Repositories;

use App\Actions\UserAction;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserInterface {

    public function __construct(public UserAction $userAction)
    {

    }

    public function login($request) {
        return $this->userAction->login($request);
    }

    public function forgetPassword($request) {
        return $this->userAction->forgetPassword($request);
    }

    public function resetPassword($request, $user) {
        return $this->userAction->resetPassword($request, $user);
    }

    public function logout() {
        return $this->userAction->logout();
    }


}
