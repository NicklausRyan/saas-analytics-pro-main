<?php
/**
 * Admin Page Test Script
 * This script tests if admin pages are rendering correctly with the sidebar
 */

// Include Laravel's bootstrap
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';

// Get the kernel instance
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "Testing admin page rendering:\n";

// Create a request to test
$request = Illuminate\Http\Request::create('/admin', 'GET');

// Handle the request
$response = $kernel->handle($request);

// Check if the response contains the sidebar (indicating it's using the admin layout)
$content = $response->getContent();
$hasSidebar = strpos($content, 'admin-sidebar') !== false;

echo "Admin page rendered with sidebar: " . ($hasSidebar ? "YES" : "NO") . "\n";

// Clean up
$kernel->terminate($request, $response);
