@extends('layouts.auth')

@section('site_title', formatTitle([__('Register'), config('settings.title')]))

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
                                    <h1 class="h2 font-weight-bold mb-3">{{ __('Register') }}</h1>
                                    <p class="font-weight-normal font-size-lg mb-0">{{ __('Join us.') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right panel - Registration form -->
                        <div class="col-12 col-lg-6">
                            <div class="card-body p-4 p-lg-5">
                                <form method="POST" action="{{ route('register') }}" id="registration-form">
                                    @csrf

                                    <div class="form-group">
                                        <label for="i-name">{{ __('Name') }}</label>
                                        <input id="i-name" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="i-email">{{ __('Email address') }}</label>
                                        <input id="i-email" type="text" dir="ltr" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">
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

                                    <div class="form-group">
                                        <label for="i-password-confirmation">{{ __('Confirm password') }}</label>
                                        <input id="i-password-confirmation" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input{{ $errors->has('agreement') ? ' is-invalid' : '' }}" name="agreement" id="i-agreement">
                                            <label class="custom-control-label" for="i-agreement">{!! __('I agree to the :terms and :privacy.', ['terms' => mb_strtolower('<a href="'.config('settings.legal_terms_url').'" target="_blank">'. __('Terms of service').'</a>'), 'privacy' => mb_strtolower('<a href="'.config('settings.legal_privacy_url').'" target="_blank">'. __('Privacy policy') .'</a>')]) !!}</label>
                                            @if ($errors->has('agreement'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('agreement') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if(config('settings.captcha_registration'))
                                        {!! NoCaptcha::displaySubmit('registration-form', __('Register'), ['data-theme' => (config('settings.dark_mode') == 1 ? 'dark' : 'light'), 'data-size' => 'invisible', 'class' => 'btn btn-block btn-primary py-2']) !!}

                                        {!! NoCaptcha::renderJs(__('lang_code')) !!}
                                    @else
                                        <button type="submit" class="btn btn-block btn-primary py-2">
                                            {{ __('Register') }}
                                        </button>
                                    @endif

                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif                                </form>                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
