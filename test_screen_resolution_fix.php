<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test the StatController method directly
use App\Http\Controllers\StatController;
use App\Models\Website;

try {
    echo "Testing screen resolution fix...\n";
    
    // Get the first website
    $website = Website::first();
    if (!$website) {
        echo "No websites found in database\n";
        exit(1);
    }
    
    echo "Testing with website: {$website->domain}\n";
    
    // Create controller instance
    $controller = new StatController();
    
    // Use reflection to access the private method
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getRevenueByScreenResolution');
    $method->setAccessible(true);
    
    // Test the method with a basic range
    $range = [
        'from' => '2025-05-01',
        'to' => '2025-05-30'
    ];
    
    $result = $method->invoke($controller, $website, $range);
    
    echo "Success! Method executed without errors.\n";
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
