@extends('layouts.app')

@section('site_title', formatTitle([__('Developers'), config('settings.title')]))

@section('head_content')
<link href="{{ asset('css/developer-docs.css') }}" rel="stylesheet">
<style>
    .bg-light-subtle {
        background-color: #f9fafb;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    }
    .nav-link {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection

@section('content')
    <div class="bg-base-1 flex-fill">
        <div class="container h-100 py-3 my-3">

            @include('shared.breadcrumbs', ['breadcrumbs' => [
                ['url' => route('home'), 'title' => __('Home')],
                ['title' => __('Developers')]
            ]])

            <h1 class="h2 mb-0 d-inline-block">{{ __('Developers') }}</h1>            {{-- Notes section that appears above every page --}}
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
            </div>            <div class="d-flex mt-3" style="gap: 1rem;">
                {{-- Sidebar Panel - Increased width --}}
                <div style="width: 250px; flex-shrink: 0;">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            @include('developers.sidebar')
                        </div>
                    </div>
                </div>
                
                {{-- Content Panel - Takes remaining space independently --}}
                <div style="flex: 1; min-width: 0;">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div id="developer-content">
                                {{-- Stats content --}}
                                <div class="content-section" id="stats-show">
                                    <div class="card border-0">
                                        <div class="card-header align-items-center bg-white border-bottom">
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

                                {{-- Websites List content --}}
                                <div class="content-section" id="websites-list">
                                    <div class="card border-0">
                                        <div class="card-header align-items-center bg-white border-bottom">
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

                                {{-- Websites Store content --}}
                                <div class="content-section" id="websites-store">
                                    <div class="card border-0">
                                        <div class="card-header align-items-center bg-white border-bottom">
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

                                {{-- Websites Update content --}}
                                <div class="content-section" id="websites-update">
                                    <div class="card border-0">
                                        <div class="card-header align-items-center bg-white border-bottom">
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
--data-urlencode 'domain=<span class="text-success">{domain}</span>' \
--data-urlencode 'privacy=<span class="text-success">{privacy}</span>' \
--data-urlencode 'password=<span class="text-success">{password}</span>'
</pre>

                                            @include('developers.websites.store-update', ['type' => 0])
                                        </div>
                                    </div>
                                </div>

                                {{-- Websites Delete content --}}
                                <div class="content-section" id="websites-delete">
                                    <div class="card border-0">
                                        <div class="card-header align-items-center bg-white border-bottom">
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
                                    <div class="card border-0">
                                        <div class="card-header align-items-center bg-white border-bottom">
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
                                </div>                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
