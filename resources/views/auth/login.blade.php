@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background-color: #e7ecf8;">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px; border-radius: 20px;">
        <div class="text-center mb-4">
            <img src="/images/image.png" alt="Logo" style="max-height: 100px;">
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                    placeholder="Username@gmail.com"
                >
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">Login</button>
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('register') }}" class="text-decoration-none">Create an account</a>
                <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>
@endsection
