<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100 scroll-behavior-smooth {{ (config('settings.dark_mode') == 1 ? 'dark' : '') }}" dir="{{ (__('lang_dir') == 'rtl' ? 'rtl' : 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('site_title')
            @yield('site_title')
        @else
            @if(request()->segment(1) == 'admin')
                {{ formatTitle([__('Admin'), config('settings.title')]) }}
            @else
                {{ config('settings.title') }}
            @endif
        @endif
    </title>

    @yield('head_content')

    <link href="{{ url('/') }}/uploads/brand/{{ config('settings.favicon') ?? 'favicon.png' }}" rel="icon">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>    <!-- Styles -->
    <link href="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . (config('settings.dark_mode') == 1 ? '.dark' : '').'.css') }}" rel="stylesheet" data-theme-light="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . '.css') }}" data-theme-dark="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . '.dark.css') }}" data-theme-target="href">
      <!-- Custom Navbar CSS -->
    <link href="{{ asset('css/navbar-custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout-fix.css') }}" rel="stylesheet">

    {!! config('settings.custom_js') !!}

    @if(config('settings.custom_css'))
        <style>
            {!! config('settings.custom_css') !!}
        </style>
    @endif
</head>
@yield('body')

<!-- Page-specific scripts -->
@yield('script_content')
</html>
