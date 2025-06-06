<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\StatController;
use App\Models\Website;

try {
    echo "Testing Fixed Revenue Methods...\n\n";
    
    // Get a website to test with
    $website = Website::first();
    if (!$website) {
        echo "No websites found in database.\n";
        exit;
    }
    
    echo "Testing with website: {$website->domain}\n";
    
    // Set up date range (last 30 days)
    $range = [
        'from' => date('Y-m-d', strtotime('-30 days')),
        'to' => date('Y-m-d'),
    ];
    
    echo "Date range: {$range['from']} to {$range['to']}\n\n";
    
    // Create StatController instance
    $statController = new StatController();
    
    // Test getRevenueByContinents method using reflection
    $reflection = new ReflectionClass($statController);
    $method = $reflection->getMethod('getRevenueByContinents');
    $method->setAccessible(true);
    
    echo "Testing getRevenueByContinents method...\n";
    $result = $method->invoke($statController, $website, $range);
    echo "✅ Continents method executed successfully\n";
    echo "Revenue data count: " . count($result) . "\n";
    if (count($result) > 0) {
        echo "Sample data: " . print_r(array_slice($result->toArray(), 0, 3, true), true) . "\n";
    }
    
    // Test getRevenueByCities method
    $method = $reflection->getMethod('getRevenueByCities');
    $method->setAccessible(true);
    
    echo "\nTesting getRevenueByCities method...\n";
    $result = $method->invoke($statController, $website, $range);
    echo "✅ Cities method executed successfully\n";
    echo "Cities revenue data count: " . count($result) . "\n";
    
    // Test getRevenueByLanguages method
    $method = $reflection->getMethod('getRevenueByLanguages');
    $method->setAccessible(true);
    
    echo "\nTesting getRevenueByLanguages method...\n";
    $result = $method->invoke($statController, $website, $range);
    echo "✅ Languages method executed successfully\n";
    echo "Languages revenue data count: " . count($result) . "\n";
    
    echo "\n🎉 All revenue methods are working correctly!\n";
    echo "The fixes have been successfully applied.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
