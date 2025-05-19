@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background-color: #e7ecf8;">
    <div class="card shadow p-4" style="width: 100%; max-width: 500px; border-radius: 20px;">
        <div class="text-center mb-4">
            <img src="/images/image.png" alt="Logo" style="max-height: 80px;">
            <h3 class="mt-3">Create an Account</h3>
            <p class="text-muted">Fill in the form below to register</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        autofocus
                        placeholder="Enter your full name"
                    >
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="Enter your email"
                    >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input
                        id="password-confirm"
                        type="password"
                        class="form-control"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input
                        id="phone"
                        type="tel"
                        class="form-control @error('phone') is-invalid @enderror"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="+91 1234567890"
                    >
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea
                        id="address"
                        class="form-control @error('address') is-invalid @enderror"
                        name="address"
                        rows="2"
                        placeholder="Enter your address"
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold py-2">
                Create Account
            </button>

            <div class="text-center mt-3">
                <p class="mb-0">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-decoration-none">Sign In</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
