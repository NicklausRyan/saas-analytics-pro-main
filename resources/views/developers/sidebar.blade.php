@php    // Build developers menu structure based on the original combined page sections
    $menu = [
        route('developers') . '#stats-show' => [
            'icon' => 'bar-chart',
            'title' => __('Show Stats')
        ],
        route('developers') . '#websites-list' => [
            'icon' => 'website',
            'title' => __('List')
        ],
        route('developers') . '#websites-store' => [
            'icon' => 'add',
            'title' => __('Store')
        ],
        route('developers') . '#websites-update' => [
            'icon' => 'edit',
            'title' => __('Update')
        ],
        route('developers') . '#websites-delete' => [
            'icon' => 'delete',
            'title' => __('Delete')
        ],
        route('developers') . '#account-show' => [
            'icon' => 'portrait',
            'title' => __('Show Account')
        ]
    ];
    
    // Function to check if current route matches menu item
    function isActiveDeveloperRoute($routeUrl) {
        $currentUrl = request()->url();
        $currentRoute = Route::currentRouteName();
        
        // For developers page, check the fragment/hash
        if ($currentRoute === 'developers') {
            return true; // Since all sections are on the same page, we'll handle active states with JavaScript
        }
        
        return false;
    }
@endphp

<div class="p-3">
    @foreach ($menu as $key => $value)
        <div class="mb-3">
            <a class="btn w-100 d-flex align-items-center justify-content-start text-left py-2 px-3 @if (isActiveDeveloperRoute($key)) btn-primary @else btn-outline-secondary @endif developer-nav-btn" href="{{ $key }}" data-section="{{ explode('#', $key)[1] ?? '' }}">
                <span class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                    @include('icons.' . $value['icon'], ['class' => 'fill-current width-4 height-4'])
                </span>                <span class="flex-grow-1 text-truncate">
                    {{ $value['title'] }}
                </span>
            </a>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle sidebar navigation for single-page sections
    const navButtons = document.querySelectorAll('.developer-nav-btn');
    const sections = document.querySelectorAll('.content-section');
    
    // Function to show specific section
    function showSection(sectionId) {
        // Hide all sections
        sections.forEach(section => {
            section.style.display = 'none';
        });
        
        // Show target section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.style.display = 'block';
        }
        
        // Update active button
        navButtons.forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-secondary');
        });
        
        // Set active button
        const activeBtn = document.querySelector(`[data-section="${sectionId}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('btn-outline-secondary');
            activeBtn.classList.add('btn-primary');
        }
    }
    
    // Handle navigation clicks
    navButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            if (section) {
                showSection(section);
                // Update URL hash without scrolling
                history.pushState(null, null, '#' + section);
            }
        });
    });
    
    // Handle initial load and browser back/forward
    function handleHashChange() {
        const hash = window.location.hash.replace('#', '');
        if (hash) {
            showSection(hash);
        } else {
            // Default to first section (stats-show)
            showSection('stats-show');
        }
    }
    
    // Listen for hash changes
    window.addEventListener('hashchange', handleHashChange);
    
    // Handle initial load
    handleHashChange();
});
</script>
