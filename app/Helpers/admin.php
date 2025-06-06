<?php

/**
 * Get the admin sidebar items.
 *
 * @return array
 */
function getAdminSidebarItems() {
    // Get all admin blade views
    $path = resource_path('views/admin');
    $files = [];
    
    if (is_dir($path)) {
        $files = array_diff(scandir($path), ['.', '..', 'sidebar.blade.php', 'container.blade.php']);
        
        // Remove .blade.php extension and sort
        $files = array_map(function($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $files);
        
        sort($files);
    }
    
    // Define menu structure with icons and display names
    $menuItems = [
        'dashboard' => [
            'icon' => 'dashboard',
            'name' => 'Dashboard'
        ],
        'websites' => [
            'icon' => 'website',
            'name' => 'Websites'
        ],
        'users' => [
            'icon' => 'account-circle',
            'name' => 'Users'
        ],
        'pages' => [
            'icon' => 'description',
            'name' => 'Pages'
        ],
        'plans' => [
            'icon' => 'package',
            'name' => 'Plans'
        ],
        'payments' => [
            'icon' => 'credit-card',
            'name' => 'Payments'
        ],
        'settings' => [
            'icon' => 'settings',
            'name' => 'Settings'
        ]
    ];
    
    // Merge found files with predefined structure
    $sidebarItems = [];
    foreach ($files as $file) {
        if (isset($menuItems[$file])) {
            $sidebarItems[$file] = $menuItems[$file];
        } else {
            // For files without predefined settings, create a default entry
            $name = ucfirst(str_replace(['-', '_'], ' ', $file));
            $sidebarItems[$file] = [
                'icon' => 'circle',
                'name' => $name
            ];
        }
    }
    
    return $sidebarItems;
}

/**
 * Check if the current route is an admin route
 * 
 * @return bool
 */
function isAdminRoute() {
    return request()->segment(1) === 'admin';
}

/**
 * Check if specific admin route is active
 * 
 * @param string $route The route name without the admin. prefix
 * @return bool
 */
function isActiveAdminRoute($route) {
    $currentRoute = request()->segment(2);
    
    // Special case for dashboard route
    if ($currentRoute === null && $route === 'dashboard') {
        return true;
    }
    
    // Special case for settings route with parameters
    if ($currentRoute === 'settings' && strpos($route, 'settings') === 0) {
        $settingsParam = request()->segment(3);
        $routeParts = explode('.', $route);
        
        if (count($routeParts) > 1 && $settingsParam === $routeParts[1]) {
            return true;
        }
    }
    
    return $currentRoute === $route;
}

/**
 * Render an admin view with the appropriate container
 * 
 * @param string $view The view name (without admin. prefix)
 * @param array $data The view data
 * @return \Illuminate\View\View
 */
function renderAdminView($view, $data = []) {
    if (strpos($view, 'admin.') !== 0) {
        $view = 'admin.' . $view;
    }
    
    return view('admin.container', ['view' => $view])->with($data);
}
