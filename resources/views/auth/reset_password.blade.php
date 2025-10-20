@extends('auth.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
            <div class="edit-profile">
                <div class="edit-profile__logos">
                    <img class="dark" src="{{ asset('assets/img/logo-dark.png') }}" alt="">
                    <img class="light" src="{{ asset('assets/img/logo-white.png') }}" alt="">
                </div>
                <div class="card border-0">
                    <div class="card-header">
                        <div class="edit-profile__title">
                            <h6>Reset Password</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="edit-profile__body">
                            <p>Enter the email address and reset code you used when you joined and we’ll send you instructions to reset
                                your password. (please check your email)</p>
                            <form action="{{ route('forgetPassword.reset-store', $user) }}" method="POST">
                                @csrf
                                <div class="form-group mb-20">
                                    <label for="reset_code">Reset Code</label>
                                    <input type="text" class="form-control" id="reset_code" name="reset_code" value="{{ old('reset_code') }}" placeholder="123456">
                                    @error('reset_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-15">
                                    <label for="password-field">password</label>
                                    <div class="position-relative">
                                        <input id="password-field" type="password" class="form-control" name="password"
                                            placeholder="Password">
                                        <span toggle="#password-field"
                                            class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                    </div>
                                    @error('password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-15">
                                    <label for="password-field2">Password Confirmed</label>
                                    <div class="position-relative">
                                        <input id="password-field2" type="password" class="form-control" name="password_confirmation"
                                            placeholder="Password">
                                        <span toggle="#password-field2"
                                            class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                    </div>
                                    @error('password_confirmation')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="d-flex">
                                    <button type="submit"
                                        class="btn btn-primary btn-default btn-squared text-capitalize lh-normal px-md-50 py-15 signIn-createBtn">
                                        Send Reset Instructions
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
