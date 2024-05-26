{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
 --}}
@extends('layouts.app')

@section('content')
<div class="limiter">
        <div class="container-login100" style="background-image: url('{{ asset('admin/login/images/bg-01.jpg') }}');">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    {{-- <img src="{{ asset('admin/login/images/login-logo.png') }}" class="login100-form-logo" > --}}
                       <span class="login100-form-title p-b-34">
                        {{str_replace("_"," ",env('APP_NAME'))}}
                    </span>
                    @error('email')
                        <span style="color:red">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <div class="wrap-input100 validate-input" data-validate = "Enter Email">
                       
                        <input id="email"  class="input100" type="email" name="email" :value="old('email')" required autofocus >
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>
                    

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                             {{ __('Email Password Reset Link') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection