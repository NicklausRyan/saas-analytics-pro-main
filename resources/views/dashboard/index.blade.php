@extends('layouts.app')

@section('site_title', formatTitle([__('Websites'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="bg-base-1">
        <div class="container py-3 my-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                <h2 class="h4 mb-0">{{ __('Dashboard') }}</h2>
                <div class="d-flex align-items-center mt-3 mt-md-0">
                    <div class="dropdown mr-2">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" id="timeframeDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @include('icons.date-range', ['class' => 'fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')])                            <span>
                                @if(safeCreateFromFormat($range['from'])->isToday() && safeCreateFromFormat($range['to'])->isToday())
                                    {{ __('Today') }}
                                @elseif(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(6)->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                    {{ __('Last :days days', ['days' => 7]) }}
                                @elseif(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(29)->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                    {{ __('Last :days days', ['days' => 30]) }}
                                @elseif(safeCreateFromFormat($range['from'])->format('Y-m-d') == Auth::user()->created_at->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                    {{ __('Total') }}
                                @else
                                    {{ safeCreateFromFormat($range['from'])->format(__('M d, Y')) }} - {{ safeCreateFromFormat($range['to'])->format(__('M d, Y')) }}
                                @endif
                            </span>
                        </button>                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="timeframeDropdown">
                            <a class="dropdown-item @if(safeCreateFromFormat($range['from'])->isToday()) active @endif" href="{{ route('dashboard', ['from' => \Carbon\Carbon::now()->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}">{{ __('Today') }}</a>
                            <a class="dropdown-item @if(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(6)->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) active @endif" href="{{ route('dashboard', ['from' => \Carbon\Carbon::now()->subDays(6)->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}">{{ __('Last :days days', ['days' => 7]) }}</a>
                            <a class="dropdown-item @if(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(29)->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) active @endif" href="{{ route('dashboard', ['from' => \Carbon\Carbon::now()->subDays(29)->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}">{{ __('Last :days days', ['days' => 30]) }}</a>
                            <a class="dropdown-item @if(safeCreateFromFormat($range['from'])->format('Y-m-d') == Auth::user()->created_at->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) active @endif" href="{{ route('dashboard', ['from' => Auth::user()->created_at->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}">{{ __('Total') }}</a>
                        </div>
                    </div>
                    
                    <a href="{{ route('websites.new') }}" class="btn btn-primary d-flex justify-content-center align-items-center">@include('icons.add', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('New website') }}</a>
                </div>
            </div>
            
            <div class="mb-5">
               <div class="row my-3">                    <!-- Visitors Card -->                    <div class="col-12 col-md-6 mb-3">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">                                    <div class="mr-4 d-flex align-items-center justify-content-center bg-primary rounded" style="min-width: 90px; min-height: 90px; width: 90px; height: 90px; position: relative;">
                                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,123,255,0.2); border-radius: 0.25rem;"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 56px; height: 56px; position: relative; z-index: 1; fill: white;">
                                            <path d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="font-weight-medium text-dark mb-2" style="font-size: 1.1rem;">{{ __('Visitors') }}</div>
                                        <div class="d-flex align-items-center">
                                            <div class="h1 font-weight-bold mb-0" style="line-height: 1;">{{ number_format($visitors, 0, __('.'), __(',')) }}</div>
                                            @if(isset($visitorsOld) && $visitorsOld > 0)
                                                @php 
                                                    $change = ($visitors - $visitorsOld) / $visitorsOld * 100;
                                                @endphp
                                                <span class="badge {{ $change >= 0 ? 'badge-success' : 'badge-danger' }} ml-3">
                                                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-muted ml-3">{{ __('No prior data') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted mb-0">{{ __('A visitor represents a page load of your website through direct access, or through a referrer.') }}</p>
                            </div>
                        </div>
                    </div>                      <!-- Total Revenue Card -->                    <div class="col-12 col-md-6 mb-3">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">                                    <div class="mr-4 d-flex align-items-center justify-content-center bg-success rounded" style="min-width: 90px; min-height: 90px; width: 90px; height: 90px; position: relative;">
                                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(40,167,69,0.2); border-radius: 0.25rem;"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 56px; height: 56px; position: relative; z-index: 1; fill: white;">
                                            <path d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="font-weight-medium text-dark mb-2" style="font-size: 1.1rem;">{{ __('Total Revenue') }}</div>
                                        <div class="d-flex align-items-center">
                                            <div class="h1 font-weight-bold mb-0" style="line-height: 1;">{{ number_format($totalRevenue, 2, __('.'), __(',')) }} {{ $primaryCurrency }}</div>
                                            @if(isset($totalRevenueOld) && $totalRevenueOld > 0)
                                                @php 
                                                    $change = ($totalRevenue - $totalRevenueOld) / $totalRevenueOld * 100;
                                                @endphp
                                                <span class="badge {{ $change >= 0 ? 'badge-success' : 'badge-danger' }} ml-3">
                                                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-muted ml-3">{{ __('No prior data') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted mb-0">{{ __('The total revenue from all your websites.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <h4 class="mb-3">{{ __('Websites') }}</h4>
            
            @include('shared.message')

            @if(count($websites) == 0)
                <div class="text-center mt-5">
                    <div class="h4 font-weight-normal text-muted mb-4">{{ __('No websites yet') }}</div>
                    <a href="{{ route('websites.new') }}" class="btn btn-primary">{{ __('Add your first website') }}</a>
                </div>
            @else                <div class="row">
                    @foreach($websites as $website)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded p-2 mr-3" style="background-color: #f8f9fa;">
                                                <img src="https://icons.duckduckgo.com/ip3/{{ $website->domain }}.ico" rel="noreferrer" class="width-8 height-8">
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-0">
                                                    <a href="{{ route('stats.overview', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-dark font-weight-bold" dir="ltr">{{ $website->domain }}</a>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="ml-auto">
                                            @include('websites.partials.menu')
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">                                        <!-- Visitors -->
                                        <div class="col-6">
                                            <div class="d-flex align-items-center mb-1">                                                <div class="d-flex align-items-center justify-content-center bg-primary rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" style="position: relative;">
                                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,123,255,0.2); border-radius: 0.25rem;"></div>
                                                </div>
                                                <div class="text-muted font-weight-medium">{{ __('Visitors') }}</div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold">{{ number_format($website->visitors->sum('count') ?? 0, 0, __('.'), __(',')) }}</div>
                                        </div>                                        <!-- Revenue -->
                                        <div class="col-6">
                                            <div class="d-flex align-items-center mb-1">                                                <div class="d-flex align-items-center justify-content-center bg-success rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" style="position: relative;">
                                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(40,167,69,0.2); border-radius: 0.25rem;"></div>
                                                </div>
                                                <div class="text-muted font-weight-medium">{{ __('Revenue') }}</div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold">{{ number_format($website->revenue->sum('amount') ?? 0, 2, __('.'), __(',')) }} {{ $primaryCurrency }}</div>
                                        </div>
                                    </div>                                </div>                                <div class="card-footer bg-base-0 d-flex justify-content-between align-items-center">
                                    <a href="{{ route('stats.overview', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="btn btn-sm btn-primary">{{ __('View stats') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($websites->hasPages())
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
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
