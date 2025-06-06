@section('site_title', formatTitle([$website->domain, __('Campaigns'), config('settings.title')]))

<div class="row m-n2">
    <!-- Most popular campaigns -->
    <div class="col-12 col-lg-6 p-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-4">
                <div class="row no-gutters">
                    <div class="col-auto d-flex position-relative text-success width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                        <div class="position-absolute bg-success opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                        @include('icons.trending-up', ['class' => 'fill-current width-6 height-6'])
                    </div>
                    <div class="col text-truncate">
                        @if(isset($first->value))
                            <div class="text-truncate">
                                <div class="font-weight-medium">
                                    @if($first->value)
                                        {{ $first->value }}
                                    @else
                                        {{ __('Unknown') }}
                                    @endif
                                </div>
                                <div class="text-muted">{{ __('Most popular') }}</div>
                            </div>
                        @else
                            <div class="text-truncate">
                                <div class="font-weight-medium">{{ __('No data') }}</div>
                                <div class="text-muted">{{ __('Most popular') }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <div class="font-weight-medium">{{ (isset($first->count) ? number_format($first->count, 0, __('.'), __(',')) : '—') }}</div>
                        <div class="text-muted">{{ (isset($first->count) ? number_format((($first->count / $total->count) * 100), 1, __('.'), __(',')).'%' : '—') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Least popular campaigns -->
    <div class="col-12 col-lg-6 p-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-4">
                <div class="row no-gutters">
                    <div class="col-auto d-flex position-relative text-danger width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                        <div class="position-absolute bg-danger opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                        @include('icons.trending-down', ['class' => 'fill-current width-6 height-6'])
                    </div>
                    <div class="col text-truncate">
                        @if(isset($last->value))
                            <div class="text-truncate">
                                <div class="font-weight-medium">
                                    @if($last->value)
                                        {{ $last->value }}
                                    @else
                                        {{ __('Unknown') }}
                                    @endif
                                </div>
                                <div class="text-muted">{{ __('Least popular') }}</div>
                            </div>
                        @else
                            <div class="text-truncate">
                                <div class="font-weight-medium">{{ __('No data') }}</div>
                                <div class="text-muted">{{ __('Least popular') }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <div class="font-weight-medium">{{ (isset($last->count) ? number_format($last->count, 0, __('.'), __(',')) : '—') }}</div>
                        <div class="text-muted">{{ (isset($last->count) ? number_format((($last->count / $total->count) * 100), 1, __('.'), __(',')).'%' : '—') }}</div>
                    </div>
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
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Campaigns') }}</div></div>
                    <div class="col-12 col-md-auto">
                        <div class="form-row">
                            @include('stats.filters', ['name' => __('Name'), 'count' => __('Visitors')])
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
            @if(count($campaigns) == 0)
                {{ __('No data') }}.
            @else                <div class="list-group list-group-flush my-n3">
                    <div class="list-group-item px-0 text-muted">
                        <div class="d-flex align-items-center">
                            <div style="width: 45%; flex-shrink: 0; padding: 0 12px;">{{ __('Name') }}</div>
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

                    @foreach($campaigns as $campaign)
                        @php
                            $campaignRevenue = 0;
                            $campaignRevenuePerVisitor = 0;
                            
                            // Always find this campaign in the revenue data
                            foreach($revenueByCampaign as $revenueData) {
                                if($revenueData['value'] == $campaign->value) {
                                    $campaignRevenue = $revenueData['revenue'];
                                    $campaignRevenuePerVisitor = $revenueData['revenuePerVisitor'];
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
                                            @if($campaign->value)
                                                {{ $campaign->value }}
                                            @else
                                                {{ __('Unknown') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Visitors Column -->
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;">
                                    {{ number_format($campaign->count, 0, __('.'), __(',')) }}
                                </div>
                                <!-- Revenue Column -->
                                <div style="width: 27.5%; flex-shrink: 0; text-align: right; padding: 0 8px;" class="text-{{ $campaignRevenue > 0 ? 'success font-weight-medium' : 'muted' }}">
                                    {{ $campaignRevenue ? number_format($campaignRevenue, 2, __('.'), __(',')) . ' ' . $primaryCurrency : '-' }}
                                </div>
                            </div>
                            
                            <!-- Smart layered progress bars positioned behind content -->
                            <div class="position-absolute" style="top: 50%; left: 0; width: 50%; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">
                                @php
                                    // Calculate percentages for comparison
                                    $visitorsPercentage = ($campaign->count / $total->count) * 100;
                                    $maxCampaignRevenue = is_array($revenueByCampaign) ? 
                                        max(array_column($revenueByCampaign, 'revenue')) :
                                        collect($revenueByCampaign)->max('revenue');
                                    $campaignRevenuePercentage = $maxCampaignRevenue > 0 ? (($campaignRevenue / $maxCampaignRevenue) * 100) : 0;
                                      
                                    // Determine which bar is smaller and should be on top
                                    $visitorsIsSmaller = $visitorsPercentage <= $campaignRevenuePercentage;
                                    $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $campaignRevenuePercentage;
                                    $largerPercentage = $visitorsIsSmaller ? $campaignRevenuePercentage : $visitorsPercentage;
                                      
                                    // Add spacing between smaller and larger bars
                                    $spacingGap = 0.5859375;
                                    $largerBarStartPosition = $smallerPercentage + $spacingGap;
                                @endphp
                                  
                                @if($campaignRevenue > 0)
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
                                <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $campaigns->firstItem(), 'to' => $campaigns->lastItem(), 'total' => $campaigns->total()]) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                {{ $campaigns->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>                </div>
            @endif
            </div>
        </div>
    </div>
</div>