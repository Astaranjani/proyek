@extends('layouts.app')

@section('content')
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('{{ asset('images/bg1.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }

    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .register-card {
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 30px;
        border-radius: 12px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
    }

    .register-card h3 {
        margin-bottom: 20px;
        font-size: 24px;
        text-align: center;
    }

    .register-card input[type="text"],
    .register-card input[type="email"],
    .register-card input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: none;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 14px;
    }

    .register-card input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .register-card button {
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

    .register-card button:hover {
        background-color: #f0f0f0;
    }

    .invalid-feedback {
        color: #ff6b6b;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 10px;
        display: block;
    }
</style>

<div class="register-container">
    <div class="register-card">
        <h3>{{ __('Register') }}</h3>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <input id="name" type="text" name="name" placeholder="Name" required
                    class="@error('name') is-invalid @enderror">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
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
            <div>
                <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm Password" required>
            </div>
            <button type="submit">{{ __('Register') }}</button>
        </form>
    </div>
</div>
@endsection
