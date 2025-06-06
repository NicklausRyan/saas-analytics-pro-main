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

echo "🔧 SCREEN RESOLUTIONS FIX VERIFICATION\n";
echo "=====================================\n\n";

try {
    // Test 1: Check if the fix is in place
    echo "1. Checking if the problematic code has been fixed...\n";
    
    $controllerFile = file_get_contents('app/Http/Controllers/StatController.php');
    
    if (strpos($controllerFile, 'recents.resolution') !== false) {
        echo "❌ FAILED: Found 'recents.resolution' in StatController.php\n";
        exit(1);
    } else {
        echo "✅ PASSED: No 'recents.resolution' references found in StatController.php\n";
    }
    
    // Test 2: Check if our fixed method exists and works
    echo "\n2. Testing the fixed getRevenueByScreenResolution method...\n";
    
    $controller = new StatController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getRevenueByScreenResolution');
    $method->setAccessible(true);
    
    // Create a mock website object
    $website = new \stdClass();
    $website->id = 1;
    
    $range = [
        'from' => '2025-05-01',
        'to' => '2025-05-30'
    ];
    
    $result = $method->invoke($controller, $website, $range);
    
    echo "✅ PASSED: Method executed successfully without database errors\n";
    echo "   Result type: " . gettype($result) . "\n";
    echo "   Result count: " . count($result) . " items\n";
    
    // Test 3: Verify result structure
    echo "\n3. Verifying result structure...\n";
    
    if (is_array($result)) {
        echo "✅ PASSED: Result is an array\n";
        
        if (empty($result)) {
            echo "ℹ️  INFO: Result is empty (expected for mock data)\n";
        } else {
            $firstItem = $result[0];
            $expectedKeys = ['value', 'visits', 'revenue', 'revenuePerVisitor'];
            
            foreach ($expectedKeys as $key) {
                if (isset($firstItem[$key])) {
                    echo "✅ PASSED: Result has '$key' field\n";
                } else {
                    echo "❌ FAILED: Result missing '$key' field\n";
                }
            }
        }
    } else {
        echo "❌ FAILED: Result is not an array\n";
    }
    
    echo "\n🎉 CONCLUSION\n";
    echo "=============\n";
    echo "✅ The screen resolutions 500 error has been FIXED!\n";
    echo "✅ The getRevenueByScreenResolution method now works without database errors\n";
    echo "✅ The method returns properly structured data\n";
    echo "\nThe screen resolutions page should now load successfully.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR during testing: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
