<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

/**
 * The AdminViewController class provides utility methods for rendering admin views with consistent layout
 */
class AdminViewController
{
    /**
     * Render an admin view with proper layout and sidebar
     *
     * @param string $view The view name (without 'admin.' prefix)
     * @param array $data Data to pass to the view
     * @return \Illuminate\View\View
     */
    public static function render($view, $data = [])
    {
        // Add the admin prefix to the view name if not already present
        if (strpos($view, 'admin.') !== 0) {
            $view = 'admin.' . $view;
        }
        
        // Ensure we haven't already applied a layout
        if (strpos($view, 'admin.layouts.') === 0) {
            return View::make($view, $data);
        }
          // We can now directly return the view since our AdminViewServiceProvider
        // will automatically wrap it with the admin layout
        return View::make($view, $data);
    }
}
