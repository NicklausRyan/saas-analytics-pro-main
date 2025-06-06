{{-- @section('site_title', formatTitle([__('API'), config('settings.title')])) --}}

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @include('shared.message')

        <div class="form-group">
            <label for="i-api-token">{{ __('API key') }}</label>
            <div class="input-group">
                <input type="text" id="i-api-token" class="form-control" value="{{ $user->api_token }}" readonly>
                <div class="input-group-append">
                    <div class="btn btn-primary" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-api-token">{{ __('Copy') }}</div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal" data-action="{{ route('account.api') }}" data-button="btn btn-danger" data-title="{{ __('Regenerate') }}" data-text="{{ __('Are you sure you want to regenerate your API key?') }}">{{ __('Regenerate') }}</button>
    </div>
</div>