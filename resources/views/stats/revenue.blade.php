@section('site_title', formatTitle([$website->domain, __('Revenue'), config('settings.title')]))

<!-- Revenue chart -->
<div class="card border-0 shadow-sm overflow-hidden mb-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Revenue') }}</div></div>
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

<!-- Revenue summary -->
<div class="card border-0 shadow-sm p-0 mb-3">
    <div class="px-3">
        <div class="row">
            <!-- Title -->
            <div class="col-12 col-md-auto d-none d-xl-flex align-items-center border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                <div class="px-2 py-4 d-flex">
                    <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                        <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                        <div class="text-primary font-weight-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-5 height-5" viewBox="0 0 24 24">
                                <path d="M12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Zm0-8.5a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0v-3A1,1,0,0,0,12,11.5Zm0-4a1.25,1.25,0,1,0,1.25,1.25A1.25,1.25,0,0,0,12,7.5Z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md text-truncate">
                <div class="row">
                    <!-- Total Revenue -->
                    <div class="col-12 col-md-6 border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md')  }}">
                        <div class="px-2 py-4">
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="text-truncate font-weight-medium">{{ __('Total Revenue') }}</div>

                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                            <div class="h2 mb-0">
                                                {{ $totalRevenue }} {{ $revenue->isNotEmpty() ? $revenue->first()->currency : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Count -->
                    <div class="col-12 col-md-6">
                        <div class="px-2 py-4">
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="text-truncate font-weight-medium">{{ __('Orders') }}</div>

                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                            <div class="h2 mb-0">{{ $revenue->total() }}</div>
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
