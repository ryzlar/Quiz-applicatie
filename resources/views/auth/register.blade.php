@extends('layouts.auth')

@section('title', 'Register')

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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="input-field" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="input-error" />
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="input-error" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="input-error" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="input-error" />
                </div>

                <!-- Links & Submit -->
                <div class="form-footer">
                    <a class="link-register" href="{{ route('login') }}">{{ __('Already registered?') }}</a>
                    <button type="submit" class="btn-primary">{{ __('Register') }}</button>
                </div>
            </form>
        </div>


    </div>

    <!-- Footer -->
    <footer class="auth-footer">
        <p>&copy; {{ date('Y') }} QuizLab. Developed by Marouan.</p>
    </footer>
@endsection
