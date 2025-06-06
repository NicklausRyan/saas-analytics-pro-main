@section('site_title', formatTitle([$website->domain, __('Browsers'), config('settings.title')]))

<div class="row m-n2">
    <!-- Most Popular Card -->    <div class="col-12 col-lg-6 p-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-4">
                <div class="d-flex align-items-center text-truncate">
                    @if(isset($first->value))
                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            <img src="{{ asset('/images/icons/browsers/'.formatBrowser($first->value ?? '')) }}.svg" class="width-4 height-4">
                        </div>
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            @if($first->value)
                                {{ $first->value }}
                            @else
                                {{ __('Unknown') }}
                            @endif
                        </div>
                    @else
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('No data') }}</div>
                    @endif

                    <div class="align-self-end">{{ (isset($first->count) ? number_format($first->count, 0, __('.'), __(',')) : '—') }}</div>
                </div>
                <div class="d-flex align-items-center text-truncate text-success">
                    <div class="flex-grow-1 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ mb_strtolower(__('Most popular')) }}</div>
                    <div>{{ (isset($first->count) ? number_format((($first->count / $total->count) * 100), 1, __('.'), __(',')).'%' : '—') }}</div>
                </div>
            </div>
        </div>
    </div>    <!-- Least Popular Card -->
    <div class="col-12 col-lg-6 p-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-4">
                <div class="d-flex align-items-center text-truncate">
                    @if(isset($last->value))
                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            <img src="{{ asset('/images/icons/browsers/'.formatBrowser($last->value ?? '')) }}.svg" class="width-4 height-4">
                        </div>
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            @if($last->value)
                                {{ $last->value }}
                            @else
                                {{ __('Unknown') }}
                            @endif
                        </div>
                    @else
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('No data') }}</div>
                    @endif

                    <div class="align-self-end">{{ (isset($last->count) ? number_format($last->count, 0, __('.'), __(',')) : '—') }}</div>
                </div>
                <div class="d-flex align-items-center text-truncate text-danger">
                    <div class="flex-grow-1 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ mb_strtolower(__('Least popular')) }}</div>
                    <div>{{ (isset($last->count) ? number_format((($last->count / $total->count) * 100), 1, __('.'), __(',')).'%' : '—') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row m-n2">
    <div class="col-12 p-3">
        <div class="card border-0 shadow-sm"><div class="card-header">
                <div class="row no-gutters">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Browsers') }}</div></div>
                    <div class="col-12 col-md-auto">
                        <div class="form-row">
                            @include('stats.filters', ['name' => __('Name'), 'count' => __('Visitors')])
                        </div>
                    </div>
                </div>
            </div>            <div class="card-body py-4">
                @if(count($browsers) == 0)
                    {{ __('No data') }}.
                @else                    <div class="list-group list-group-flush my-n3"><div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">{{ __('Name') }}</div>
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">{{ __('Visitors') }}</div>
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">{{ __('Revenue') }}</div>
                            </div>
                        </div>                        <div class="list-group-item px-0 small text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">
                                    {{ __('Total') }}
                                </div>
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                    {{ number_format($total->count, 0, __('.'), __(',')) }}
                                </div>
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                    {{ number_format($totalRevenue, 2, __('.'), __(',')) }} {{ $primaryCurrency }}
                                </div>
                            </div>
                        </div>@foreach($browsers as $browser)
                            @php
                                $browserRevenue = 0;
                                $browserRevenuePerVisitor = 0;
                                
                                // Always find this browser in the revenue data
                                foreach($revenueByBrowser as $revenueData) {
                                    if($revenueData['value'] == $browser->value) {
                                        $browserRevenue = $revenueData['revenue'];
                                        $browserRevenuePerVisitor = $revenueData['revenuePerVisitor'];
                                        break;
                                    }
                                }
                            @endphp                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 44px;">
                                    <!-- Name Column -->
                                    <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                <img src="{{ asset('/images/icons/browsers/'.formatBrowser($browser->value)) }}.svg" class="width-4 height-4">
                                            </div>
                                            <div class="text-truncate">
                                                @if($browser->value)
                                                    {{ $browser->value }}
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Visitors Column -->
                                    <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                        {{ number_format($browser->count, 0, __('.'), __(',')) }}
                                    </div>
                                    <!-- Revenue Column -->
                                    <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;" class="text-{{ $browserRevenue > 0 ? 'success font-weight-medium' : 'muted' }}">
                                        {{ $browserRevenue ? number_format($browserRevenue, 2, __('.'), __(',')) . ' ' . $primaryCurrency : '-' }}
                                    </div>
                                </div><!-- Smart layered progress bars positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 50%; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">
                                    @php
                                        // Calculate percentages for comparison
                                        $visitorsPercentage = ($browser->count / $total->count) * 100;
                                        $maxBrowserRevenue = is_array($revenueByBrowser) ? 
                                            max(array_column($revenueByBrowser, 'revenue')) : 
                                            collect($revenueByBrowser)->max('revenue');
                                        $browserRevenuePercentage = $maxBrowserRevenue > 0 ? (($browserRevenue / $maxBrowserRevenue) * 100) : 0;
                                          
                                        // Determine which bar is smaller and should be on top
                                        $visitorsIsSmaller = $visitorsPercentage <= $browserRevenuePercentage;
                                        $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $browserRevenuePercentage;
                                        $largerPercentage = $visitorsIsSmaller ? $browserRevenuePercentage : $visitorsPercentage;
                                          
                                        // Add spacing between smaller and larger bars
                                        $spacingGap = 0.5859375;
                                        $largerBarStartPosition = $smallerPercentage + $spacingGap;
                                    @endphp
                                      
                                    @if($browserRevenue > 0)
                                        <!-- Larger bar (background layer) - starts after smaller bar ends with spacing -->
                                        <div class="position-absolute" style="top: 0; left: {{ $largerBarStartPosition }}%; width: {{ 100 - $largerBarStartPosition }}%; height: 100%; z-index: 1;">
                                            <div style="background: {{ $visitorsIsSmaller ? 'rgba(40, 167, 69, 0.5); border: 1px solid rgba(40, 167, 69, 0.7)' : 'rgba(54, 162, 235, 0.5); border: 1px solid rgba(54, 162, 235, 0.7)' }}; width: {{ (($largerPercentage - $smallerPercentage) / (100 - $largerBarStartPosition)) * 100 }}%; height: 100%;"></div>
                                        </div>
                                        
                                        <!-- Smaller bar (top layer) - full width from start -->
                                        <div class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; z-index: 2;">
                                            <div style="background: {{ $visitorsIsSmaller ? 'rgba(54, 162, 235, 0.5); border: 1px solid rgba(54, 162, 235, 0.7)' : 'rgba(40, 167, 69, 0.5); border: 1px solid rgba(40, 167, 69, 0.7)' }}; width: {{ $smallerPercentage }}%; height: 100%;"></div>
                                        </div>
                                    @else
                                        <!-- Only visitors bar when no revenue -->
                                        <div class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; z-index: 1;">
                                            <div style="background: rgba(54, 162, 235, 0.5); border: 1px solid rgba(54, 162, 235, 0.7); width: {{ $visitorsPercentage }}%; height: 100%;"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-3 align-items-center">
                            <div class="row">
                                <div class="col">
                                    <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $browsers->firstItem(), 'to' => $browsers->lastItem(), 'total' => $browsers->total()]) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    {{ $browsers->onEachSide(1)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>