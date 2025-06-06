@section('site_title', formatTitle([__('Dashboard'), __('Admin'), config('settings.title')]))

<div class="d-flex">
    <div class="flex-grow-1">
        <h1 class="h2 mb-0">{{ __('Dashboard') }}</h1>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12 col-sm-6 col-md-3 mt-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex">
                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                    @include('icons.account-circle', ['class' => 'fill-current width-5 height-5'])
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <div class="text-muted">
                        {{ __('Users') }}
                    </div>
                    <div class="font-weight-bold h4 mb-0">
                        {{ number_format($stats['users'], 0, __('.'), __(',')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mt-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex">
                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                    @include('icons.website', ['class' => 'fill-current width-5 height-5'])
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <div class="text-muted">
                        {{ __('Websites') }}
                    </div>
                    <div class="font-weight-bold h4 mb-0">
                        {{ number_format($stats['websites'], 0, __('.'), __(',')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mt-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex">
                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                    @include('icons.package', ['class' => 'fill-current width-5 height-5'])
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <div class="text-muted">
                        {{ __('Plans') }}
                    </div>
                    <div class="font-weight-bold h4 mb-0">
                        {{ number_format($stats['plans'], 0, __('.'), __(',')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mt-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex">
                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                    @include('icons.credit-card', ['class' => 'fill-current width-5 height-5'])
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <div class="text-muted">
                        {{ __('Payments') }}
                    </div>
                    <div class="font-weight-bold h4 mb-0">
                        {{ number_format($stats['payments'], 0, __('.'), __(',')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
