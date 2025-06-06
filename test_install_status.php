<?php
// Test install status and environment variables
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$app->instance('request', $request);

echo "=== Environment Variables Test ===\n";
echo "APP_INSTALLED: " . (env('APP_INSTALLED') ? 'true' : 'false') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE') . "\n";
echo "DB_HOST: " . env('DB_HOST') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME') . "\n";

echo "\n=== Database Connection Test ===\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "Database connection: SUCCESS\n";
    
    // Test if we can query the database
    $result = DB::select('SELECT COUNT(*) as count FROM users');
    echo "User count: " . $result[0]->count . "\n";
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "\n";
}

echo "\n=== Install Middleware Test ===\n";
$installMiddleware = new App\Http\Middleware\InstallMiddleware();
echo "APP_INSTALLED check: " . (env('APP_INSTALLED') ? 'App is installed' : 'App is NOT installed') . "\n";

echo "\n=== Home Controller Logic Test ===\n";
echo "DB_DATABASE check: " . (env('DB_DATABASE') ? 'Database configured' : 'Database NOT configured') . "\n";
echo "Auth check: " . (Auth::check() ? 'User logged in' : 'User NOT logged in') . "\n";

// Test the actual redirect logic
if (!env('DB_DATABASE')) {
    echo "Would redirect to: install\n";
} elseif (Auth::check()) {
    echo "Would redirect to: dashboard\n";
} else {
    echo "Would show: home page\n";
}
