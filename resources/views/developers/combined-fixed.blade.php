@extends('layouts.app')

@section('site_title', formatTitle([__('Developers'), config('settings.title')]))

@section('head_content')
<link href="{{ asset('css/developer-docs.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="bg-base-1 flex-fill">
        <div class="container h-100 py-3 my-3">

            @include('shared.breadcrumbs', ['breadcrumbs' => [
                ['url' => route('home'), 'title' => __('Home')],
                ['title' => __('Developers')]
            ]])

            <h1 class="h2 mb-0 d-inline-block">{{ __('Developers') }}</h1>

            {{-- Notes section that appears above every page --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header align-items-center">
                    <div class="row">
                        <div class="col">
                            <div class="font-weight-medium py-1">{{ __('Notes') }}</div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <div class="badge badge-danger">{{ __('Expert level') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{ __('The API key should be sent as a Bearer token in the Authorization header of the request.') }} <a href="{{ route('account.api') }}">{{ __('Get your API key') }}</a>.
                </div>
            </div>

            <div class="developer-wrapper mt-3">
                {{-- Sidebar navigation --}}
                <div class="developer-sidebar">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <ul class="nav flex-column" id="developerTabs">
                                {{-- Stats section --}}
                                <li class="nav-item" data-category="stats">
                                    <a class="nav-link d-flex align-items-center category-header">
                                        <span>@include('icons.bar-chart', ['class' => 'fill-current width-4 height-4 mr-2']) {{ __('Stats') }}</span>
                                    </a>
                                    <div class="sub-nav">
                                        <a class="nav-link" href="#" data-section="stats-show">{{ __('Show') }}</a>
                                    </div>
                                </li>
                                
                                {{-- Websites section --}}
                                <li class="nav-item" data-category="websites">
                                    <a class="nav-link d-flex align-items-center category-header">
                                        <span>@include('icons.website', ['class' => 'fill-current width-4 height-4 mr-2']) {{ __('Websites') }}</span>
                                    </a>
                                    <div class="sub-nav">
                                        <a class="nav-link" href="#" data-section="websites-list">{{ __('List') }}</a>
                                        <a class="nav-link" href="#" data-section="websites-store">{{ __('Store') }}</a>
                                        <a class="nav-link" href="#" data-section="websites-update">{{ __('Update') }}</a>
                                        <a class="nav-link" href="#" data-section="websites-delete">{{ __('Delete') }}</a>
                                    </div>
                                </li>
                                
                                {{-- Account section --}}
                                <li class="nav-item" data-category="account">
                                    <a class="nav-link d-flex align-items-center category-header">
                                        <span>@include('icons.portrait', ['class' => 'fill-current width-4 height-4 mr-2']) {{ __('Account') }}</span>
                                    </a>
                                    <div class="sub-nav">
                                        <a class="nav-link" href="#" data-section="account-show">{{ __('Show') }}</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Content area --}}
                <div class="developer-content">
                    {{-- Stats content --}}
                    <div class="content-section" id="stats-show">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Stats') }} - {{ __('Show') }}</div></div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-1">
                                    {{ __('API endpoint') }}:
                                </p>

                                <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                                <span class="badge bg-light text-success px-2 py-1 mr-3">GET</span>
                                <pre class="m-0 text-light">{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.stats.show', ['id' => ':id'])) !!}</pre>
                                </div>

                                <p class="mb-1">
                                    {{ __('Request example') }}:
                                </p>
                                <pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request GET '{!! str_replace([':id', '%3Aname', '%3Afrom', '%3Ato'], ['<span class="text-success">{id}</span>', '<span class="text-success">{name}</span>', '<span class="text-success">{from}</span>', '<span class="text-success">{to}</span>'], route('api.stats.show', ['id' => ':id', 'name' => ':name', 'from' => ':from', 'to' => ':to'])) !!}' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>
                                @include('developers.stats.list', ['type' => 0])
                            </div>
                        </div>
                    </div>

                    {{-- Websites content --}}
                    {{-- List section --}}
                    <div class="content-section" id="websites-list">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Websites') }} - {{ __('List') }}</div></div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-1">
                                    {{ __('API endpoint') }}:
                                </p>

                                <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                                    <span class="badge bg-light text-success px-2 py-1 mr-3">GET</span>
                                    <pre class="m-0 text-light">{{ route('api.websites.index') }}</pre>
                                </div>

                                <p class="mb-1">
                                    {{ __('Request example') }}:
                                </p>
                                <pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request GET '{{ route('api.websites.index') }}' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>

                                @include('developers.websites.list', ['type' => 0])
                            </div>
                        </div>
                    </div>

                    {{-- Store section --}}
                    <div class="content-section" id="websites-store">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Websites') }} - {{ __('Store') }}</div></div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-1">
                                    {{ __('API endpoint') }}:
                                </p>

                                <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                                    <span class="badge bg-light text-danger px-2 py-1 mr-3">POST</span>
                                    <pre class="m-0 text-light">{{ route('api.websites.store') }}</pre>
                                </div>

                                <p class="mb-1">
                                    {{ __('Request example') }}:
                                </p>
                                <pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request POST '{{ route('api.websites.store') }}' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>' \
--data-urlencode 'domain=<span class="text-success">{domain}</span>' \
--data-urlencode 'privacy=<span class="text-success">{privacy}</span>' \
--data-urlencode 'password=<span class="text-success">{password}</span>'
</pre>

                                @include('developers.websites.store-update', ['type' => 1])
                            </div>
                        </div>
                    </div>

                    {{-- Update section --}}
                    <div class="content-section" id="websites-update">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Websites') }} - {{ __('Update') }}</div></div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-1">
                                    {{ __('API endpoint') }}:
                                </p>

                                <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                                    <span class="badge bg-light text-warning px-2 py-1 mr-3">PUT</span>
                                    <pre class="m-0 text-light">{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.websites.update', ['id' => ':id'])) !!}</pre>
                                </div>

                                <p class="mb-1">
                                    {{ __('Request example') }}:
                                </p>
                                <pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request PUT '{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.websites.update', ['id' => ':id'])) !!}' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>' \
--data-urlencode 'privacy=<span class="text-success">{privacy}</span>' \
--data-urlencode 'password=<span class="text-success">{password}</span>'
</pre>

                                @include('developers.websites.store-update', ['type' => 0])
                            </div>
                        </div>
                    </div>

                    {{-- Delete section --}}
                    <div class="content-section" id="websites-delete">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Websites') }} - {{ __('Delete') }}</div></div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-1">
                                    {{ __('API endpoint') }}:
                                </p>

                                <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                                    <span class="badge bg-light text-danger px-2 py-1 mr-3">DELETE</span>
                                    <pre class="m-0 text-light">{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.websites.destroy', ['id' => ':id'])) !!}</pre>
                                </div>

                                <p class="mb-1">
                                    {{ __('Request example') }}:
                                </p>
                                <pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request DELETE '{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.websites.destroy', ['id' => ':id'])) !!}' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>
                            </div>
                        </div>
                    </div>

                    {{-- Account content --}}
                    <div class="content-section" id="account-show">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Account') }} - {{ __('Show') }}</div></div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-1">
                                    {{ __('API endpoint') }}:
                                </p>

                                <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                                    <span class="badge bg-light text-success px-2 py-1 mr-3">GET</span>
                                    <pre class="m-0 text-light">{{ route('api.account.index') }}</pre>
                                </div>

                                <p class="mb-1">
                                    {{ __('Request example') }}:
                                </p>
                                <pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request GET '{{ route('api.account.index') }}' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>
                                @include('developers.account.content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide all content sections initially
            document.querySelectorAll('.content-section').forEach(function(section) {
                section.style.display = 'none';
            });

            // Show the stats-show section by default
            if(document.getElementById('stats-show')) {
                document.getElementById('stats-show').style.display = 'block';
                
                // Mark the stats-show link as active
                document.querySelector('[data-section="stats-show"]').classList.add('active');
            }

            // Handle sidebar link clicks
            document.querySelectorAll('.nav-link[data-section]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Hide all content sections
                    document.querySelectorAll('.content-section').forEach(function(section) {
                        section.style.display = 'none';
                    });

                    // Show the selected section
                    const sectionId = this.getAttribute('data-section');
                    document.getElementById(sectionId).style.display = 'block';

                    // Update active state in sidebar
                    document.querySelectorAll('.nav-link[data-section]').forEach(function(navLink) {
                        navLink.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        });
    </script>
@endsection
