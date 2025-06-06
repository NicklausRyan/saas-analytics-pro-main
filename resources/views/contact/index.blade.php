@extends('layouts.app')

@section('site_title', formatTitle([__('Contact'), config('settings.title')]))

@section('head_content')
<style>
    /* Custom styles for contact form */
    #contact-form .form-group {
        margin-bottom: 0.85rem;
    }
    #contact-form .form-control {
        font-size: 0.9rem;
    }
    #contact-form textarea.form-control {
        resize: vertical;
        min-height: 60px;
        max-height: 120px;
    }
    #contact-form label {
        font-size: 0.9rem;
    }
    #contact-form .btn {
        font-size: 0.9rem;
        padding: 0.35rem 1rem;
    }
    @media (max-width: 767.98px) {
        .col-md-5.col-lg-4.mx-auto {
            max-width: 85%;
        }
    }
</style>
@endsection

@section('content')    <div class="bg-base-1 d-flex align-items-center flex-fill">        <div class="container h-100 py-6">

            <div class="text-center">
                <h1 class="h2 mb-3 d-inline-block">{{ __('Contact') }}</h1>
            </div>            <div class="row h-100 justify-content-center align-items-center mt-4">
                <div class="col-12 col-md-5 col-lg-4 mx-auto"><div class="card border-0 shadow-sm overflow-hidden">
                        <div class="row no-gutters">
                            <div class="col-12">
                                <div class="card-body p-3">
                                    @include('shared.message')

                                    <form method="POST" action="{{ route('contact') }}" id="contact-form">
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
                                            <label for="i-subject">{{ __('Subject') }}</label>
                                            <input id="i-subject" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="subject" value="{{ old('subject') }}" autofocus>
                                            @if ($errors->has('subject'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subject') }}</strong>
                                                </span>
                                            @endif
                                        </div>                                        <div class="form-group">
                                            <label for="i-message">{{ __('Message') }}</label>
                                            <textarea name="message" id="i-message" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" rows="2">{{ old('message') }}</textarea>
                                            @if ($errors->has('message'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('message') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        @if(config('settings.captcha_contact'))
                                            {!! NoCaptcha::displaySubmit('contact-form', __('Send'), ['data-theme' => (config('settings.dark_mode') == 1 ? 'dark' : 'light'), 'data-size' => 'invisible', 'class' => 'btn btn-primary']) !!}

                                            {!! NoCaptcha::renderJs(__('lang_code')) !!}                                        @else
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Send') }}
                                            </button>
                                        @endif

                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                        @endif
                                    </form>
                                </div>                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('shared.sidebars.user')
