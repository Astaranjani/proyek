@extends('layouts.app')

@section('content')
<style>
    body {
        background: url('{{ asset('images/bg1.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        backdrop-filter: blur(5px);
    }
    .login-card {
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }
    .login-card input {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
    }
    .login-card input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    .login-card .btn-primary {
        background-color: #fff;
        color: black;
        border: none;
        width: 100%;
    }
    .login-card .register-link {
        color: white;
        text-decoration: none;
        display: block;
        margin-top: 10px;
    }
    .login-card .register-link:hover {
        text-decoration: underline;
    }
</style>

<div class="login-container">
    <div class="col-md-4">
        <div class="login-card text-center">
            <h3>{{ __('Login') }}</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                <a href="{{ route('register') }}" class="register-link">{{ __('Don\'t have an account? Register here') }}</a>
            </form>
        </div>
    </div>
</div>
@endsection