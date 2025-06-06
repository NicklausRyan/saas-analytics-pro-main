@extends('layouts.app')

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

        <div class="d-flex mt-3" style="gap: 1rem;">
            {{-- Sidebar Panel - Fixed width --}}
            <div style="width: 169px; flex-shrink: 0;">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @include('developers.sidebar')
                    </div>
                </div>
            </div>
            
            {{-- Content Panel - Takes remaining space --}}
            <div style="flex: 1;">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @yield('developers_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
