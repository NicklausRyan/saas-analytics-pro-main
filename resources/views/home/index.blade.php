@extends('layouts.app')

@section('site_title', formatTitle([config('settings.title'), __(config('settings.tagline'))]))

@section('head_content')
    <link href="{{ asset('css/landing-page-fixes.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="flex-fill">        <!-- Hero Section -->
        <div class="bg-base-0 position-relative pt-10 pb-10">
            <div class="position-absolute top-0 left-0 right-0 bottom-0 z-0 opacity-10" style="background-image: url({{ asset('images/background.svg') }}); background-size: 800px; background-repeat: no-repeat; background-position: center center;"></div>

            <div class="container position-relative z-1">
                <div class="row align-items-center">                    <!-- Left side - Text and buttons -->
                    <div class="col-12 col-lg-6 text-left mb-5 mb-lg-0">
                        <h1 class="display-3 mb-4 font-weight-bold text-dark">
                            {{ __('Track your visitors') }}<br>
                            <span class="text-primary">{{ __('without invading privacy') }}</span>
                        </h1>

                        <p class="text-muted font-size-xl mb-5" style="max-width: 480px;">
                            {{ __('Get powerful insights about your website visitors with our GDPR-compliant analytics platform. No cookies, no tracking, just pure data.') }}
                        </p>

                        <div class="d-flex flex-column flex-sm-row align-items-start mb-6">
                            <a href="{{ config('settings.registration') ? route('register') : route('login') }}" class="btn btn-primary btn-lg px-5 py-3 font-size-lg font-weight-medium mb-3 mb-sm-0">
                                {{ __('Start Free Trial') }}
                                <span class="ml-2">→</span>
                            </a>
                            
                            @if(config('settings.demo_url'))
                                <a href="{{ config('settings.demo_url') }}" target="_blank" class="btn btn-outline-dark btn-lg px-5 py-3 font-size-lg ml-sm-3">
                                    {{ __('View Demo') }}
                                    @include('icons.external', ['class' => 'fill-current width-4 height-4 ml-2'])
                                </a>
                            @endif
                        </div>
                    </div>                    <!-- Right side - Hero Image -->
                    <div class="col-12 col-lg-6">
                        <div class="position-relative" style="aspect-ratio: 4/3; overflow: hidden; border-radius: 16px;">
                            <img src="{{ (config('settings.dark_mode') == 1 ? asset('images/hero_dark.png') : asset('images/hero.png')) }}" 
                                 class="img-fluid" 
                                 data-theme-dark="{{ asset('images/hero_dark.png') }}" 
                                 data-theme-light="{{ asset('images/hero.png') }}" 
                                 data-theme-target="src" 
                                 alt="{{ config('settings.title') }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section with Tabs -->
        <div class="bg-base-1 py-8">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-12 col-lg-8 text-center">
                        <h2 class="h1 mb-4 font-weight-bold">
                            {{ __('Everything you need to understand your audience') }}
                        </h2>
                        <p class="text-muted font-size-lg mb-5">
                            {{ __('Powerful analytics features that respect your visitors\' privacy while giving you the insights you need to grow.') }}
                        </p>
                    </div>
                </div>                <!-- Feature Tabs -->
                <div class="row justify-content-center mb-4">
                    <div class="col-12">
                        <ul class="nav nav-pills nav-fill nav-pills-rounded" id="feature-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="realtime-tab" data-toggle="pill" href="#realtime" role="tab" aria-controls="realtime" aria-selected="true">
                                    @include('icons.adjust', ['class' => 'fill-current width-4 height-4 mr-2'])
                                    {{ __('Real-time') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="behavior-tab" data-toggle="pill" href="#behavior" role="tab" aria-controls="behavior" aria-selected="false">
                                    @include('icons.web', ['class' => 'fill-current width-4 height-4 mr-2'])
                                    {{ __('Behavior') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="acquisition-tab" data-toggle="pill" href="#acquisition" role="tab" aria-controls="acquisition" aria-selected="false">
                                    @include('icons.acquisition', ['class' => 'fill-current width-4 height-4 mr-2'])
                                    {{ __('Acquisition') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="geographic-tab" data-toggle="pill" href="#geographic" role="tab" aria-controls="geographic" aria-selected="false">
                                    @include('icons.map', ['class' => 'fill-current width-4 height-4 mr-2'])
                                    {{ __('Geographic') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content mt-4" id="feature-tab-content">                    <!-- Real-time Tab -->
                    <div class="tab-pane fade show active" id="realtime" role="tabpanel" aria-labelledby="realtime-tab">
                        <div class="row align-items-start">
                            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                                <h3 class="h2 mb-4 font-weight-bold">{{ __('See what\'s happening right now') }}</h3>
                                <p class="text-muted font-size-lg mb-4">
                                    {{ __('Watch your visitors browse your website in real-time. Track page views, visitor locations, and traffic sources as they happen, giving you instant insights into your website performance.') }}
                                </p>
                                <ul class="list-unstyled">
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Live visitor count and activity') }}
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Real-time traffic sources') }}
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Current page popularity') }}
                                    </li>
                                </ul>
                            </div>                            <div class="col-12 col-lg-6">
                                <div class="position-relative" style="aspect-ratio: 4/3; overflow: hidden; border-radius: 12px;">
                                    <img src="{{ (config('settings.dark_mode') == 1 ? asset('images/hero_dark.png') : asset('images/hero.png')) }}" 
                                         class="img-fluid" 
                                         data-theme-dark="{{ asset('images/hero_dark.png') }}" 
                                         data-theme-light="{{ asset('images/hero.png') }}" 
                                         data-theme-target="src" 
                                         alt="{{ __('Real-time Analytics Dashboard') }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Behavior Tab -->
                    <div class="tab-pane fade" id="behavior" role="tabpanel" aria-labelledby="behavior-tab">
                        <div class="row align-items-start">
                            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                                <h3 class="h2 mb-4 font-weight-bold">{{ __('Understand user behavior') }}</h3>
                                <p class="text-muted font-size-lg mb-4">
                                    {{ __('Discover which pages perform best, track user engagement, and identify content that resonates with your audience. Optimize your website based on real user behavior data.') }}
                                </p>
                                <ul class="list-unstyled">
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Page performance analytics') }}
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('User journey tracking') }}
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Engagement metrics') }}
                                    </li>
                                </ul>
                            </div>                            <div class="col-12 col-lg-6">
                                <div class="position-relative" style="aspect-ratio: 4/3; overflow: hidden; border-radius: 12px;">
                                    <img src="{{ (config('settings.dark_mode') == 1 ? asset('images/hero_dark.png') : asset('images/hero.png')) }}" 
                                         class="img-fluid" 
                                         data-theme-dark="{{ asset('images/hero_dark.png') }}" 
                                         data-theme-light="{{ asset('images/hero.png') }}" 
                                         data-theme-target="src" 
                                         alt="{{ __('User Behavior Analytics') }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Acquisition Tab -->
                    <div class="tab-pane fade" id="acquisition" role="tabpanel" aria-labelledby="acquisition-tab">
                        <div class="row align-items-start">
                            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                                <h3 class="h2 mb-4 font-weight-bold">{{ __('Know where visitors come from') }}</h3>
                                <p class="text-muted font-size-lg mb-4">
                                    {{ __('Track your traffic sources and understand which channels drive the most valuable visitors. Optimize your marketing efforts with detailed acquisition data.') }}
                                </p>
                                <ul class="list-unstyled">
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Traffic source analysis') }}
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Campaign performance') }}
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Referrer tracking') }}
                                    </li>
                                </ul>
                            </div>                            <div class="col-12 col-lg-6">
                                <div class="position-relative" style="aspect-ratio: 4/3; overflow: hidden; border-radius: 12px;">
                                    <img src="{{ (config('settings.dark_mode') == 1 ? asset('images/hero_dark.png') : asset('images/hero.png')) }}" 
                                         class="img-fluid" 
                                         data-theme-dark="{{ asset('images/hero_dark.png') }}" 
                                         data-theme-light="{{ asset('images/hero.png') }}" 
                                         data-theme-target="src" 
                                         alt="{{ __('Traffic Acquisition Analytics') }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Geographic Tab -->
                    <div class="tab-pane fade" id="geographic" role="tabpanel" aria-labelledby="geographic-tab">
                        <div class="row align-items-start">
                            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                                <h3 class="h2 mb-4 font-weight-bold">{{ __('Global audience insights') }}</h3>
                                <p class="text-muted font-size-lg mb-4">
                                    {{ __('Understand your global reach with detailed geographic data. See where your visitors are located, down to the city level, and optimize your content for different regions.') }}
                                </p>
                                <ul class="list-unstyled">
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Country and city-level data') }}
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Timezone analysis') }}
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle mr-3" style="width: 8px; height: 8px;"></div>
                                        {{ __('Language preferences') }}
                                    </li>
                                </ul>
                            </div>                            <div class="col-12 col-lg-6">
                                <div class="position-relative" style="aspect-ratio: 4/3; overflow: hidden; border-radius: 12px;">
                                    <img src="{{ (config('settings.dark_mode') == 1 ? asset('images/hero_dark.png') : asset('images/hero.png')) }}" 
                                         class="img-fluid" 
                                         data-theme-dark="{{ asset('images/hero_dark.png') }}" 
                                         data-theme-light="{{ asset('images/hero.png') }}" 
                                         data-theme-target="src" 
                                         alt="{{ __('Geographic Analytics Dashboard') }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        <!-- Testimonials Section -->
        <div class="bg-base-0 py-8">
            <div class="container">
                <div class="row justify-content-center mb-6">
                    <div class="col-12 col-lg-8 text-center">
                        <div class="badge badge-pill badge-primary mb-3 px-4 py-2 font-size-sm">
                            {{ __('Customer Testimonials') }}
                        </div>
                        <h2 class="h1 mb-4 font-weight-bold">
                            {{ __('Trusted by thousands of businesses') }}
                        </h2>
                        <p class="text-muted font-size-lg">
                            {{ __('See what our customers say about their experience with our analytics platform.') }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-5">
                                <div class="d-flex mb-3">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="fill-current text-warning width-4 height-4 mr-1" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <blockquote class="mb-4">
                                    <p class="mb-0">"{{ __('Finally, an analytics tool that respects our users\' privacy while giving us all the insights we need. The real-time data is incredibly valuable for our team.') }}"</p>
                                </blockquote>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px;">
                                        <span class="text-white font-weight-bold">JD</span>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ __('John Doe') }}</div>
                                        <div class="small text-muted">{{ __('Marketing Director') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 mb-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-5">
                                <div class="d-flex mb-3">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="fill-current text-warning width-4 height-4 mr-1" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <blockquote class="mb-4">
                                    <p class="mb-0">"{{ __('The geographic insights have helped us understand our global audience better than ever before. Implementation was seamless and support is excellent.') }}"</p>
                                </blockquote>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px;">
                                        <span class="text-white font-weight-bold">SM</span>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ __('Sarah Miller') }}</div>
                                        <div class="small text-muted">{{ __('Product Manager') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 mb-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-5">
                                <div class="d-flex mb-3">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="fill-current text-warning width-4 height-4 mr-1" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <blockquote class="mb-4">
                                    <p class="mb-0">"{{ __('We switched from Google Analytics and couldn\'t be happier. Clean interface, accurate data, and complete GDPR compliance. Highly recommended!') }}"</p>
                                </blockquote>
                                <div class="d-flex align-items-center">
                                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px;">
                                        <span class="text-white font-weight-bold">MJ</span>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ __('Michael Johnson') }}</div>
                                        <div class="small text-muted">{{ __('CEO') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Section -->
        @if(paymentProcessors())            <div class="bg-base-1 py-8">
                <div class="container">
                    <div class="row justify-content-center mb-6">
                        <div class="col-12 col-lg-8 text-center">
                            <div class="badge badge-pill badge-primary mb-3 px-4 py-2 font-size-sm">
                                {{ __('Pricing Plans') }}
                            </div>
                            <h2 class="h1 mb-4 font-weight-bold">
                                {{ __('Simple, transparent pricing') }}
                            </h2>
                            <p class="text-muted font-size-lg">
                                {{ __('Choose the plan that fits your needs. All plans include our core privacy-focused analytics features.') }}
                            </p>
                        </div>
                    </div>

                    @include('shared.pricing')
                </div>
            </div>
        @else
            <div class="bg-base-1 py-8">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8 text-center">
                            <div class="bg-primary p-6 border-radius-lg text-white">
                                <h2 class="h1 mb-4 font-weight-bold text-white">
                                    {{ __('Ready to get started?') }}
                                </h2>
                                <p class="font-size-lg mb-5 opacity-90">
                                    {{ __('Join thousands of businesses using our privacy-focused analytics platform. Start tracking your visitors today.') }}
                                </p>
                                <a href="{{ config('settings.registration') ? route('register') : route('login') }}" class="btn btn-white btn-lg px-5 py-3 font-size-lg font-weight-medium">
                                    {{ __('Get Started Now') }}
                                    <span class="ml-2">→</span>
                                </a>
                                <div class="mt-4 small opacity-75">
                                    {{ __('No credit card required • 14-day free trial • Cancel anytime') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
      <!-- Removed inline styles as they are now in landing-page-fixes.css -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth transition between tabs
        const tabs = document.querySelectorAll('#feature-tabs .nav-link');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Add a slight delay to make the tab transition smoother
                setTimeout(() => {
                    const targetTab = document.querySelector(this.getAttribute('href'));
                    if (targetTab) {
                        targetTab.classList.add('show');
                    }
                }, 150);
            });
        });
    });
    </script>
@endsection
