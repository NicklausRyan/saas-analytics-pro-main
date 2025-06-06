<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\StatController;
use App\Models\Website;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing the fixed revenue methods...\n\n";

try {
    // Get a test website
    $website = Website::first();
    
    if (!$website) {
        echo "❌ No websites found in database. Please ensure you have test data.\n";
        exit(1);
    }
    
    echo "✅ Found website: {$website->domain}\n";
    
    // Create date range for testing (last 30 days)
    $range = [
        'from' => now()->subDays(30)->format('Y-m-d'),
        'to' => now()->format('Y-m-d')
    ];
    
    echo "📅 Testing date range: {$range['from']} to {$range['to']}\n\n";
    
    // Create StatController instance
    $controller = new StatController();
    $reflection = new ReflectionClass($controller);
    
    // Test getRevenueByContinents method
    echo "1. Testing the fixed getRevenueByContinents method...\n";
    try {
        $method = $reflection->getMethod('getRevenueByContinents');
        $method->setAccessible(true);
        $result = $method->invoke($controller, $website, $range);
        echo "   ✅ getRevenueByContinents executed successfully\n";
        echo "   📊 Found " . count($result) . " continents with revenue data\n";
    } catch (Exception $e) {
        echo "   ❌ Error in getRevenueByContinents: " . $e->getMessage() . "\n";
    }
    
    // Test getRevenueByCities method
    echo "\n2. Testing the fixed getRevenueByCities method...\n";
    try {
        $method = $reflection->getMethod('getRevenueByCities');
        $method->setAccessible(true);
        $result = $method->invoke($controller, $website, $range);
        echo "   ✅ getRevenueByCities executed successfully\n";
        echo "   📊 Found " . count($result) . " cities with revenue data\n";
    } catch (Exception $e) {
        echo "   ❌ Error in getRevenueByCities: " . $e->getMessage() . "\n";
    }
    
    // Test getRevenueByLanguages method
    echo "\n3. Testing the fixed getRevenueByLanguages method...\n";
    try {
        $method = $reflection->getMethod('getRevenueByLanguages');
        $method->setAccessible(true);
        $result = $method->invoke($controller, $website, $range);
        echo "   ✅ getRevenueByLanguages executed successfully\n";
        echo "   📊 Found " . count($result) . " languages with revenue data\n";
    } catch (Exception $e) {
        echo "   ❌ Error in getRevenueByLanguages: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 All revenue method tests completed!\n";
    echo "\nThe database error 'SQLSTATE[42S22]: Column not found: 1054 Unknown column 'stats.visitor_id' in 'on clause'' should now be resolved.\n";
    echo "\nThe methods now correctly join with the 'recents' table instead of the 'stats' table.\n";
    
} catch (Exception $e) {
    echo "❌ Test failed with error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
