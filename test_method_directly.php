<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\StatController;
use App\Models\Website;

echo "Testing the fixed getRevenueByScreenResolution method...\n";

try {
    // Create a mock website object
    $website = new \stdClass();
    $website->id = 1;
    
    // Create controller and test the method
    $controller = new StatController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getRevenueByScreenResolution');
    $method->setAccessible(true);
    
    $range = [
        'from' => '2025-05-01',
        'to' => '2025-05-30'
    ];
    
    echo "Calling getRevenueByScreenResolution...\n";
    $result = $method->invoke($controller, $website, $range);
    
    echo "✅ SUCCESS! The method executed without database errors.\n";
    echo "Result count: " . count($result) . " items\n";
    
    if (!empty($result)) {
        echo "Sample result:\n";
        echo json_encode($result[0], JSON_PRETTY_PRINT) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
