@extends('layouts.auth')

@section('title', 'Log in')

@section('content')
    <div class="auth-page">
        <div class="auth-card">
            <!-- Logo -->
            <div class="auth-logo">
                <a href="{{ url('/') }}">
                    <h1><span class="logo-quiz">Quiz</span><span class="logo-lab">Lab</span></h1>
                </a>
            </div>


            <!-- Session Status -->
            <x-auth-session-status class="session-status" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="input-error" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="input-error" />
                </div>

                <!-- Remember Me -->
                <div class="form-remember">
                    <input id="remember_me" type="checkbox" class="checkbox-field" name="remember">
                    <label for="remember_me" class="checkbox-label">{{ __('Remember me') }}</label>
                </div>

                <!-- Links & Submit -->
                <div class="form-footer">
                    @if (Route::has('register'))
                        <a class="link-register" href="{{ route('register') }}">{{ __('Register?') }}</a>
                    @endif

                    <button type="submit" class="btn-primary">{{ __('Log in') }}</button>
                </div>
            </form>
        </div>

    </div>

    <!-- Footer -->
    <footer class="auth-footer">
        <p>&copy; {{ date('Y') }} QuizLab. Developed by Marouan.</p>
    </footer>
@endsection
