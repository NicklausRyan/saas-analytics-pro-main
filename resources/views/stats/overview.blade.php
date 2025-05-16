@section('site_title', formatTitle([$website->domain, __('Overview'), config('settings.title')]))

<div class="card border-0 rounded-top shadow-sm mb-3 overflow-hidden" id="trend-chart-container">
    <div class="px-3 border-bottom">
        <div class="row">
            <!-- Title -->
            <div class="col-12 col-md-auto d-none d-xl-flex align-items-center border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                <div class="px-2 py-4 d-flex">
                    <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                        <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                        @include('icons.assesment', ['class' => 'fill-current width-5 height-5'])
                    </div>
                </div>
            </div>            <div class="col-12 col-md">
                <div class="row">
                    <!-- Bounce Rate -->
                    <div class="col-12 col-md-4 border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md')  }}">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-info rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="bounce-rate-legend"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            <div class="text-truncate">{{ __('Bounce Rate') }}</div>
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('The percentage of visitors who navigate away from your site after viewing only one page.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0">{{ number_format($bounceRate * 100, 1, __('.'), __(',')) }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Average Session Duration -->
                    <div class="col-12 col-md-4 border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md')  }}">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-success rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="session-duration-legend"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            <div class="text-truncate">{{ __('Session Time') }}</div>
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('The average amount of time that visitors spend on your site.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0">{{ floor($avgSessionDuration / 60) }}m {{ $avgSessionDuration % 60 }}s</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visitors -->
                    <div class="col-12 col-md-4">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-primary rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="visitors-legend"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            <div class="text-truncate">{{ __('Visitors') }}</div>
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('A visitor represents a page load of your website through direct access, or through a referrer.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>

                                    @include('stats.growth', ['growthCurrent' => $totalVisitors, 'growthPrevious' => $totalVisitorsOld])
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0">{{ number_format($totalVisitors, 0, __('.'), __(',')) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                <div class="row border-top">
                    <!-- Pageviews -->
                    <div class="col-12 col-md-4 border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md')  }}">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-danger rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="pageviews-legend"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            <div class="text-truncate">{{ __('Pageviews') }}</div>
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('A pageview represents a page load of your website.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>

                                    @include('stats.growth', ['growthCurrent' => $totalPageviews, 'growthPrevious' => $totalPageviewsOld])
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0">{{ number_format($totalPageviews, 0, __('.'), __(',')) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Revenue -->
                    <div class="col-12 col-md-4 border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md')  }}">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-success rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="revenue-legend"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            <div class="text-truncate">{{ __('Total Revenue') }}</div>
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('The total revenue generated during the selected period.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>

                                    @include('stats.growth', ['growthCurrent' => $totalRevenue, 'growthPrevious' => $totalRevenueOld])
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0">{{ number_format($totalRevenue, 2, __('.'), __(',')) }} {{ $primaryCurrency }}</div>
                                </div>
                            </div>
                        </div>                    </div>                    <!-- Revenue per visitor -->
                    <div class="col-12 col-md-4">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-warning rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="revenue-per-visitor-legend"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            <div class="text-truncate">{{ __('Revenue / Visitor') }}</div>
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('The average revenue generated per unique visitor.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>
                                </div>                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0">{{ number_format($revenuePerVisitor, 2, __('.'), __(',')) }} {{ $primaryCurrency }}</div>
                                </div>
                            </div>
                        </div>
                    </div>                </div>
                <div class="row border-top">
                    <!-- Conversion Rate -->
                    <div class="col-12">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-info rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="conversion-rate-legend"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            <div class="text-truncate">{{ __('Conversion Rate') }}</div>
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('The percentage of sessions that resulted in a revenue event.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0">{{ number_format($conversionRate * 100, 1, __('.'), __(',')) }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div style="height: 230px">
            <canvas id="trend-chart"></canvas>
        </div>
        <script>
            'use strict';

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
                };                const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');                const uniqueColor = window.getComputedStyle(document.getElementById('visitors-legend')).getPropertyValue('background-color');
                const pageViewsColor = window.getComputedStyle(document.getElementById('pageviews-legend')).getPropertyValue('background-color');
                const bounceRateColor = window.getComputedStyle(document.getElementById('bounce-rate-legend')).getPropertyValue('background-color');
                const sessionDurationColor = window.getComputedStyle(document.getElementById('session-duration-legend')).getPropertyValue('background-color');
                const revenueColor = window.getComputedStyle(document.getElementById('revenue-legend')).getPropertyValue('background-color');                const ctx = document.querySelector('#trend-chart').getContext('2d');
                const gradient1 = ctx.createLinearGradient(0, 0, 0, 300);
                gradient1.addColorStop(0, uniqueColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                gradient1.addColorStop(1, uniqueColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                const gradient2 = ctx.createLinearGradient(0, 0, 0, 300);
                gradient2.addColorStop(0, pageViewsColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                gradient2.addColorStop(1, pageViewsColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));
                
                const gradient3 = ctx.createLinearGradient(0, 0, 0, 300);
                gradient3.addColorStop(0, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                gradient3.addColorStop(1, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                let tooltipTitles = [
                    @foreach($visitorsMap as $date => $value)
                        @if($range['unit'] == 'hour')
                            '{{ \Carbon\Carbon::createFromFormat('H', $date)->format('H:i') }}',
                        @elseif($range['unit'] == 'day')
                            '{{ \Carbon\Carbon::parse($date)->format(__('Y-m-d')) }}',
                        @elseif($range['unit'] == 'month')
                            '{{ \Carbon\Carbon::parse($date)->format(__('Y-m')) }}',
                        @else
                            '{{ $date }}',
                        @endif
                    @endforeach
                ];                const lineOptions = {
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    hitRadius: 5,
                    pointHoverBorderWidth: 3,
                    lineTension: 0
                }                
                let trendChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach($pageviewsMap as $date => $value)
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
                            ],
                            backgroundColor: revenueColor.replace('rgb', 'rgba').replace(')', ', 0.6)'),
                            borderColor: revenueColor,
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
                            borderColor: uniqueColor,
                            pointBorderColor: uniqueColor,
                            pointBackgroundColor: uniqueColor,
                            pointHoverBackgroundColor: phBgColor,
                            pointHoverBorderColor: uniqueColor,
                            ...lineOptions,
                            order: 1                      
                        }, {
                            type: 'line',
                            label: '{{ __('Pageviews') }}',
                            data: [
                                @foreach($pageviewsMap as $date => $value)
                                    {{ $value }},
                                @endforeach
                            ],
                            fill: true,
                            backgroundColor: gradient2,
                            borderColor: pageViewsColor,
                            pointBorderColor: pageViewsColor,
                            pointBackgroundColor: pageViewsColor,
                            pointHoverBackgroundColor: phBgColor,
                            pointHoverBorderColor: pageViewsColor,
                            ...lineOptions,
                            order: 2
                        }]
                    },                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            y: {
                                position: 'left',
                                grid: {
                                    borderDash: [2, 2],
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function (value) {
                                        return formatNumber(value);
                                    }
                                }
                            },
                            y1: {
                                position: 'right',
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function (value) {
                                        return formatNumber(value) + ' {{ $primaryCurrency }}';
                                    }
                                },
                                beginAtZero: true
                            },
                            x: {
                                offset: true,
                                grid: {
                                    display: true,
                                    drawBorder: false,
                                    drawOnChartArea: false
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

                                boxPadding: 4,                                callbacks: {
                                    label: function (tooltipItem) {
                                        // Format revenue values with currency symbol
                                        if (tooltipItem.dataset.label === '{{ __('Revenue') }}') {
                                            return ' ' + tooltipItem.dataset.label + ': ' + parseFloat(tooltipItem.dataset.data[tooltipItem.dataIndex]).format(2, 3, '{{ __(',') }}').toString() + ' {{ $primaryCurrency }}';
                                        }
                                        return ' ' + tooltipItem.dataset.label + ': ' + parseFloat(tooltipItem.dataset.data[tooltipItem.dataIndex]).format(0, 3, '{{ __(',') }}').toString();
                                    },
                                    title: function (tooltipItem) {
                                        return tooltipTitles[tooltipItem[0].dataIndex];
                                    }
                                }
                            },
                        },
                        scales: {
                            x: {
                                display: true,
                                grid: {
                                    lineWidth: 0,
                                    tickLength: 0
                                },
                                ticks: {
                                    maxTicksLimit: @if($range['unit'] == 'day') 12 @else 15 @endif,
                                    padding: 10,
                                }
                            },
                            y: {
                                display: true,
                                beginAtZero: true,
                                grid: {
                                    tickLength: 0
                                },
                                ticks: {
                                    maxTicksLimit: 8,
                                    padding: 10,
                                    callback: function (value) {
                                        return commarize(value, 1000);
                                    }
                                }
                            },
                        }
                    }
                });

                // The time to wait before attempting to change the colors on first attempt
                let colorSchemeTimer = 500;

                // Update the chart colors when the color scheme changes
                const observer = (new MutationObserver(function (mutationsList, observer) {
                    for (const mutation of mutationsList) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            setTimeout(function () {                                const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');                                const visitorsColor = window.getComputedStyle(document.getElementById('visitors-legend')).getPropertyValue('background-color');
                                const pageViewsColor = window.getComputedStyle(document.getElementById('pageviews-legend')).getPropertyValue('background-color');
                                const bounceRateColor = window.getComputedStyle(document.getElementById('bounce-rate-legend')).getPropertyValue('background-color');
                                const sessionDurationColor = window.getComputedStyle(document.getElementById('session-duration-legend')).getPropertyValue('background-color');
                                const revenueColor = window.getComputedStyle(document.getElementById('revenue-legend')).getPropertyValue('background-color');                                const gradient1 = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient1.addColorStop(0, visitorsColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                                gradient1.addColorStop(1, visitorsColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                                const gradient2 = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient2.addColorStop(0, pageViewsColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                                gradient2.addColorStop(1, pageViewsColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));
                                
                                const gradient3 = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient3.addColorStop(0, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                                gradient3.addColorStop(1, revenueColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                                trendChart.data.datasets[0].backgroundColor = gradient1;
                                trendChart.data.datasets[0].borderColor = visitorsColor;
                                trendChart.data.datasets[0].pointBorderColor = visitorsColor;
                                trendChart.data.datasets[0].pointBackgroundColor = visitorsColor;
                                trendChart.data.datasets[0].pointHoverBackgroundColor = phBgColor;
                                trendChart.data.datasets[0].pointHoverBorderColor = visitorsColor;

                                trendChart.data.datasets[1].backgroundColor = gradient2;
                                trendChart.data.datasets[1].borderColor = pageViewsColor;
                                trendChart.data.datasets[1].pointBorderColor = pageViewsColor;
                                trendChart.data.datasets[1].pointBackgroundColor = pageViewsColor;                                trendChart.data.datasets[1].pointHoverBackgroundColor = phBgColor;
                                trendChart.data.datasets[1].pointHoverBorderColor = pageViewsColor;
                                
                                trendChart.data.datasets[2].backgroundColor = gradient3;
                                trendChart.data.datasets[2].borderColor = revenueColor;
                                trendChart.data.datasets[2].pointBorderColor = revenueColor;
                                trendChart.data.datasets[2].pointBackgroundColor = revenueColor;
                                trendChart.data.datasets[2].pointHoverBackgroundColor = phBgColor;
                                trendChart.data.datasets[2].pointHoverBorderColor = revenueColor;

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
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Pages') }}</div></div>
                </div>
            </div>
            <div class="card-body">
                @if(count($pages) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="row align-items-center">
                                <div class="col">
                                    {{ __('URL') }}
                                </div>
                                <div class="col-auto">
                                    {{ __('Pageviews') }}
                                </div>
                            </div>
                        </div>

                        @foreach($pages as $page)
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex text-truncate">
                                                <div class="text-truncate" dir="ltr">{{ $page->value }}</div> <a href="http://{{ $website->domain . $page->value }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-in-new', ['class' => 'fill-current width-3 height-3'])</a>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">

                                            <div>
                                                {{ number_format($page->count, 0, __('.'), __(',')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress height-1.25 w-100">
                                        <div class="progress-bar bg-danger rounded" role="progressbar" style="width: {{ (($page->count / $totalPageviews) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(count($pages) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.pages', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-6 p-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Referrers') }}</div></div>
                </div>
            </div>
            <div class="card-body">
                @if(count($referrers) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="row align-items-center">
                                <div class="col">
                                    {{ __('Website') }}
                                </div>
                                <div class="col-auto">
                                    {{ __('Visitors') }}
                                </div>
                            </div>
                        </div>

                        @foreach($referrers as $referrer)
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex text-truncate align-items-center">
                                            @if($referrer->value)
                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                    <img src="https://icons.duckduckgo.com/ip3/{{ $referrer->value }}.ico" rel="noreferrer" class="width-4 height-4">
                                                </div>

                                                <div class="d-flex text-truncate">
                                                    <div class="text-truncate" dir="ltr">{{ $referrer->value }}</div> <a href="http://{{ $referrer->value }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-in-new', ['class' => 'fill-current width-3 height-3'])</a>
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

                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                            <div>
                                                {{ number_format($referrer->count, 0, __('.'), __(',')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress height-1.25 w-100">
                                        <div class="progress-bar bg-primary rounded" role="progressbar" style="width: {{ (($referrer->count / $totalReferrers) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(count($referrers) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.referrers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>    <div class="col-12 col-lg-4 p-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Countries') }}</div></div>
                </div>
            </div>
            <div class="card-body">
                @if(count($countriesWithRevenue) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="row align-items-center">
                                <div class="col">
                                    {{ __('Name') }}
                                </div>
                                <div class="col-auto text-right">
                                    <div class="d-flex flex-column">
                                        <div>{{ __('Visitors') }}</div>
                                        <div>{{ __('Revenue') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach($countriesWithRevenue as $country)
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/countries/'. formatFlag($country['value'])) }}.svg" class="width-4 height-4"></div>
                                            <div class="text-truncate">
                                                @if(!empty(explode(':', $country['value'])[1]))
                                                    <a href="{{ route('stats.cities', ['id' => $website->domain, 'search' => explode(':', $country['value'])[0].':', 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-body" data-tooltip="true" title="{{ __(explode(':', $country['value'])[1]) }}">{{ explode(':', $country['value'])[1] }}</a>
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                            <div>
                                                {{ number_format($country['visits'], 0, __('.'), __(',')) }}
                                            </div>
                                            <div class="text-{{ $country['revenue'] > 0 ? 'success' : 'muted' }}">
                                                {{ $country['revenue'] ? number_format($country['revenue'], 2, __('.'), __(',')) . ' ' . $primaryCurrency : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress height-1.25 w-100">
                                        <div class="progress-bar bg-primary rounded" role="progressbar" style="width: {{ (($country['visits'] / $totalVisitors) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(count($countriesWithRevenue) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.countries', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>    <div class="col-12 col-lg-4 p-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Browsers') }}</div></div>
                </div>
            </div>
            <div class="card-body">
                @if(count($browsersWithRevenue) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="row align-items-center">
                                <div class="col">
                                    {{ __('Name') }}
                                </div>
                                <div class="col-auto text-right">
                                    <div class="d-flex flex-column">
                                        <div>{{ __('Visitors') }}</div>
                                        <div>{{ __('Revenue') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach($browsersWithRevenue as $browser)
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/browsers/'.formatBrowser($browser['value'])) }}.svg" class="width-4 height-4"></div>
                                            <div class="text-truncate">
                                                @if($browser['value'])
                                                    {{ $browser['value'] }}
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                            <div>
                                                {{ number_format($browser['visits'], 0, __('.'), __(',')) }}
                                            </div>
                                            <div class="text-{{ $browser['revenue'] > 0 ? 'success' : 'muted' }}">
                                                {{ $browser['revenue'] ? number_format($browser['revenue'], 2, __('.'), __(',')) . ' ' . $primaryCurrency : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress height-1.25 w-100">
                                        <div class="progress-bar bg-primary rounded" role="progressbar" style="width: {{ (($browser['visits'] / $totalVisitors) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(count($browsersWithRevenue) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.browsers', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>    <div class="col-12 col-lg-4 p-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Operating systems') }}</div></div>
                </div>
            </div>
            <div class="card-body">
                @if(count($operatingSystemsWithRevenue) == 0)
                    {{ __('No data') }}.
                @else
                    <div class="list-group list-group-flush my-n3">
                        <div class="list-group-item px-0 text-muted">
                            <div class="row align-items-center">
                                <div class="col">
                                    {{ __('Name') }}
                                </div>
                                <div class="col-auto text-right">
                                    <div class="d-flex flex-column">
                                        <div>{{ __('Visitors') }}</div>
                                        <div>{{ __('Revenue') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach($operatingSystemsWithRevenue as $operatingSystem)
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex text-truncate align-items-center">
                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/os/'.formatOperatingSystem($operatingSystem['value'])) }}.svg" class="width-4 height-4"></div>
                                            <div class="text-truncate">
                                                @if($operatingSystem['value'])
                                                    {{ $operatingSystem['value'] }}
                                                @else
                                                    {{ __('Unknown') }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                            <div>
                                                {{ number_format($operatingSystem['visits'], 0, __('.'), __(',')) }}
                                            </div>
                                            <div class="text-{{ $operatingSystem['revenue'] > 0 ? 'success' : 'muted' }}">
                                                {{ $operatingSystem['revenue'] ? number_format($operatingSystem['revenue'], 2, __('.'), __(',')) . ' ' . $primaryCurrency : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress height-1.25 w-100">
                                        <div class="progress-bar bg-primary rounded" role="progressbar" style="width: {{ (($operatingSystem['visits'] / $totalVisitors) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(count($operatingSystemsWithRevenue) > 0)
                <div class="card-footer bg-base-2 border-0">
                    <a href="{{ route('stats.operating_systems', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>

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
                    <a href="{{ route('stats.events', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                </div>
            @endif
        </div>
    </div>
</div>
