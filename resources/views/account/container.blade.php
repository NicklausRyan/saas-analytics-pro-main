@extends('layouts.account')

@section('account_content')
    <div class="card border-0">
        <div class="card-header align-items-center bg-white border-bottom">
            <div class="row">
                <div class="col">
                    <div class="font-weight-medium py-1">
                        @if($view === 'profile')
                            {{ __('Profile') }}
                        @elseif($view === 'security')
                            {{ __('Security') }}
                        @elseif($view === 'preferences')
                            {{ __('Preferences') }}
                        @elseif($view === 'plan')
                            {{ __('Plan') }}
                        @elseif($view === 'payments.list')
                            {{ __('Payments') }}
                        @elseif($view === 'payments.edit')
                            {{ __('Payment Details') }}
                        @elseif($view === 'payments.invoice')
                            {{ __('Invoice') }}
                        @elseif($view === 'api')
                            {{ __('API') }}
                        @elseif($view === 'delete')
                            {{ __('Delete Account') }}
                        @else
                            {{ __('Account') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('account.' . $view)
        </div>
    </div>
@endsection