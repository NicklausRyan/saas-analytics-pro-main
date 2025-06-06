@extends('layouts.app')

@section('head_content')
    <link href="{{ asset('css/admin-sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-dashboard-fix.css') }}" rel="stylesheet">
@endsection

@section('script_content')
    <script src="{{ asset('js/admin-sidebar.js') }}"></script>
    @yield('admin_script_content')
@endsection

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container-fluid py-3 my-3">
        <div class="row">
            <div class="col-md-3 col-lg-2">
                <div class="admin-sidebar mb-3 mb-md-0">
                    <div class="d-md-none d-block text-center py-3">
                        <button id="admin-sidebar-toggle" class="btn btn-sm btn-outline-primary">
                            Menu <span class="fas fa-bars ml-1"></span>
                        </button>
                    </div>
                    <div class="nav flex-column" id="admin-sidebar-nav">
                        @include('admin.sidebar')
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-lg-10">
                @yield('admin_content')
            </div>
        </div>
    </div>
</div>
@endsection
