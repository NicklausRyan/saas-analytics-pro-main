<?php
// Debug script to trace installation flow
require_once 'vendor/autoload.php';

// Load the Laravel application
$app = require_once 'bootstrap/app.php';

// Boot the application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Debug Installation Flow ===\n";

echo "1. Environment Variables:\n";
echo "   APP_INSTALLED: " . (env('APP_INSTALLED') ? 'true' : 'false') . "\n";
echo "   DB_DATABASE: " . env('DB_DATABASE') . "\n";

echo "\n2. HomeController Logic:\n";
if (!env('DB_DATABASE')) {
    echo "   -> Would redirect to install (DB_DATABASE is empty)\n";
} else {
    echo "   -> DB_DATABASE is set, continuing...\n";
    
    // Check if user is authenticated
    if (Auth::check()) {
        echo "   -> User is authenticated, would redirect to dashboard\n";
    } else {
        echo "   -> User is NOT authenticated\n";
        
        // Check custom site index
        if (config('settings.index')) {
            echo "   -> Custom site index set, would redirect\n";
        } else {
            echo "   -> No custom site index, would show home page\n";
        }
    }
}

echo "\n3. Install Middleware Logic:\n";
if (!env('APP_INSTALLED')) {
    echo "   -> APP_INSTALLED is false, would allow install routes\n";
} else {
    echo "   -> APP_INSTALLED is true, would redirect to home from install routes\n";
}

echo "\n4. Current Route Resolution:\n";
// Try to understand which routes are being matched
try {
    $request = \Illuminate\Http\Request::create('/', 'GET');
    $response = $app->handle($request);
    echo "   -> Request handled, status: " . $response->getStatusCode() . "\n";
    
    if ($response->isRedirect()) {
        echo "   -> Response is a redirect to: " . $response->headers->get('Location') . "\n";
    }
} catch (Exception $e) {
    echo "   -> Error handling request: " . $e->getMessage() . "\n";
}
