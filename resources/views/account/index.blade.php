{{--
    This file is no longer used.
    
    The account index now defaults to showing the Profile section with sidebar navigation
    instead of the card-based navigation that was previously displayed here.
    
    The AccountController::index() method now returns the 'profile' view through the container.
    Users can navigate between account sections using the sidebar.
    
    Original card-based content backed up as index.blade.php.bak
--}}

@section('site_title', formatTitle([__('Account'), config('settings.title')]))

{{-- 
    This view should not be reached in normal operation since AccountController::index() 
    now returns the profile view directly. If you see this, there may be a routing issue.
--}}

<div class="alert alert-info">
    <strong>Note:</strong> The account page now shows the Profile section by default. 
    Use the sidebar to navigate between different account sections.
</div>
