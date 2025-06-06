@extends('layouts.app')

@section('head_content')
    <link href="{{ asset('css/account-no-animations.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container h-100 py-3 my-3">

        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => __('Home')],
            ['title' => __('Account')]
        ]])        <h1 class="h2 mb-0 d-inline-block">{{ __('Account') }}</h1>        <div class="d-flex mt-3" style="gap: 1rem;">
            {{-- Sidebar Panel - Standard width --}}
            <div style="width: 250px; flex-shrink: 0;">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @include('account.sidebar')
                    </div>
                </div>
            </div>
            
            {{-- Content Panel - Takes remaining space independently --}}
            <div style="flex: 1; min-width: 0;">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @yield('account_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('shared.sidebars.user')
