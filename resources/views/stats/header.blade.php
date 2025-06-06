@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('home'), 'title' => __('Home')],
    ['title' => __('Stats')]
]])

<div class="d-flex align-items-end justify-content-between mb-3">
    <!-- Left side: Domain and Realtime -->
    <div class="d-flex align-items-center">        <!-- Domain Section -->
        <div class="d-flex align-items-center border rounded px-3 py-2 bg-white shadow-sm {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
            <img src="https://icons.duckduckgo.com/ip3/{{ $website->domain }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
            <a href="{{ route('stats.overview', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-decoration-none text-dark">
                <h1 class="h2 mb-0 text-truncate" style="font-size: 1.25rem;">{{ $website->domain }}</h1>
            </a>
        </div><!-- Realtime Button -->
        <div>
            <a href="{{ route('stats.realtime', ['id' => $website->domain, 'from' => $range['from'], 'to' => $range['to']]) }}" class="d-flex align-items-center px-3 py-2 text-decoration-none border rounded bg-white shadow-sm {{ Route::currentRouteName() == 'stats.realtime' ? 'text-success' : 'text-secondary' }}">
                <span class="d-flex align-items-center position-relative width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                    <div class="pulsating-circle position-absolute width-2 height-2"></div>
                </span>
                <span class="font-weight-medium">Realtime</span>
            </a>
        </div>
    </div>
    
    <!-- Right side: Timeframe and Kebab Menu -->
    <div class="d-flex align-items-center">
        <!-- Timeframe Picker -->
        <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">            @if(Route::currentRouteName() == 'stats.realtime')
                <div class="text-muted cursor-default d-flex align-items-center px-3 py-2 border rounded bg-white shadow-sm">
                    <div class="d-flex align-items-center text-muted">
                        @include('icons.schedule', ['class' => 'fill-current width-4 height-4 flex-shrink-0'])&#8203;

                        <span class="d-none d-lg-block text-nowrap {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                            {{ __('Last :seconds seconds', ['seconds' => 60]) }}
                        </span>
                    </div>
                </div>            @else
                <a href="#" class="text-secondary text-decoration-none d-flex align-items-center px-3 py-2 border rounded bg-white shadow-sm" id="date-range-selector">
                    <div class="d-flex align-items-center cursor-pointer">
                        @include('icons.date-range', ['class' => 'fill-current width-4 height-4 flex-shrink-0'])&#8203;

                        <span class="{{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }} d-none d-lg-block text-nowrap" id="date-range-value">
                            @if($range['from'] == $range['to'])
                                @if(safeCreateFromFormat($range['from'])->isToday())
                                    {{ __('Today') }}
                                @elseif(safeCreateFromFormat($range['from'])->isYesterday())
                                    {{ __('Yesterday') }}
                                @else
                                    {{ safeCreateFromFormat($range['from'])->format('Y-m-d') }} - {{ safeCreateFromFormat($range['to'])->format('Y-m-d') }}
                                @endif
                            @else
                                @if(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(6)->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                    {{ __('Last :days days', ['days' => 7]) }}
                                @elseif(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(29)->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                    {{ __('Last :days days', ['days' => 30]) }}
                                @elseif(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'))
                                    {{ __('This month') }}
                                @elseif(safeCreateFromFormat($range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'))
                                    {{ __('Last month') }}
                                @elseif(safeCreateFromFormat($range['from'])->format('Y-m-d') == $website->created_at->format('Y-m-d') && safeCreateFromFormat($range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                    {{ __('All time') }}
                                @else
                                    {{ safeCreateFromFormat($range['from'])->format('Y-m-d') }} - {{ safeCreateFromFormat($range['to'])->format('Y-m-d') }}
                                @endif
                            @endif
                        </span>

                        @include('icons.expand-more', ['class' => 'flex-shrink-0 fill-current width-3 height-3 '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])
                    </div>
                </a>
            @endif

            <form method="GET" name="date-range" action="{{ route(Route::currentRouteName(), ['id' => $website->domain]) }}">
                <input name="from" type="hidden">
                <input name="to" type="hidden">
            </form>
        </div>
        
        <!-- Kebab Menu -->
        <div>
            <a href="#" class="text-secondary d-flex align-items-center px-3 py-2 text-decoration-none border rounded bg-white shadow-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;</a>

            @include('websites.partials.menu')
        </div>    </div>
</div>

{{-- @include('stats.menu') --}}

<script>
    'use strict';

    window.addEventListener('DOMContentLoaded', function () {
        document.querySelector('#date-range-selector') && document.querySelector('#date-range-selector').addEventListener('click', function (e) {
            e.preventDefault();
        });

        jQuery('#date-range-selector').daterangepicker({
            @php
                $utcOffset = \Carbon\Carbon::now()->utcOffset();
            @endphp

            ranges: {
                "{{ __('Today') }}": [moment().utcOffset({{ $utcOffset }}), moment().utcOffset({{ $utcOffset }})],
                "{{ __('Yesterday') }}": [moment().utcOffset({{ $utcOffset }}).subtract(1, 'days'), moment().utcOffset({{ $utcOffset }}).subtract(1, 'days')],
                "{{ __('Last :days days', ['days' => 7]) }}": [moment().utcOffset({{ $utcOffset }}).subtract(6, 'days'), moment().utcOffset({{ $utcOffset }})],
                "{{ __('Last :days days', ['days' => 30]) }}": [moment().utcOffset({{ $utcOffset }}).subtract(29, 'days'), moment().utcOffset({{ $utcOffset }})],
                "{{ __('This month') }}": [moment().utcOffset({{ $utcOffset }}).startOf('month'), moment().utcOffset({{ $utcOffset }}).endOf('month')],
                "{{ __('Last month') }}": [moment().utcOffset({{ $utcOffset }}).subtract(1, 'month').startOf('month'), moment().utcOffset({{ $utcOffset }}).subtract(1, 'month').endOf('month')],
                "{{ __('All time') }}": [moment('{{ $website->created_at->format('Y-m-d') }}'), moment().utcOffset({{ $utcOffset }})]
            },            locale: {
                direction: "{{ (__('lang_dir') == 'rtl' ? 'rtl' : 'ltr') }}",
                format: "YYYY-MM-DD",
                separator: " - ",
                applyLabel: "{{ __('Apply') }}",
                cancelLabel: "{{ __('Cancel') }}",
                customRangeLabel: "{{ __('Custom') }}",
                daysOfWeek: [
                    "{{ __('Su') }}",
                    "{{ __('Mo') }}",
                    "{{ __('Tu') }}",
                    "{{ __('We') }}",
                    "{{ __('Th') }}",
                    "{{ __('Fr') }}",
                    "{{ __('Sa') }}"
                ],
                monthNames: [
                    "{{ __('January') }}",
                    "{{ __('February') }}",
                    "{{ __('March') }}",
                    "{{ __('April') }}",
                    "{{ __('May') }}",
                    "{{ __('June') }}",
                    "{{ __('July') }}",
                    "{{ __('August') }}",
                    "{{ __('September') }}",
                    "{{ __('October') }}",
                    "{{ __('November') }}",
                    "{{ __('December') }}"
                ]            },            startDate : "{{ safeCreateFromFormat($range['from'])->format('Y-m-d') }}",
            endDate : "{{ safeCreateFromFormat($range['to'])->format('Y-m-d') }}",
            opens: "{{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}",
            applyClass: "btn-primary",
            cancelClass: "btn-secondary",
            linkedCalendars: false,
            alwaysShowCalendars: true
        });

        jQuery('#date-range-selector').on('apply.daterangepicker', function (ev, picker) {
            document.querySelector('input[name="from"]').value = picker.startDate.format('YYYY-MM-DD');
            document.querySelector('input[name="to"]').value = picker.endDate.format('YYYY-MM-DD');

            document.querySelector('form[name="date-range"]').submit();
        });

        jQuery('#date-range-selector').on('hide.daterangepicker', function (ev, picker) {
            document.querySelector('#date-range-selector').classList.remove('active');
        });

        jQuery('#date-range-selector').on('show.daterangepicker', function (ev, picker) {
            document.querySelector('#date-range-selector').classList.add('active');
        });
    });
</script>
