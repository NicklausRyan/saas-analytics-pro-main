@php
    // Build account menu structure
    $menu = [
        route('account') => [
            'icon' => 'portrait',
            'title' => __('Profile')
        ],
        route('account.security') => [
            'icon' => 'lock',
            'title' => __('Security')
        ],
        route('account.preferences') => [
            'icon' => 'tune',
            'title' => __('Preferences')
        ],
        route('account.plan') => [
            'icon' => 'package',
            'title' => __('Plan')
        ]
    ];
    
    // Add payments link if payment processors are enabled
    if(paymentProcessors()) {
        $menu[route('account.payments')] = [
            'icon' => 'credit-card',
            'title' => __('Payments')
        ];
    }
    
    // Add API and Delete options
    $menu[route('account.api')] = [
        'icon' => 'code',
        'title' => __('API')
    ];
    
    $menu[route('account.delete')] = [
        'icon' => 'delete',
        'title' => __('Delete')
    ];
      // Function to check if current route matches menu item
    function isActiveRoute($routeUrl) {
        $currentUrl = request()->url();
        $currentRoute = Route::currentRouteName();
        
        // Exact match for profile page
        if ($routeUrl === route('account')) {
            return $currentRoute === 'account' || $currentRoute === 'account.profile';
        }
        
        // For other routes, check if the current route name starts with the route name of the menu item
        if ($routeUrl === route('account.security')) {
            return $currentRoute === 'account.security';
        } elseif ($routeUrl === route('account.preferences')) {
            return $currentRoute === 'account.preferences';
        } elseif ($routeUrl === route('account.plan')) {
            return $currentRoute === 'account.plan';
        } elseif ($routeUrl === route('account.payments')) {
            return $currentRoute === 'account.payments';
        } elseif ($routeUrl === route('account.api')) {
            return $currentRoute === 'account.api';
        } elseif ($routeUrl === route('account.delete')) {
            return $currentRoute === 'account.delete';
        }
        
        return false;
    }
@endphp

<div class="p-3">
    @foreach ($menu as $key => $value)
        <div class="mb-3">
            <a class="btn w-100 d-flex align-items-center justify-content-start text-left py-2 px-3 @if (isActiveRoute($key)) btn-primary @else btn-outline-secondary @endif" href="{{ $key }}">
                <span class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                    @include('icons.' . $value['icon'], ['class' => 'fill-current width-4 height-4'])
                </span>
                <span class="flex-grow-1 text-truncate">{{ $value['title'] }}</span>
            </a>
        </div>
    @endforeach
</div>
