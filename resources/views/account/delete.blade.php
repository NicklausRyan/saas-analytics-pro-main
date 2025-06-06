{{-- @section('site_title', formatTitle([__('Delete'), config('settings.title')])) --}}

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @include('shared.message')

        <div class="alert alert-danger" role="alert">
            {{ __('Deleting your account is permanent, and will remove all the data associated with it.') }}
        </div>

        <form action="{{ route('account.destroy') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i-current-password">{{ __('Current password') }}</label>
                <input type="password" name="current_password" id="i-current-password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}">
                @if ($errors->has('current_password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('current_password') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-danger">{{ __('Delete') }}</button>
        </form>
    </div>
</div>