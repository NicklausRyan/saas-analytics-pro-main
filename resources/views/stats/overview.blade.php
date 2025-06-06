@section('site_title', formatTitle([$website->domain, __('Overview'), config('settings.title')]))

<div class="card border-0 rounded-top shadow-sm mb-3 overflow-hidden" id="trend-chart-container">    <div class="px-3">
        <div class="row">
            <div class="col-12 col-md">
                <div class="row"><!-- Visitors -->
                    <div class="col-12 col-md border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                        <div class="px-2 py-4">                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="font-weight-medium h6 mb-0">{{ __('Visitors') }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="h4 font-weight-bold mb-0">{{ number_format($totalVisitors, 0, __('.'), __(',')) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Total Revenue -->
                    <div class="col-12 col-md border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                        <div class="px-2 py-4">                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="font-weight-medium h6 mb-0">{{ __('Total Revenue') }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="h4 font-weight-bold mb-0">${{ number_format($totalRevenue, 2, __('.'), __(',')) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Revenue per visitor -->
                    <div class="col-12 col-md border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                        <div class="px-2 py-4">                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="font-weight-medium h6 mb-0">{{ __('Revenue / Visitor') }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="h4 font-weight-bold mb-0">${{ number_format($revenuePerVisitor, 2, __('.'), __(',')) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Bounce Rate -->
                    <div class="col-12 col-md border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                        <div class="px-2 py-4">                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="font-weight-medium h6 mb-0">{{ __('Bounce Rate') }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="h4 font-weight-bold mb-0">{{ number_format($bounceRate * 100, 1, __('.'), __(',')) }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Average Session Duration -->
                    <div class="col-12 col-md border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                        <div class="px-2 py-4">                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="font-weight-medium h6 mb-0">{{ __('Session Time') }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="h4 font-weight-bold mb-0">{{ floor($avgSessionDuration / 60) }}m {{ $avgSessionDuration % 60 }}s</div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Conversion Rate -->
                    <div class="col-12 col-md border-bottom border-bottom-md-0">
                        <div class="px-2 py-4">                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="font-weight-medium h6 mb-0">{{ __('Conversion Rate') }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="h4 font-weight-bold mb-0">{{ number_format($conversionRate * 100, 1, __('.'), __(',')) }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <div class="card-body pt-0">
        <div style="height: 230px">
            <canvas id="trend-chart"></canvas>
        </div><script>            'use strict';

            window.addEventListener("DOMContentLoaded", function () {
                // Helper function to format numbers with abbreviations for large values
                function formatNumber(num) {
                    if (num >= 1000000) {
                        return (num / 1000000).toFixed(1) + 'M';
                    } else if (num >= 1000) {
                        return (num / 1000).toFixed(1) + 'K';
                    }
                    return num.toFixed(0);
                }
                
                Chart.defaults.font = {
                    family: "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'",
                    size: 12
                };
                
                const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');
                
                // Define colors directly since legend squares have been removed
                const uniqueColor = 'rgb(0, 123, 255)'; // Primary blue for visitors
                const revenueColor = 'rgb(40, 167, 69)'; // Success green for revenue
                const bounceRateColor = 'rgb(23, 162, 184)'; // Info blue for bounce rate
                const sessionDurationColor = 'rgb(108, 117, 125)'; // Secondary gray for session duration
                  const ctx = document.querySelector('#trend-chart').getContext('2d');

                // Create gradient for visitors line - from clear at bottom to blue approaching the line
                const gradient1 = ctx.createLinearGradient(0, 230, 0, 0);
                gradient1.addColorStop(0, 'rgba(0, 123, 255, 0)'); // Clear at bottom
                gradient1.addColorStop(0.7, 'rgba(0, 123, 255, 0.1)'); // Slight blue
                gradient1.addColorStop(0.9, 'rgba(0, 123, 255, 0.3)'); // More blue approaching line
                gradient1.addColorStop(1, 'rgba(0, 123, 255, 0.6)'); // Strong blue at the line

                /*
                const gradient2 = ctx.createLinearGradient(0, 0, 0, 300);
                gradient2.addColorStop(0, pageViewsColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                gradient2.addColorStop(1, pageViewsColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));
                */
                
                const gradient3 = ctx.createLinearGradient(0, 0, 0, 300);
                gradient3.addColorStop(0, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                gradient3.addColorStop(1, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                let tooltipTitles = [
                    @foreach($visitorsMap as $date => $value)
                        @if($range['unit'] == 'hour')
                            '{{ \Carbon\Carbon::createFromFormat('H', $date)->format('H:i') }}',
                        @elseif($range['unit'] == 'day')
                            '{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}',
                        @elseif($range['unit'] == 'month')
                            '{{ \Carbon\Carbon::parse($date)->format(__('Y-m')) }}',
                        @else
                            '{{ $date }}',
                        @endif                    @endforeach
                ];
                  const lineOptions = {
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    hitRadius: 5,
                    pointHoverBorderWidth: 3,
                    tension: 0.4
                };let trendChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach($visitorsMap as $date => $value)
                                @if($range['unit'] == 'hour')
                                    '{{ \Carbon\Carbon::createFromFormat('H', $date)->format('H:i') }}',
                                @elseif($range['unit'] == 'day')
                                    '{{ __(':month :day', ['month' => mb_substr(__(\Carbon\Carbon::parse($date)->format('F')), 0, 3), 'day' => __(\Carbon\Carbon::parse($date)->format('j'))]) }}',
                                @elseif($range['unit'] == 'month')
                                    '{{ __(':year :month', ['year' => \Carbon\Carbon::parse($date)->format('Y'), 'month' => mb_substr(__(\Carbon\Carbon::parse($date)->format('F')), 0, 3)]) }}',
                                @else
                                    '{{ $date }}',
                                @endif
                            @endforeach
                        ],
                        datasets: [{
                            type: 'bar',
                            label: '{{ __('Revenue') }}',
                            data: [
                                @foreach($revenueMap as $date => $value)
                                    {{ $value }},
                                @endforeach
                            ],                            backgroundColor: revenueColor.replace('rgb', 'rgba').replace(')', ', 0.5)'),
                            borderColor: revenueColor.replace('rgb', 'rgba').replace(')', ', 0.7)'),
                            borderWidth: 1,
                            yAxisID: 'y1',
                            order: 3
                        }, {
                            type: 'line',
                            label: '{{ __('Visitors') }}',
                            data: [
                                @foreach($visitorsMap as $date => $value)
                                    {{ $value }},
                                @endforeach
                            ],
                            fill: true,
                            backgroundColor: gradient1,
                            borderColor: uniqueColor,                            pointBorderColor: uniqueColor,
                            pointBackgroundColor: uniqueColor,
                            pointHoverBackgroundColor: phBgColor,
                            pointHoverBorderColor: uniqueColor,
                            ...lineOptions,
                            order: 1                      
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            x: {
                                display: true,
                                offset: true,
                                grid: {
                                    lineWidth: 0,
                                    tickLength: 0,
                                    display: true,
                                    drawBorder: false,
                                    drawOnChartArea: false
                                },
                                ticks: {
                                    maxTicksLimit: @if($range['unit'] == 'day') 12 @else 15 @endif,
                                    padding: 10,
                                }
                            },
                            y: {
                                position: 'left',
                                display: true,
                                beginAtZero: true,
                                grid: {
                                    borderDash: [2, 2],
                                    drawBorder: false,
                                    tickLength: 0
                                },
                                ticks: {
                                    maxTicksLimit: 8,
                                    padding: 10,
                                    callback: function (value) {
                                        return formatNumber(value);
                                    }
                                }
                            },
                            y1: {
                                position: 'right',
                                display: true,
                                beginAtZero: true,
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },                                ticks: {
                                    callback: function (value) {
                                        return '$' + formatNumber(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                                display: false
                            },
                            tooltip: {
                                rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                                mode: 'index',
                                intersect: false,
                                reverse: true,
                                padding: {
                                    top: 14,
                                    right: 16,
                                    bottom: 16,
                                    left: 16
                                },
                                backgroundColor: '{{ (config('settings.dark_mode') == 1 ? '#FFF' : '#000') }}',
                                titleColor: '{{ (config('settings.dark_mode') == 1 ? '#000' : '#FFF') }}',
                                titleMarginBottom: 7,
                                titleFont: {
                                    size: 16,
                                    weight: 'normal'
                                },
                                bodyColor: '{{ (config('settings.dark_mode') == 1 ? '#000' : '#FFF') }}',
                                bodySpacing: 7,
                                bodyFont: {
                                    size: 14
                                },
                                footerMarginTop: 10,
                                footerFont: {
                                    size: 12,
                                    weight: 'normal'
                                },
                                cornerRadius: 4,
                                caretSize: 7,
                                boxPadding: 4,
                                callbacks: {                                    label: function (tooltipItem) {
                                        // Format revenue values with currency symbol
                                        if (tooltipItem.dataset.label === '{{ __('Revenue') }}') {
                                            return ' ' + tooltipItem.dataset.label + ': $' + parseFloat(tooltipItem.dataset.data[tooltipItem.dataIndex]).format(2, 3, '{{ __(',') }}').toString();
                                        }
                                        return ' ' + tooltipItem.dataset.label + ': ' + parseFloat(tooltipItem.dataset.data[tooltipItem.dataIndex]).format(0, 3, '{{ __(',') }}').toString();
                                    },
                                    title: function (tooltipItem) {
                                        return tooltipTitles[tooltipItem[0].dataIndex];
                                    }
                                }
                            }
                        }
                    }
                });

                // The time to wait before attempting to change the colors on first attempt
                let colorSchemeTimer = 500;                // Update the chart colors when the color scheme changes
                const observer = (new MutationObserver(function (mutationsList, observer) {
                    for (const mutation of mutationsList) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            setTimeout(function () {
                                const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');
                                  // Use the same defined colors as above
                                const visitorsColor = 'rgb(0, 123, 255)'; // Primary blue for visitors
                                const revenueColor = 'rgb(40, 167, 69)'; // Success green for revenue

                                // Create the same gradient style for visitors line
                                const gradient1 = ctx.createLinearGradient(0, 230, 0, 0);
                                gradient1.addColorStop(0, 'rgba(0, 123, 255, 0)'); // Clear at bottom
                                gradient1.addColorStop(0.7, 'rgba(0, 123, 255, 0.1)'); // Slight blue
                                gradient1.addColorStop(0.9, 'rgba(0, 123, 255, 0.3)'); // More blue approaching line
                                gradient1.addColorStop(1, 'rgba(0, 123, 255, 0.6)'); // Strong blue at the line
                                
                                const gradient3 = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient3.addColorStop(0, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                                gradient3.addColorStop(1, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                                // Update Revenue dataset (index 0)
                                trendChart.data.datasets[0].backgroundColor = revenueColor.replace('rgb', 'rgba').replace(')', ', 0.6)');
                                trendChart.data.datasets[0].borderColor = revenueColor;

                                // Update Visitors dataset (index 1)
                                trendChart.data.datasets[1].backgroundColor = gradient1;
                                trendChart.data.datasets[1].borderColor = visitorsColor;
                                trendChart.data.datasets[1].pointBorderColor = visitorsColor;
                                trendChart.data.datasets[1].pointBackgroundColor = visitorsColor;
                                trendChart.data.datasets[1].pointHoverBackgroundColor = phBgColor;
                                trendChart.data.datasets[1].pointHoverBorderColor = visitorsColor;

                                trendChart.options.plugins.tooltip.backgroundColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#000' : '#FFF');
                                trendChart.options.plugins.tooltip.titleColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#FFF' : '#000');
                                trendChart.options.plugins.tooltip.bodyColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#FFF' : '#000');
                                trendChart.update();

                                // Update the color scheme timer to be faster next time it's used
                                colorSchemeTimer = 100;
                            }, colorSchemeTimer);
                        }
                    }
                }));

                observer.observe(document.querySelector('html'), { attributes: true });
            });
        </script>
    </div>
</div>
<div class="row m-n2">
    <div class="col-12 col-lg-6 p-2">
        <div class="card border-0 shadow-sm h-100">            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md d-flex justify-content-between align-items-center">
                        <div class="font-weight-medium py-1">{{ __('Pages') }}</div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="pagesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Behavior') }}
                            </button>                            <div class="dropdown-menu dropdown-menu-right border-0 shadow" aria-labelledby="pagesDropdown">
                                <a class="dropdown-item" href="{{ route('stats.pages', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Pages') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.landing_pages', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Landing pages') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.exit_pages', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Exit pages') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(count($pages) == 0)
                    {{ __('No data') }}.
                @else                    <div class="list-group list-group-flush my-n3">                        <div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">{{ __('URL') }}</div>
                                <div style="width: 200px; flex-shrink: 0; text-align: right;">{{ __('Pageviews') }}</div>
                            </div>
                        </div>@foreach($pages as $page)                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 40px;">                                    <!-- URL Column -->
                                    <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="text-truncate" dir="ltr">{{ $page->value }}</div> 
                                            <a href="http://{{ $website->domain . $page->value }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-in-new', ['class' => 'fill-current width-3 height-3'])</a>
                                        </div>
                                    </div>
                                    
                                    <!-- Pageviews Column -->
                                    <div style="width: 200px; flex-shrink: 0; text-align: right;">
                                        {{ number_format($page->count, 0, __('.'), __(',')) }}
                                    </div>
                                </div><!-- Background progress bar positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 280px; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">
                                    <div style="background: rgba(54, 162, 235, 0.5); border: 1px solid rgba(54, 162, 235, 0.7); width: {{ (($page->count / $totalPageviews) * 100) }}%; height: 100%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>            @if(count($pages) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.pages', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('Details') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif        </div>
    </div>

    <div class="col-12 col-lg-6 p-2">
        <div class="card border-0 shadow-sm h-100">            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md d-flex justify-content-between align-items-center">
                        <div class="font-weight-medium py-1">{{ __('Campaigns') }}</div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="campaignsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Acquisitions') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow" aria-labelledby="campaignsDropdown">
                                <a class="dropdown-item" href="{{ route('stats.referrers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Referrers') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.search_engines', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Search engines') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.social_networks', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Social networks') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('stats.campaigns', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Campaigns') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(count($campaigns) == 0)
                    {{ __('No data') }}.
                @else                    <div class="list-group list-group-flush my-n3">                        <div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">{{ __('Name') }}</div>
                                <div style="width: 200px; flex-shrink: 0; text-align: right;">{{ __('Visitors') }}</div>
                            </div>
                        </div>

                        @foreach($campaigns as $campaign)
                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 40px;">
                                    <!-- Name Column -->
                                    <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">
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
                                    <div style="width: 200px; flex-shrink: 0; text-align: right;">
                                        {{ number_format($campaign->count, 0, __('.'), __(',')) }}
                                    </div>
                                </div>

                                <!-- Background progress bar positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 280px; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">
                                    <div style="background: rgba(54, 162, 235, 0.5); border: 1px solid rgba(54, 162, 235, 0.7); width: {{ $totalCampaigns > 0 ? (($campaign->count / $totalCampaigns) * 100) : 0 }}%; height: 100%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @if(count($campaigns) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.campaigns', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('Details') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row m-n2">
    <div class="col-12 col-lg-6 p-2">
        <div class="card border-0 shadow-sm h-100">            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md d-flex justify-content-between align-items-center">
                        <div class="font-weight-medium py-1">{{ __('Referrers') }}</div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="referrersDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Acquisitions') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow" aria-labelledby="referrersDropdown">
                                <a class="dropdown-item" href="{{ route('stats.referrers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Referrers') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.search_engines', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Search engines') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.social_networks', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Social networks') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('stats.campaigns', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Campaigns') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(count($referrersWithRevenue) == 0)
                    {{ __('No data') }}.
                @else                    <div class="list-group list-group-flush my-n3">                        <div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">{{ __('Website') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Visitors') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Revenue') }}</div>
                            </div>
                        </div>                        @foreach($referrersWithRevenue as $referrer)                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">
                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 40px;">
                                    <!-- Website Column -->
                                    <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">
                                        <div class="d-flex text-truncate align-items-center">
                                            @if($referrer['value'])
                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                    <img src="https://icons.duckduckgo.com/ip3/{{ $referrer['value'] }}.ico" rel="noreferrer" class="width-4 height-4">
                                                </div>
                                                <div class="d-flex text-truncate">
                                                    <div class="text-truncate" dir="ltr">{{ $referrer['value'] }}</div> 
                                                    <a href="http://{{ $referrer['value'] }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-in-new', ['class' => 'fill-current width-3 height-3'])</a>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                    <img src="{{ asset('/images/icons/referrers/unknown.svg') }}" rel="noreferrer" class="width-4 height-4">
                                                </div>
                                                <div class="text-truncate">
                                                    {{ __('Direct, Email, SMS') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Visitors Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;">
                                        {{ number_format($referrer['visits'], 0, __('.'), __(',')) }}
                                    </div>
                                    
                                    <!-- Revenue Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;" class="text-{{ $referrer['revenue'] > 0 ? 'success' : 'muted' }}">
                                        {{ $referrer['revenue'] ? '$' . number_format($referrer['revenue'], 2, __('.'), __(',')) : '-' }}
                                    </div>
                                </div>                                <!-- Smart layered progress bars positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 280px; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">                                    @php
                                        // Calculate percentages for comparison
                                        $visitorsPercentage = ($referrer['visits'] / $totalReferrers) * 100;
                                        $maxRevenue = is_array($referrersWithRevenue) ? 
                                            max(array_column($referrersWithRevenue, 'revenue')) : 
                                            collect($referrersWithRevenue)->max('revenue');
                                        $revenuePercentage = $maxRevenue > 0 ? (($referrer['revenue'] / $maxRevenue) * 100) : 0;
                                        
                                        // Determine which bar is smaller and should be on top
                                        $visitorsIsSmaller = $visitorsPercentage <= $revenuePercentage;
                                        $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $revenuePercentage;                                        $largerPercentage = $visitorsIsSmaller ? $revenuePercentage : $visitorsPercentage;                                        // Add spacing between bars (0.5859375% gap - 25% increase)
                                        $spacingGap = 0.5859375;
                                        $largerBarStartPosition = $smallerPercentage + $spacingGap;
                                    @endphp
                                    
                                    @if($referrer['revenue'] > 0)
                                        <!-- Larger bar (background layer) - starts after smaller bar ends with spacing -->
                                        <div class="position-absolute" style="top: 0; left: {{ $largerBarStartPosition }}%; width: {{ 100 - $largerBarStartPosition }}%; height: 100%; z-index: 1;">
                                            <div style="background: {{ $visitorsIsSmaller ? 'rgba(40, 167, 69, 0.5); border: 1px solid rgba(40, 167, 69, 0.7)' : 'rgba(54, 162, 235, 0.5); border: 1px solid rgba(54, 162, 235, 0.7)' }}; width: {{ (($largerPercentage - $largerBarStartPosition) / (100 - $largerBarStartPosition)) * 100 }}%; height: 100%;"></div>
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
                    </div>
                @endif
            </div>            @if(count($referrersWithRevenue) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.referrers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('Details') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif        </div>    </div><div class="col-12 col-lg-6 p-2">
        <div class="card border-0 shadow-sm h-100">            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md d-flex justify-content-between align-items-center">
                        <div class="font-weight-medium py-1">{{ __('Countries') }}</div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="countriesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Geographic') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow" aria-labelledby="countriesDropdown">
                                <a class="dropdown-item" href="{{ route('stats.continents', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Continents') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.countries', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Countries') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.cities', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Cities') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.languages', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Languages') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><div class="card-body">
                @if(count($countriesWithRevenue) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">{{ __('Name') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Visitors') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Revenue') }}</div>
                            </div>                        </div>                        @foreach(array_slice($countriesWithRevenue, 0, 5) as $country)                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">
                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 40px;">
                                    <!-- Name Column -->
                                    <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                <img src="{{ asset('/images/icons/countries/'. formatFlag($country['value'])) }}.svg" class="width-4 height-4">
                                            </div>                                            <div class="text-truncate">
                                                @if(formatCountryName($country['value']) !== __('Unknown'))
                                                    <a href="{{ route('stats.cities', ['id' => $website->domain, 'search' => explode(':', $country['value'])[0].':', 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-body" data-tooltip="true" title="{{ __(formatCountryName($country['value'])) }}">{{ formatCountryName($country['value']) }}</a>
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Visitors Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;">
                                        {{ number_format($country['visits'], 0, __('.'), __(',')) }}
                                    </div>
                                    
                                    <!-- Revenue Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;" class="text-{{ $country['revenue'] > 0 ? 'success' : 'muted' }}">
                                        {{ $country['revenue'] ? '$' . number_format($country['revenue'], 2, __('.'), __(',')) : '-' }}
                                    </div>
                                </div>                                <!-- Smart layered progress bars positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 280px; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">                                    @php
                                        // Calculate percentages for comparison
                                        $visitorsPercentage = ($country['visits'] / $totalVisitors) * 100;
                                        $maxCountryRevenue = is_array($countriesWithRevenue) ? 
                                            max(array_column($countriesWithRevenue, 'revenue')) : 
                                            collect($countriesWithRevenue)->max('revenue');
                                        $countryRevenuePercentage = $maxCountryRevenue > 0 ? (($country['revenue'] / $maxCountryRevenue) * 100) : 0;
                                          // Determine which bar is smaller and should be on top
                                        $visitorsIsSmaller = $visitorsPercentage <= $countryRevenuePercentage;
                                        $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $countryRevenuePercentage;                                        $largerPercentage = $visitorsIsSmaller ? $countryRevenuePercentage : $visitorsPercentage;
                                          // Add spacing between smaller and larger bars (25% increase)
                                        $spacingGap = 0.5859375;
                                        $largerBarStartPosition = $smallerPercentage + $spacingGap;
                                    @endphp
                                      @if($country['revenue'] > 0)
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
                    </div>
                @endif
            </div>            @if(count($countriesWithRevenue) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.countries', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('Details') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>    </div><div class="col-12 col-lg-6 p-2">
        <div class="card border-0 shadow-sm h-100">            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md d-flex justify-content-between align-items-center">
                        <div class="font-weight-medium py-1">{{ __('Browsers') }}</div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="browsersDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Technology') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow" aria-labelledby="browsersDropdown">
                                <a class="dropdown-item" href="{{ route('stats.browsers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Browsers') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.operating_systems', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Operating systems') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.screen_resolutions', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Screen resolutions') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.devices', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Devices') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><div class="card-body">
                @if(count($browsersWithRevenue) == 0)
                    {{ __('No data') }}.
                @else                    <div class="list-group list-group-flush my-n3">                        <div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">{{ __('Name') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Visitors') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Revenue') }}</div>
                            </div>
                        </div>                        @foreach($browsersWithRevenue as $browser)                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">
                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 40px;">
                                    <!-- Name Column -->
                                    <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                <img src="{{ asset('/images/icons/browsers/'.formatBrowser($browser['value'])) }}.svg" class="width-4 height-4">
                                            </div>
                                            <div class="text-truncate">
                                                @if($browser['value'])
                                                    {{ $browser['value'] }}
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Visitors Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;">
                                        {{ number_format($browser['visits'], 0, __('.'), __(',')) }}
                                    </div>
                                    
                                    <!-- Revenue Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;" class="text-{{ $browser['revenue'] > 0 ? 'success' : 'muted' }}">
                                        {{ $browser['revenue'] ? '$' . number_format($browser['revenue'], 2, __('.'), __(',')) : '-' }}
                                    </div>
                                </div>                                <!-- Smart layered progress bars positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 280px; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">                                    @php
                                        // Calculate percentages for comparison
                                        $visitorsPercentage = ($browser['visits'] / $totalVisitors) * 100;
                                        $maxBrowserRevenue = is_array($browsersWithRevenue) ? 
                                            max(array_column($browsersWithRevenue, 'revenue')) : 
                                            collect($browsersWithRevenue)->max('revenue');
                                        $browserRevenuePercentage = $maxBrowserRevenue > 0 ? (($browser['revenue'] / $maxBrowserRevenue) * 100) : 0;
                                        
                                        // Determine which bar is smaller and should be on top
                                        $visitorsIsSmaller = $visitorsPercentage <= $browserRevenuePercentage;
                                        $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $browserRevenuePercentage;                                        $largerPercentage = $visitorsIsSmaller ? $browserRevenuePercentage : $visitorsPercentage;
                                        
                                        // Add spacing between smaller and larger bars (25% increase)
                                        $spacingGap = 0.5859375;
                                        $largerBarStartPosition = $smallerPercentage + $spacingGap;
                                    @endphp
                                      @if($browser['revenue'] > 0)
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
                    </div>
                @endif
            </div>            @if(count($browsersWithRevenue) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.browsers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('Details') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>    <div class="col-12 col-lg-6 p-2">
        <div class="card border-0 shadow-sm h-100">            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md d-flex justify-content-between align-items-center">
                        <div class="font-weight-medium py-1">{{ __('Operating systems') }}</div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="operatingSystemsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Technology') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right border-0 shadow" aria-labelledby="operatingSystemsDropdown">
                                <a class="dropdown-item" href="{{ route('stats.browsers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Browsers') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.operating_systems', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Operating systems') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.screen_resolutions', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Screen resolutions') }}</a>
                                <a class="dropdown-item" href="{{ route('stats.devices', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}">{{ __('Devices') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><div class="card-body">
                @if(count($operatingSystemsWithRevenue) == 0)
                    {{ __('No data') }}.
                @else                    <div class="list-group list-group-flush my-n3">                        <div class="list-group-item px-0 text-muted">
                            <div class="d-flex align-items-center">
                                <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">{{ __('Name') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Visitors') }}</div>
                                <div style="width: 100px; flex-shrink: 0; text-align: right;">{{ __('Revenue') }}</div>
                            </div>
                        </div>                        @foreach($operatingSystemsWithRevenue as $operatingSystem)                            <div class="list-group-item px-0 border-0 position-relative" style="z-index: 2; margin: 0;">
                                <div class="d-flex align-items-center position-relative" style="z-index: 3; min-height: 40px;">
                                    <!-- Name Column -->
                                    <div style="width: 280px; flex-shrink: 0; padding: 0 8px;">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                <img src="{{ asset('/images/icons/os/'.formatOperatingSystem($operatingSystem['value'])) }}.svg" class="width-4 height-4">
                                            </div>
                                            <div class="text-truncate">
                                                @if($operatingSystem['value'])
                                                    {{ $operatingSystem['value'] }}
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Visitors Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;">
                                        {{ number_format($operatingSystem['visits'], 0, __('.'), __(',')) }}
                                    </div>
                                    
                                    <!-- Revenue Column -->
                                    <div style="width: 100px; flex-shrink: 0; text-align: right;" class="text-{{ $operatingSystem['revenue'] > 0 ? 'success' : 'muted' }}">
                                        {{ $operatingSystem['revenue'] ? '$' . number_format($operatingSystem['revenue'], 2, __('.'), __(',')) : '-' }}
                                    </div>
                                </div>                                <!-- Smart layered progress bars positioned behind content -->
                                <div class="position-absolute" style="top: 50%; left: 0; width: 280px; height: 32px; transform: translateY(-50%); z-index: 1; pointer-events: none;">                                    @php
                                        // Calculate percentages for comparison
                                        $visitorsPercentage = ($operatingSystem['visits'] / $totalVisitors) * 100;
                                        $maxOSRevenue = is_array($operatingSystemsWithRevenue) ? 
                                            max(array_column($operatingSystemsWithRevenue, 'revenue')) : 
                                            collect($operatingSystemsWithRevenue)->max('revenue');
                                        $osRevenuePercentage = $maxOSRevenue > 0 ? (($operatingSystem['revenue'] / $maxOSRevenue) * 100) : 0;
                                        
                                        // Determine which bar is smaller and should be on top
                                        $visitorsIsSmaller = $visitorsPercentage <= $osRevenuePercentage;
                                        $smallerPercentage = $visitorsIsSmaller ? $visitorsPercentage : $osRevenuePercentage;                                        $largerPercentage = $visitorsIsSmaller ? $osRevenuePercentage : $visitorsPercentage;
                                        
                                        // Add spacing between smaller and larger bars
                                        $spacingGap = 0.46875;
                                        $largerBarStartPosition = $smallerPercentage + $spacingGap;
                                    @endphp
                                      @if($operatingSystem['revenue'] > 0)
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
                    </div>
                @endif
            </div>            @if(count($operatingSystemsWithRevenue) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.operating_systems', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('Details') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif        </div>    </div>
</div>

<div class="row m-n2">
    <div class="col-12 col-lg-12 p-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Events') }}</div></div>
                </div>
            </div>
            <div class="card-body">
                @if(count($events) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="row align-items-center">
                                <div class="col">
                                    {{ __('Name') }}
                                </div>
                                <div class="col-auto">
                                    {{ __('Completions') }}
                                </div>
                            </div>
                        </div>

                        @foreach($events as $event)
                            <div class="list-group-item px-0">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="text-truncate">
                                                {{ explode(':', $event->value)[0] }}
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                            <div class="d-flex align-items-center justify-content-end {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                                @if(!empty(explode(':', $event->value)[1]) || !empty(explode(':', $event->value)[2]))
                                                    <span class="badge badge-secondary {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                        @if(!empty(explode(':', $event->value)[1]))
                                                            {{ number_format((explode(':', $event->value)[1] * $event->count), 2, __('.'), __(',')) }}
                                                        @endif

                                                        @if(!empty(explode(':', $event->value)[2]))
                                                            {{ explode(':', $event->value)[2] }}
                                                        @endif
                                                    </span>
                                                @endif

                                                {{ number_format($event->count, 0, __('.'), __(',')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(count($events) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.events', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('Details') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>
</div>
