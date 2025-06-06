<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Website;
use App\Models\Revenue;
use App\Http\Controllers\StatController;
use Carbon\Carbon;

echo "Testing Revenue by Country Fix\n";
echo "==============================\n\n";

// Get first website
$website = Website::first();
if (!$website) {
    echo "No websites found!\n";
    exit(1);
}

echo "Testing with website: {$website->domain}\n";

// Set up date range for last 30 days
$range = [
    'from' => Carbon::now()->subDays(30)->format('Y-m-d'),
    'to' => Carbon::now()->format('Y-m-d')
];

echo "Date range: {$range['from']} to {$range['to']}\n";

// Check revenue records for this website
$revenueCount = Revenue::where('website_id', $website->id)
    ->whereBetween('date', [$range['from'], $range['to']])
    ->count();

echo "Revenue records for this website in range: {$revenueCount}\n";

// Check recent records for this website
$recentCount = \App\Models\Recent::where('website_id', $website->id)
    ->whereBetween('created_at', [$range['from'] . ' 00:00:00', $range['to'] . ' 23:59:59'])
    ->count();

echo "Recent visitor records for this website in range: {$recentCount}\n";

// Test the SQL query directly
echo "\nTesting revenue by country query...\n";

try {
    $revenueByCountry = \DB::table('revenue')
        ->selectRaw('
            CASE 
                WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(recents.country, ":", 2), ":", -1) IS NOT NULL 
                THEN SUBSTRING_INDEX(SUBSTRING_INDEX(recents.country, ":", 2), ":", -1)
                ELSE "Unknown"
            END as country_value, 
            SUM(revenue.amount) as total_revenue,
            COUNT(*) as record_count
        ')
        ->join('recents', function($join) use ($website, $range) {
            $join->on('recents.website_id', '=', 'revenue.website_id')
                 ->whereRaw('DATE(recents.created_at) = revenue.date')
                 ->where('recents.website_id', '=', $website->id)
                 ->whereBetween('recents.created_at', [
                     $range['from'] . ' 00:00:00', 
                     $range['to'] . ' 23:59:59'
                 ]);
        })
        ->where('revenue.website_id', '=', $website->id)
        ->whereBetween('revenue.date', [$range['from'], $range['to']])
        ->whereNotNull('recents.country')
        ->groupBy('country_value')
        ->get();

    echo "Query executed successfully!\n";
    echo "Results found: " . $revenueByCountry->count() . "\n";
    
    if ($revenueByCountry->count() > 0) {
        echo "\nRevenue by Country Results:\n";
        foreach ($revenueByCountry as $result) {
            echo "- {$result->country_value}: {$result->total_revenue} ({$result->record_count} records)\n";
        }
    } else {
        echo "No results found. This might indicate:\n";
        echo "1. No revenue records have matching visitor data in 'recents' table\n";
        echo "2. Date correlation between revenue.date and recents.created_at is not working\n";
        echo "3. No country data in recent records\n";
        
        // Let's check sample data
        echo "\nSample revenue record:\n";
        $sampleRevenue = Revenue::where('website_id', $website->id)->first();
        if ($sampleRevenue) {
            echo "Date: {$sampleRevenue->date}, Amount: {$sampleRevenue->amount}\n";
        }
        
        echo "\nSample recent record:\n";
        $sampleRecent = \App\Models\Recent::where('website_id', $website->id)->first();
        if ($sampleRecent) {
            echo "Date: {$sampleRecent->created_at}, Country: {$sampleRecent->country}\n";
        }
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "This indicates there's still an issue with the SQL query.\n";
}

echo "\nTest completed.\n";
