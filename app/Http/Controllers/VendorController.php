<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\Request;
use Auth;
use App\Services\UserService;

class VendorController extends Controller {

    public function index() {
        return view('pages.vendors.index');
    }

    public function create() {
        return view('pages.vendors.form');
    }

}
