@extends('layouts.app')

@section('content')
<style>
    body {
        background: url('{{ asset('images/bg1.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }
    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        backdrop-filter: blur(5px);
    }
    .register-card {
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }
    .register-card input {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
    }
    .register-card input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    .register-card .btn-primary {
        background-color: #fff;
        color: black;
        border: none;
        width: 100%;
    }
</style>

<div class="register-container">
    <div class="col-md-4">
        <div class="register-card text-center">
            <h3>{{ __('Register') }}</h3>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
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
                <div class="mb-3">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
