@extends('layouts.app')

@section('content')
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('{{ asset('images/bg1.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .login-card {
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 30px;
        border-radius: 12px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
    }

    .login-card h3 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    .login-card input[type="email"],
    .login-card input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: none;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 14px;
    }

    .login-card input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .login-card .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .login-card .form-check input[type="checkbox"] {
        margin-right: 10px;
    }

    .login-card button {
        width: 100%;
        padding: 12px;
        background-color: #ffffff;
        color: #000000;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .login-card button:hover {
        background-color: #f0f0f0;
    }

    .register-link {
        color: #ffffff;
        display: block;
        text-align: center;
        margin-top: 15px;
        text-decoration: none;
        font-size: 14px;
    }

    .register-link:hover {
        text-decoration: underline;
    }

    .invalid-feedback {
        color: #ff6b6b;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 10px;
        display: block;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <h3>{{ __('Login') }}</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <input id="email" type="email" name="email" placeholder="Email" required
                    class="@error('email') is-invalid @enderror">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
                <input id="password" type="password" name="password" placeholder="Password" required
                    class="@error('password') is-invalid @enderror">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">{{ __('Remember Me') }}</label>
            </div>
            <button type="submit">{{ __('Login') }}</button>
            <a href="{{ route('register') }}" class="register-link">{{ __("Don't have an account? Register here") }}</a>
        </form>
    </div>
</div>
@endsection
