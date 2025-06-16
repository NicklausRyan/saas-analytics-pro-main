@extends('layouts.auth')

@section('site_title', formatTitle([Str::ucfirst(mb_strtolower(__('Reset Password'))), config('settings.title')]))

@section('head_content')

@endsection

@section('content')
    <div class="bg-base-1 d-flex align-items-start align-items-lg-center flex-fill">
        <div class="container h-100 py-6">

            <div class="text-center d-block d-lg-none">
                <h1 class="h2 mb-3 d-inline-block">{{ Str::ucfirst(mb_strtolower(__('Reset Password'))) }}</h1>
                <div class="m-auto">
                    <p class="text-muted font-weight-normal font-size-lg mb-0">{{ __('Get back your account.') }}</p>
                </div>
            </div>

            <div class="row h-100 justify-content-center align-items-center mt-5 mt-lg-0">
                <div class="col-12">                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="row no-gutters">                            <!-- Left panel - Welcome message -->
                            <div class="col-12 col-lg-6 bg-primary d-none d-lg-flex flex-fill">
                                <div class="card-body p-lg-5 d-flex flex-column justify-content-center align-items-center text-white">
                                    <div>
                                        <h1 class="h2 font-weight-bold mb-3">{{ Str::ucfirst(mb_strtolower(__('Reset Password'))) }}</h1>
                                        <p class="font-weight-normal font-size-lg mb-0">{{ __('Get back your account.') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right panel - Form -->
                            <div class="col-12 col-lg-6">
                                <div class="card-body p-4 p-lg-5">
                                    @include('shared.message')

                                    @if (request()->session()->get('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ request()->session()->get('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('password.email') }}">
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

                                        <button type="submit" class="btn btn-block btn-primary">
                                            {{ __('Send Password Reset Link') }}
                                        </button>                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
