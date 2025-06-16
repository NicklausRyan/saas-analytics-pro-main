@extends('layouts.app')

@section('site_title', formatTitle([__('Pricing'), config('settings.title')]))

@section('head_content')

@endsection

@section('content')
    <div class="flex-fill">
        <div class="bg-base-1">
            <div class="container py-6">
                @include('shared.message')                <div class="text-center">
                    <h1 class="h2 d-inline-block font-weight-bold mb-3">{{ __('Pricing') }}</h1>
                    <div class="m-auto">
                        <p class="text-muted font-weight-normal mb-0">{{ __('Simple pricing plans for everyone and every budget.') }}</p>
                    </div>
                </div>@include('shared.pricing')
            </div>
        </div>
    </div>
@endsection
