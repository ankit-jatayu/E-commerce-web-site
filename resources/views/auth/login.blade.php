@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="limiter">
        <div class="container-login100" style="background-image: url('{{ asset('admin/login/images/bg-01.jpg') }}');">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    {{-- <span class="login100-form-logo">
                        <i class="zmdi zmdi-landscape"></i>
                    </span> --}}
                    <!-- <img src="{{ asset('admin/images/rl_logo_png.png') }}" class="login100-form-logo" > -->

                   <span class="login100-form-title p-b-34">
                        {{str_replace("_"," ",env('APP_NAME'))}}
                    </span> 
                    
                    @error('email')
                        <span style="color:red">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    @error('password')
                        <span style="color:red">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="wrap-input100 validate-input" data-validate = "Enter Email">
                       
                        <input id="email" type="email" class="input100" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email"  autofocus>
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>
                    


                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        {{-- <input class="input100" type="password" name="pass" placeholder="Password"> --}}
                        <input id="password" type="password" class="input100" name="password" placeholder="Password" required autocomplete="current-password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>
                    

                    {{-- <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">
                            Remember me
                        </label>
                    </div> --}}

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            Login
                        </button>
                    </div>

                    <div class="text-center p-t-90">
                        @if (Route::has('password.request'))
                            <a class="txt1" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection