@extends('layouts.app')

@section('site_title', formatTitle([__('Dashboard'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="bg-base-0">
        <div class="container py-5">
            <div class="d-flex">
                <div class="row no-gutters w-100">
                    <div class="d-flex col-12 col-md">
                        <div class="flex-shrink-1">
                            <a href="{{ route('account') }}" class="d-block"><img src="{{ gravatar(Auth::user()->email, 128) }}" class="rounded-circle width-16 height-16"></a>
                        </div>
                        <div class="flex-grow-1 d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                            <div>
                                <h4 class="font-weight-medium mb-0">{{ Auth::user()->name }}</h4>

                                <div class="d-flex flex-wrap">
                                    @if(paymentProcessors())
                                        <div class="d-inline-block mt-2 {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.package', ['class' => 'text-muted fill-current width-4 height-4'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    <a href="{{ route('account.plan') }}" class="text-dark text-decoration-none">{{ Auth::user()->plan->name }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-inline-block mt-2 {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.email', ['class' => 'text-muted fill-current width-4 height-4'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    {{ Auth::user()->email }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(paymentProcessors())
                        @if(Auth::user()->planOnDefault())
                            <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                                <a href="{{ route('pricing') }}" class="btn btn-outline-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0 {{ (__('lang_dir') == 'rtl' ? 'ml-md-3' : 'mr-md-3') }}">@include('icons.unarchive', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Upgrade') }}</a>
                            </div>
                        @else
                            <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                                <a href="{{ route('pricing') }}" class="btn btn-outline-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0 {{ (__('lang_dir') == 'rtl' ? 'ml-md-3' : 'mr-md-3') }}">@include('icons.package', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Plans') }}</a>
                            </div>
                        @endif
                    @endif

                    <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                        <a href="{{ route('websites.new') }}" class="btn btn-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0">@include('icons.add', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('New website') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-1">
        <div class="container py-3 my-3">
            <div class="mb-5">
                <div class="row">
                    <div class="col-12 col-lg">
                        <h4 class="mb-0">{{ __('Overview') }}</h4>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="nav nav-pills small">
                            <li class="nav-item">
                                <a href="{{ route('dashboard', ['from' => \Carbon\Carbon::now()->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" class="nav-link py-1 px-2 @if(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->isToday()) active @endif" href="#">{{ __('Today') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard', ['from' => \Carbon\Carbon::now()->subDays(6)->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" class="nav-link py-1 px-2 @if(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(6)->format('Y-m-d') && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) active @endif" href="#">{{ __('Last :days days', ['days' => 7]) }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard', ['from' => \Carbon\Carbon::now()->subDays(29)->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" class="nav-link py-1 px-2 @if(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(29)->format('Y-m-d') && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) active @endif" href="#">{{ __('Last :days days', ['days' => 30]) }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard', ['from' => Auth::user()->created_at->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" class="nav-link py-1 px-2 @if(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == Auth::user()->created_at->format('Y-m-d') && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) active @endif" href="#">{{ __('Total') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 rounded-top shadow-sm my-3 overflow-hidden">
                    <div class="px-3">
                        <div class="row">
                            <!-- Title -->
                            <div class="col-12 col-md-auto d-none d-md-flex align-items-center border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                                <div class="px-2 py-4 d-flex">
                                    <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                                        <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                        @include('icons.assesment', ['class' => 'fill-current width-5 height-5'])
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md">
                                <div class="row">
                                    <!-- Visitors -->
                                    <div class="col-12 col-md-6 border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                                        <div class="px-2 py-4">
                                            <div class="d-flex">
                                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                    <div class="d-flex align-items-center text-truncate">
                                                        <div class="d-flex align-items-center justify-content-center bg-primary rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>

                                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                                            <div class="text-truncate">{{ __('Visitors') }}</div>
                                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('A visitor represents a page load of your website through direct access, or through a referrer.') }}">
                                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @include('stats.growth', ['growthCurrent' => $visitors, 'growthPrevious' => $visitorsOld])
                                                </div>

                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                                    <div class="h2 font-weight-bold mb-0">{{ number_format($visitors, 0, __('.'), __(',')) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pageviews -->
                                    <div class="col-12 col-md-6">
                                        <div class="px-2 py-4">
                                            <div class="d-flex">
                                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                    <div class="d-flex align-items-center text-truncate">
                                                        <div class="d-flex align-items-center justify-content-center bg-danger rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>

                                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                                            <div class="text-truncate">{{ __('Pageviews') }}</div>
                                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('A pageview represents a page load of your website.') }}">
                                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @include('stats.growth', ['growthCurrent' => $pageviews, 'growthPrevious' => $pageviewsOld])
                                                </div>

                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                                    <div class="h2 font-weight-bold mb-0">{{ number_format($pageviews, 0, __('.'), __(',')) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mb-0">{{ __('Activity') }}</h4>

            <div class="row">
                <div class="col-12 mt-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <div class="row">
                                <div class="col"><div class="font-weight-medium py-1">{{ __('Websites') }}</div></div>
                                <div class="col-auto">
                                    <form method="GET" action="{{ route('dashboard') }}">
                                        <div class="input-group input-group-sm">
                                            <input class="form-control" name="search" placeholder="{{ __('Search') }}" value="{{ app('request')->input('search') }}">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-primary d-flex align-items-center dropdown-toggle dropdown-toggle-split reset-after" data-tooltip="true" title="{{ __('Filters') }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.filter', ['class' => 'fill-current width-4 height-4'])&#8203;</button>
                                                <div class="dropdown-menu {{ (__('lang_dir') == 'rtl' ? 'dropdown-menu' : 'dropdown-menu-right') }} border-0 shadow width-64 p-0" id="search-filters">
                                                    <div class="dropdown-header py-3">
                                                        <div class="row">
                                                            <div class="col"><div class="font-weight-medium m-0 text-body">{{ __('Filters') }}</div></div>
                                                            <div class="col-auto">
                                                                @if(request()->input('per_page'))
                                                                    <a href="{{ route('dashboard') }}" class="text-secondary">{{ __('Reset') }}</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="dropdown-divider my-0"></div>

                                                    <div class="max-height-96 overflow-auto pt-3">
                                                        <div class="form-group px-4">
                                                            <label for="i-search-by" class="small">{{ __('Search by') }}</label>
                                                            <select name="search_by" id="i-search-by" class="custom-select custom-select-sm">
                                                                @foreach(['domain' => __('Domain')] as $key => $value)
                                                                    <option value="{{ $key }}" @if(request()->input('search_by') == $key || !request()->input('search_by') && $key == 'name') selected @endif>{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group px-4">
                                                            <label for="i-sort-by" class="small">{{ __('Sort by') }}</label>
                                                            <select name="sort_by" id="i-sort-by" class="custom-select custom-select-sm">
                                                                @foreach(['id' => __('Date created'), 'domain' => __('Domain')] as $key => $value)
                                                                    <option value="{{ $key }}" @if(request()->input('sort_by') == $key) selected @endif>{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group px-4">
                                                            <label for="i-sort" class="small">{{ __('Sort') }}</label>
                                                            <select name="sort" id="i-sort" class="custom-select custom-select-sm">
                                                                @foreach(['desc' => __('Descending'), 'asc' => __('Ascending')] as $key => $value)
                                                                    <option value="{{ $key }}" @if(request()->input('sort') == $key) selected @endif>{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group px-4">
                                                            <label for="i-per-page" class="small">{{ __('Results per page') }}</label>
                                                            <select name="per_page" id="i-per-page" class="custom-select custom-select-sm">
                                                                @foreach([10, 25, 50, 100] as $value)
                                                                    <option value="{{ $value }}" @if(request()->input('per_page') == $value || request()->input('per_page') == null && $value == config('settings.paginate')) selected @endif>{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="dropdown-divider my-0"></div>

                                                    <div class="px-4 py-3">
                                                        <button type="submit" class="btn btn-primary btn-sm btn-block">{{ __('Search') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('shared.message')

                            @if(count($websites) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0 text-muted">
                                        <div class="row d-flex align-items-center">
                                            <div class="col">
                                                <div class="row align-items-center">
                                                    <div class="col-12 col-lg-4 text-truncate">
                                                        {{ __('Domain') }}
                                                    </div>

                                                    <div class="col-12 col-lg-4 text-truncate">
                                                        {{ __('Visitors') }}
                                                    </div>

                                                    <div class="col-12 col-lg-4 text-truncate">
                                                        {{ __('Pageviews') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="invisible btn d-flex align-items-center btn-sm text-primary">@include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($websites as $website)
                                        <div class="list-group-item px-0">
                                            <div class="row d-flex align-items-center">
                                                <div class="col text-truncate">
                                                    <div class="row text-truncate">
                                                        <div class="col-12 col-lg-4 d-flex align-items-center text-truncate">
                                                            <img src="https://icons.duckduckgo.com/ip3/{{ $website->domain }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"> <div class="text-truncate" dir="ltr"><a href="{{ route('stats.overview', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ $website->domain }}</a></div>
                                                        </div>

                                                        <div class="col-12 col-lg-4 d-flex align-items-center font-weight-medium">
                                                            <div class="d-flex align-items-center text-truncate">
                                                                <div class="d-flex align-items-center justify-content-center bg-primary rounded width-4 height-4 flex-shrink-0 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>
                                                                <div class="text-truncate">{{ number_format($website->visitors->sum('count') ?? 0, 0, __('.'), __(',')) }}</div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-lg-4 d-flex align-items-center font-weight-medium">
                                                            <div class="d-flex align-items-center text-truncate">
                                                                <div class="d-flex align-items-center justify-content-center bg-danger rounded width-4 height-4 flex-shrink-0 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>
                                                                <div class="text-truncate">{{ number_format($website->pageviews->sum('count') ?? 0, 0, __('.'), __(',')) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            @include('websites.partials.menu')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="mt-3 align-items-center">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $websites->firstItem(), 'to' => $websites->lastItem(), 'total' => $websites->total()]) }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                {{ $websites->onEachSide(1)->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('shared.sidebars.user')
