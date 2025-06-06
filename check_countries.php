<?php
// Debug script to check countries data in database

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING ACTUAL COUNTRIES DATA ===\n";

// Check stats table for country data
$countryStats = DB::table('stats')
    ->where('name', 'country')
    ->select('value', 'count', 'date', 'website_id')
    ->orderBy('date', 'desc')
    ->limit(20)
    ->get();

echo "Recent country stats entries:\n";
foreach ($countryStats as $stat) {
    echo "Value: '{$stat->value}' | Count: {$stat->count} | Date: {$stat->date} | Website: {$stat->website_id}\n";
}

echo "\n=== CHECKING WEBSITE DATA ===\n";
$websites = DB::table('websites')->select('id', 'domain')->get();
foreach ($websites as $website) {
    echo "Website ID: {$website->id} | Domain: {$website->domain}\n";
}

echo "\n=== TESTING formatCountryName FUNCTION ===\n";
require_once 'app/Helpers/format.php';

// Test various country value formats
$testValues = ['US:United States', 'FR:France', 'GB:United Kingdom', 'Unknown', '', 'US', 'InvalidFormat'];

foreach ($testValues as $testValue) {
    $result = formatCountryName($testValue);
    echo "Input: '{$testValue}' -> Output: '{$result}'\n";
}
