@section('site_title', formatTitle([$website->domain, __('Pages'), config('settings.title')]))

<div class="row m-n2">
    <!-- Most Popular Card -->    <div class="col-12 col-lg-6 p-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-4">
                <div class="d-flex align-items-center text-truncate">
                    @if(isset($first->value))
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            @if($first->value)
                                <div class="text-truncate" dir="ltr">{{ $first->value }}</div>
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
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            @if($last->value)
                                <div class="text-truncate" dir="ltr">{{ $last->value }}</div>
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
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <div class="row no-gutters">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Pages') }}</div></div>
                    <div class="col-12 col-md-auto">
                        <div class="form-row">
                            @include('stats.filters', ['name' => __('URL'), 'count' => __('Pageviews')])
                        </div>
                    </div>
                </div>
            </div><div class="card-body">
            @if(count($pages) == 0)
                {{ __('No data') }}.
            @else
                <div class="list-group list-group-flush my-n3">
                    <div class="list-group-item px-0 text-muted">
                        <div class="d-flex align-items-center">
                            <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">{{ __('URL') }}</div>
                            <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">{{ __('Pageviews') }}</div>
                            <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">{{ __('Revenue') }}</div>
                        </div>
                    </div>

                    <div class="list-group-item px-0 small text-muted">
                        <div class="d-flex align-items-center">
                            <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">
                                {{ __('Total') }}
                            </div>
                            <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                {{ number_format($total->count, 0, __('.'), __(',')) }}
                            </div>
                            <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                {{ isset($primaryCurrency) && $totalRevenue > 0 ? number_format($totalRevenue, 2, __('.'), __(',')) . ' ' . $primaryCurrency : '—' }}
                            </div>
                        </div>
                    </div>

                    @foreach($pages as $page)
                        @php
                            $pageRevenue = 0;
                            $pageRevenuePerVisitor = 0;
                            
                            // Always find this page in the revenue data
                            foreach($revenueByPage as $revenueData) {
                                if($revenueData['value'] == $page->value) {
                                    $pageRevenue = $revenueData['revenue'];
                                    $pageRevenuePerVisitor = $revenueData['revenuePerVisitor'];
                                    break;
                                }
                            }
                        @endphp
                        
                        <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">
                            <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 44px;">
                                <!-- Name Column -->
                                <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">
                                    <div class="d-flex text-truncate align-items-center">
                                        <div class="text-truncate" dir="ltr">{{ $page->value }}</div>
                                        <a href="http://{{ $website->domain . $page->value }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-in-new', ['class' => 'fill-current width-3 height-3'])</a>
                                    </div>
                                </div>
                                <!-- Pageviews Column -->
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                    {{ number_format($page->count, 0, __('.'), __(',')) }}
                                </div>
                                <!-- Revenue Column -->
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;" class="text-{{ $pageRevenue > 0 ? 'success font-weight-medium' : 'muted' }}">
                                    {{ $pageRevenue ? number_format($pageRevenue, 2, __('.'), __(',')) . ' ' . $primaryCurrency : '-' }}
                                </div>
                            </div>

                            <!-- Smart layered progress bars positioned behind content -->
                            <div class="position-absolute" style="top: 50%; left: 0; width: 50%; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">
                                @php
                                    // Calculate percentages for comparison
                                    $visitorsPercentage = ($page->count / $total->count) * 100;
                                    $maxPageRevenue = is_array($revenueByPage) ? 
                                        max(array_column($revenueByPage, 'revenue')) : 
                                        collect($revenueByPage)->max('revenue');
                                    $pageRevenuePercentage = $maxPageRevenue > 0 ? (($pageRevenue / $maxPageRevenue) * 100) : 0;
                                      
                                    // Determine which bar is smaller and should be on top
                                    $visitorsIsSmaller = $visitorsPercentage <= $pageRevenuePercentage;
                                    $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $pageRevenuePercentage;
                                    $largerPercentage = $visitorsIsSmaller ? $pageRevenuePercentage : $visitorsPercentage;
                                      
                                    // Add spacing between smaller and larger bars
                                    $spacingGap = 0.5859375;
                                    $largerBarStartPosition = $smallerPercentage + $spacingGap;
                                @endphp
                                  
                                @if($pageRevenue > 0)
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
                                <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $pages->firstItem(), 'to' => $pages->lastItem(), 'total' => $pages->total()]) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                {{ $pages->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>