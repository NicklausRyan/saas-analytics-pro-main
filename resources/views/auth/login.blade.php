@extends('layouts.auth')

@section('site_title', formatTitle([__('Login'), config('settings.title')]))

@section('head_content')

@endsection

@section('content')
<div class="bg-base-1 d-flex align-items-center justify-content-center flex-fill">
    <div class="container py-6">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="row no-gutters">                        <!-- Left panel - Welcome message -->
                        <div class="col-12 col-lg-6 bg-primary d-none d-lg-flex flex-fill">
                            <div class="card-body p-lg-5 d-flex flex-column justify-content-center align-items-center text-white">
                                <div>
                                    <h1 class="h2 font-weight-bold mb-3">{{ __('Login') }}</h1>
                                    <p class="font-weight-normal font-size-lg mb-0">{{ __('Welcome back.') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right panel - Login form -->
                        <div class="col-12 col-lg-6">
                            <div class="card-body p-4 p-lg-5">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="i-email">{{ __('Email address') }}</label>
                                        <input id="i-email" type="text" dir="ltr" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="i-password">{{ __('Password') }}</label>
                                        <input id="i-password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-6">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="remember" id="i-remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="custom-control-label" for="i-remember">
                                                    {{ __('Remember me') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}">
                                                    {{ __('Forgot password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-block btn-primary py-2">
                                        {{ __('Login') }}
                                    </button>
                                </form>                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
