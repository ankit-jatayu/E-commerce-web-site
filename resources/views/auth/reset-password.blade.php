{{-- <x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
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
                <form class="login100-form validate-form" method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                     <img src="{{ asset('admin/images/rl_logo_png.png') }}" class="login100-form-logo" >
                     {{--  <span class="login100-form-title p-b-34">
                        {{str_replace("_"," ",env('APP_NAME'))}}
                    </span> --}}
                    
                    @error('email')
                        <span style="color:red">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="wrap-input100 validate-input" data-validate = "Enter Email">
                        <input id="email"  class="input100" type="email" name="email" value="{{old('email', $request->email)}}" required autofocus >
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input id="password" type="password" class="input100" name="password" placeholder="Password" required autocomplete="current-password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" style="color:red"/>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input id="password_confirmation" type="password" class="input100" name="password_confirmation" placeholder="Confirm Password" required >
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                         <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color:red"/>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            {{ __('Reset Password') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection