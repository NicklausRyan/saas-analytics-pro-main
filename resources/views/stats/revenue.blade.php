@section('site_title', formatTitle([$website->domain, __('Revenue'), config('settings.title')]))

<!-- Revenue Summary Dashboard -->
<div class="card border-0 shadow-sm overflow-hidden mb-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Revenue Dashboard') }} <span class="badge badge-success">New</span></div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Total Revenue card -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card border shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0 mr-2">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-5 height-5" viewBox="0 0 24 24">
                                        <path d="M12,2C6.486,2,2,6.486,2,12s4.486,10,10,10,10-4.486,10-10S17.514,2,12,2z M13,16.915V18h-2v-1.08 C8.661,16.553,8,14.918,8,14h2c0.011,0.143,0.159,1,2,1c1.38,0,2-0.585,2-1c0-0.324,0-1-2-1c-3.48,0-4-1.88-4-3 c0-1.288,1.029-2.584,3-2.915V6.012h2v1.109c1.734,0.41,2.4,1.853,2.4,2.879h-1l-1,0.018C13.386,9.638,13.185,9,12,9 c-1.299,0-2,0.516-2,1c0,0.374,0,1,2,1c3.48,0,4,1.88,4,3C16,15.288,14.971,16.584,13,16.915z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted font-weight-medium">{{ __('Total Revenue') }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="h3 m-0 text-success">{{ number_format($revenueSummary['totalAmount'], 2) }} {{ $revenueSummary['primaryCurrency'] }}</div>
                            </div>
                        </div>
                        <div class="mt-3 text-muted small">
                            @if($revenueSummary['growthRate'] > 0)
                                <span class="badge badge-success">+{{ number_format($revenueSummary['growthRate'], 1) }}%</span>
                            @elseif($revenueSummary['growthRate'] < 0)
                                <span class="badge badge-danger">{{ number_format($revenueSummary['growthRate'], 1) }}%</span>
                            @else
                                <span class="badge badge-secondary">0%</span>
                            @endif
                            <span>{{ __('compared to previous period') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- All Time Revenue card -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card border shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="d-flex position-relative text-dark width-10 height-10 align-items-center justify-content-center flex-shrink-0 mr-2">
                                    <div class="position-absolute bg-dark opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-5 height-5" viewBox="0 0 24 24">
                                        <path d="M12,2C6.486,2,2,6.486,2,12s4.486,10,10,10,10-4.486,10-10S17.514,2,12,2z M12,20c-4.411,0-8-3.589-8-8 s3.589-8,8-8s8,3.589,8,8S16.411,20,12,20z"/>
                                        <path d="M13 7L11 7 11 13 17 13 17 11 13 11z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted font-weight-medium">{{ __('All Time Revenue') }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="h3 m-0">{{ number_format($revenueSummary['allTimeRevenue'], 2) }} {{ $revenueSummary['primaryCurrency'] }}</div>
                            </div>
                        </div>
                        <div class="mt-3 text-muted small">
                            <span>{{ __('Since tracking began') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Month to Date Revenue card -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card border shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="d-flex position-relative text-info width-10 height-10 align-items-center justify-content-center flex-shrink-0 mr-2">
                                    <div class="position-absolute bg-info opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-5 height-5" viewBox="0 0 24 24">
                                        <path d="M19,4h-1V3c0-0.6-0.4-1-1-1s-1,0.4-1,1v1H8V3c0-0.6-0.4-1-1-1S6,2.4,6,3v1H5C3.3,4,2,5.3,2,7v11c0,1.7,1.3,3,3,3h14 c1.7,0,3-1.3,3-3V7C22,5.3,20.7,4,19,4z M20,18c0,0.6-0.4,1-1,1H5c-0.6,0-1-0.4-1-1V10h16V18z M20,8H4V7c0-0.6,0.4-1,1-1h1v1 c0,0.6,0.4,1,1,1s1-0.4,1-1V6h8v1c0,0.6,0.4,1,1,1s1-0.4,1-1V6h1c0.6,0,1,0.4,1,1V8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted font-weight-medium">{{ __('Month to Date') }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="h3 m-0 text-info">{{ number_format($revenueSummary['monthToDateRevenue'], 2) }} {{ $revenueSummary['primaryCurrency'] }}</div>
                            </div>
                        </div>
                        <div class="mt-3 text-muted small">
                            <span>{{ __('Current month') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Average Order Value card -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card border shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="d-flex position-relative text-warning width-10 height-10 align-items-center justify-content-center flex-shrink-0 mr-2">
                                    <div class="position-absolute bg-warning opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-5 height-5" viewBox="0 0 24 24">
                                        <path d="M12,9.988l5.709-5.709C18.31,3.679,18.972,3.513,19.5,3.5c1.677-0.041,2.5,0.781,2.5,2.457 c-0.026,0.57-0.191,1.11-0.5,1.59L15.707,13L21.5,18.793c0.309,0.48,0.474,1.02,0.5,1.59c0,1.676-0.823,2.498-2.5,2.457 c-0.528-0.013-1.19-0.179-1.791-0.779L12,16.352l-5.709,5.709c-0.601,0.6-1.263,0.766-1.791,0.779 c-1.677,0.041-2.5-0.781-2.5-2.457c0.026-0.57,0.191-1.11,0.5-1.59L8.293,13L2.5,7.207C2.191,6.727,2.026,6.187,2,5.617 c0-1.676,0.823-2.498,2.5-2.457c0.528,0.013,1.19,0.179,1.791,0.779L12,9.988z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted font-weight-medium">{{ __('Average Order') }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="h3 m-0 text-warning">{{ number_format($revenueSummary['averageOrderValue'], 2) }} {{ $revenueSummary['primaryCurrency'] }}</div>
                            </div>
                        </div>
                        <div class="mt-3 text-muted small">
                            <span>{{ __('Per transaction') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue chart -->
<div class="card border-0 shadow-sm overflow-hidden mb-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Revenue Trend') }}</div></div>
        </div>
    </div>
    <div class="card-body">
        @if(count($revenueByDay) == 0)
            {{ __('No data') }}.
        @else
            <div class="chart">
                <canvas id="revenue-chart"></canvas>
            </div>
        @endif
    </div>
</div>

<script>
'use strict';

document.addEventListener("DOMContentLoaded", function() {
    @if(count($revenueByDay) > 0)
        new Chart(document.getElementById('revenue-chart').getContext('2d'), {
            type: 'line',
            data: {
                labels: [
                    @foreach($revenueByDay as $date => $value)
                        '{{ $date }}',
                    @endforeach
                ],
                datasets: [{
                    label: '{{ __('Revenue') }}',
                    data: [
                        @foreach($revenueByDay as $date => $value)
                            {{ $value }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    pointRadius: 2,
                    pointBorderWidth: 0,
                    pointBackgroundColor: 'rgba(0, 123, 255, 1)',
                    pointHoverRadius: 4,
                    pointHoverBackgroundColor: 'rgba(0, 123, 255, 1)',
                    pointHoverBorderWidth: 0,
                    pointHitRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{{ __('Date') }}'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{{ __('Revenue') }}'
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return value + ' {{ $revenue->isNotEmpty() ? $revenue->first()->currency : '' }}';
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel + ' {{ $revenue->isNotEmpty() ? $revenue->first()->currency : '' }}';
                        }
                    }
                }
            }
        });
    @endif
});
</script>

<!-- Revenue Analytics -->
<div class="card border-0 shadow-sm p-0 mb-3">
    <div class="card-header border-0 bg-white">
        <div class="font-weight-medium py-1">{{ __('Revenue Analytics') }}</div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Period Comparison Chart -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('Revenue Comparison') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="h4">{{ number_format($revenueSummary['totalAmount'], 2) }} {{ $revenueSummary['primaryCurrency'] }}</div>
                                <div class="text-muted">{{ __('Current Period') }}</div>
                            </div>
                            <div class="col-md-4 text-center">
                                @if($revenueSummary['growthRate'] > 0)
                                    <div class="h4 text-success">+{{ number_format($revenueSummary['growthRate'], 1) }}%</div>
                                @elseif($revenueSummary['growthRate'] < 0)
                                    <div class="h4 text-danger">{{ number_format($revenueSummary['growthRate'], 1) }}%</div>
                                @else
                                    <div class="h4">0%</div>
                                @endif
                                <div class="text-muted">{{ __('Growth') }}</div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="h4">{{ number_format($revenueSummary['previousPeriodRevenue'], 2) }} {{ $revenueSummary['primaryCurrency'] }}</div>
                                <div class="text-muted">{{ __('Previous Period') }}</div>
                            </div>
                        </div>
                        <div class="progress mt-4" style="height: 15px;">
                            @php
                                $progressPercentage = $revenueSummary['previousPeriodRevenue'] > 0 ? 
                                    ($revenueSummary['totalAmount'] / ($revenueSummary['totalAmount'] + $revenueSummary['previousPeriodRevenue'])) * 100 : 100;
                            @endphp
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $progressPercentage }}%;" 
                                 aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                {{ __('Current') }}
                            </div>
                            <div class="progress-bar bg-secondary" role="progressbar" 
                                 style="width: {{ 100 - $progressPercentage }}%;" 
                                 aria-valuenow="{{ 100 - $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                {{ __('Previous') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Source Breakdown -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('Revenue Sources') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('Source') }}</th>
                                        <th class="text-right">{{ __('Amount') }}</th>
                                        <th class="text-right">{{ __('Percentage') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalSourceRevenue = array_sum($revenueSummary['revenueBySource']);
                                    @endphp
                                    @foreach($revenueSummary['revenueBySource'] as $source => $amount)
                                        <tr>
                                            <td><span class="badge badge-secondary text-capitalize">{{ $source }}</span></td>
                                            <td class="text-right">{{ number_format($amount, 2) }} {{ $revenueSummary['primaryCurrency'] }}</td>
                                            <td class="text-right">{{ $totalSourceRevenue > 0 ? number_format(($amount / $totalSourceRevenue) * 100, 1) : 0 }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Revenue Metrics -->
            <div class="col-12 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('Key Metrics') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Orders Count -->
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <div class="h5">{{ __('Total Orders') }}</div>
                                        <div class="h2 text-primary">{{ number_format($revenueSummary['totalOrders']) }}</div>
                                        <div class="text-muted small">{{ __('Transactions in period') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Average Order Value -->
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <div class="h5">{{ __('Average Order Value') }}</div>
                                        <div class="h2 text-success">{{ number_format($revenueSummary['averageOrderValue'], 2) }}</div>
                                        <div class="text-muted small">{{ $revenueSummary['primaryCurrency'] }} {{ __('per transaction') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Daily Average -->
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <div class="h5">{{ __('Daily Average') }}</div>
                                        <div class="h2 text-info">{{ number_format($revenueSummary['averagePerDay'], 2) }}</div>
                                        <div class="text-muted small">{{ $revenueSummary['primaryCurrency'] }} {{ __('per day') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Best Day Info -->
            @if($revenueSummary['maxDailyRevenue'])
            <div class="col-12 col-md-6 mb-3">
                <div class="card border h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('Best Performing Day') }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="h3 text-warning">{{ $revenueSummary['maxDailyRevenue']['date'] }}</div>
                        <div class="h4">{{ number_format($revenueSummary['maxDailyRevenue']['amount'], 2) }} {{ $revenueSummary['primaryCurrency'] }}</div>
                        <div class="text-muted">{{ __('Highest daily revenue') }}</div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- All-time Stats -->
            <div class="col-12 col-md-6 mb-3">
                <div class="card border h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('All-time Performance') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center border-right">
                                <div class="h5">{{ __('Total Revenue') }}</div>
                                <div class="h3">{{ number_format($revenueSummary['allTimeRevenue'], 2) }}</div>
                                <div class="text-muted small">{{ $revenueSummary['primaryCurrency'] }}</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="h5">{{ __('Month to Date') }}</div>
                                <div class="h3">{{ number_format($revenueSummary['monthToDateRevenue'], 2) }}</div>
                                <div class="text-muted small">{{ $revenueSummary['primaryCurrency'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-column">
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Revenue Details') }}</div></div>
                <div class="col-12 col-md-auto">
                    <div class="form-row">
                        @include('stats.filters', ['name' => __('Order'), 'count' => __('Amount')])
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(count($revenue) == 0)
                {{ __('No data') }}.
            @else
                <div class="list-group list-group-flush my-n3">
                    <div class="list-group-item px-0 text-muted">
                        <div class="row align-items-center">
                            <div class="col">
                                {{ __('Date') }}
                            </div>
                            <div class="col-auto">
                                {{ __('Amount') }}
                            </div>
                        </div>
                    </div>

                    @foreach($revenue as $entry)
                        <div class="list-group-item px-0 border-0">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <div class="d-flex text-truncate">
                                        <div class="text-truncate" dir="ltr">
                                            <span class="badge badge-secondary">{{ $entry->date }}</span>
                                            <a href="#" class="text-truncate">{{ $entry->order_id }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto d-flex">
                                    <div class="badge badge-{{ $entry->amount > 0 ? 'success' : 'danger' }}">
                                        {{ $entry->amount }} {{ strtoupper($entry->currency) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="mt-2 mb-3">
                                    {{ __('Showing :from-:to of :total', ['from' => $revenue->firstItem(), 'to' => $revenue->lastItem(), 'total' => $revenue->total()]) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                {{ $revenue->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
