@extends('auth.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
            <div class="edit-profile">
                <div class="card border-0">
                    <div class="edit-profile__title text-center mt-3">
                        <img class="rounded-circle w-25" src="{{ asset('assets/img/logo.png') }}" alt="">
                        <h6 class="mt-2">Forgot Password?</h6>
                    </div>
                    <div class="card-body">
                        <div class="edit-profile__body">
                            <p>Enter the email address you used when you joined and we’ll send you instructions to reset
                                your password.</p>
                            <form action="{{ route('forgetPassword.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-20">
                                    <label for="email">Email Adress</label>
                                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <button type="submit"
                                        class="btn btn-primary btn-default btn-squared text-capitalize lh-normal px-md-50 py-15 signIn-createBtn">
                                        Send Reset Instructions
                                    </button>
                                    <a href="{{ route('login') }}">Back to Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
