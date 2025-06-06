@section('site_title', formatTitle([$website->domain, __('Screen resolutions'), config('settings.title')]))

<div class="row m-n2">
    <!-- Most Popular Card -->
    <div class="col-12 col-lg-6 p-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-4">
                <div class="d-flex align-items-center text-truncate">
                    @if(isset($first->value))
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            @if($first->value)
                                {{ $first->value }}
                            @else
                                {{ __('Unknown') }}
                            @endif
                        </div>
                    @else
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('No data') }}</div>
                    @endif                    <div class="align-self-end">{{ (isset($first->count) ? number_format($first->count, 0, __('.'), __(',')) : '—') }}</div>
                </div>
                <div class="d-flex align-items-center text-truncate text-success">
                    <div class="flex-grow-1 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ mb_strtolower(__('Most popular')) }}</div>
                    <div>{{ (isset($first->count) ? number_format((($first->count / $total->count) * 100), 1, __('.'), __(',')).'%' : '—') }}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Least Popular Card -->
    <div class="col-12 col-lg-6 p-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-4">
                <div class="d-flex align-items-center text-truncate">
                    @if(isset($last->value))
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                            @if($last->value)
                                {{ $last->value }}
                            @else
                                {{ __('Unknown') }}
                            @endif
                        </div>
                    @else
                        <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('No data') }}</div>
                    @endif                    <div class="align-self-end">{{ (isset($last->count) ? number_format($last->count, 0, __('.'), __(',')) : '—') }}</div>
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
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Screen resolutions') }}</div></div>
                    <div class="col-12 col-md-auto">
                        <div class="form-row">
                            @include('stats.filters', ['name' => __('Size'), 'count' => __('Visitors')])
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                @if(count($screenResolutions) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">{{ __('Size') }}</div>
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">{{ __('Visitors') }}</div>
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
                                    {{ number_format($totalRevenue, 2, __('.'), __(',')) }} {{ $primaryCurrency }}
                                </div>
                            </div>
                        </div>

                        @foreach($screenResolutions as $screenResolution)
                            @php
                                $resolutionRevenue = 0;
                                $resolutionRevenuePerVisitor = 0;
                                
                                // Always find this resolution in the revenue data
                                foreach($revenueByScreenResolution as $revenueData) {
                                    if($revenueData['value'] == $screenResolution->value) {
                                        $resolutionRevenue = $revenueData['revenue'];
                                        $resolutionRevenuePerVisitor = $revenueData['revenuePerVisitor'];
                                        break;
                                    }
                                }
                            @endphp
                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">
                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 44px;">
                                    <!-- Name Column -->
                                    <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="text-truncate">
                                                @if($screenResolution->value)
                                                    {{ $screenResolution->value }}
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Visitors Column -->
                                    <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                        {{ number_format($screenResolution->count, 0, __('.'), __(',')) }}
                                    </div>
                                    <!-- Revenue Column -->
                                    <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;" class="text-{{ $resolutionRevenue > 0 ? 'success font-weight-medium' : 'muted' }}">
                                        {{ $resolutionRevenue ? number_format($resolutionRevenue, 2, __('.'), __(',')) . ' ' . $primaryCurrency : '-' }}
                                    </div>
                                </div>
                                  <!-- Smart layered progress bars positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 50%; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">
                                    @php
                                        // Calculate percentages for comparison
                                        $visitorsPercentage = ($screenResolution->count / $total->count) * 100;
                                        $maxResolutionRevenue = is_array($revenueByScreenResolution) ? 
                                            max(array_column($revenueByScreenResolution, 'revenue')) : 
                                            collect($revenueByScreenResolution)->max('revenue');
                                        $resolutionRevenuePercentage = $maxResolutionRevenue > 0 ? (($resolutionRevenue / $maxResolutionRevenue) * 100) : 0;
                                          
                                        // Determine which bar is smaller and should be on top
                                        $visitorsIsSmaller = $visitorsPercentage <= $resolutionRevenuePercentage;
                                        $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $resolutionRevenuePercentage;
                                        $largerPercentage = $visitorsIsSmaller ? $resolutionRevenuePercentage : $visitorsPercentage;
                                        $spacingGap = 2; // Small gap between bars
                                    @endphp
                                      
                                    @if($resolutionRevenue > 0)
                                        <!-- Larger bar (background) -->
                                        <div class="progress-bar bg-{{ $visitorsIsSmaller ? 'success' : 'primary' }}" 
                                             role="progressbar" 
                                             style="width: {{ $largerPercentage }}%; height: 100%; position: absolute; opacity: 0.15; border-radius: 3px;">
                                        </div>
                                        
                                        <!-- Smaller bar (foreground) -->
                                        <div class="progress-bar bg-{{ $visitorsIsSmaller ? 'primary' : 'success' }}" 
                                             role="progressbar" 
                                             style="width: {{ $smallerPercentage }}%; height: 100%; position: absolute; opacity: 0.3; border-radius: 3px;">
                                        </div>
                                    @else
                                        <!-- Single bar when no revenue -->
                                        <div class="progress-bar bg-primary" 
                                             role="progressbar" 
                                             style="width: {{ $visitorsPercentage }}%; height: 100%; position: absolute; opacity: 0.15; border-radius: 3px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach                        <div class="mt-3 align-items-center">
                            <div class="row">
                                <div class="col">
                                    <small class="text-muted">{{ __(':from-:to of :total', ['from' => $screenResolutions->firstItem(), 'to' => $screenResolutions->lastItem(), 'total' => $screenResolutions->total()]) }}</small>
                                </div>
                                <div class="col-auto">
                                    {{ $screenResolutions->onEachSide(1)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>