<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Website;
use App\Models\Revenue;
use App\Models\Recent;
use Carbon\Carbon;

echo "Testing Revenue by Country JOIN Fix\n";
echo "===================================\n\n";

// Get first website
$website = Website::first();
if (!$website) {
    echo "No websites found!\n";
    exit(1);
}

echo "Website: {$website->domain} (ID: {$website->id})\n";

// Set up date range
$range = [
    'from' => Carbon::now()->subDays(30)->format('Y-m-d'),
    'to' => Carbon::now()->format('Y-m-d')
];

echo "Date range: {$range['from']} to {$range['to']}\n\n";

// Check what data we have
$revenueCount = Revenue::where('website_id', $website->id)->count();
$recentCount = Recent::where('website_id', $website->id)->count();

echo "Data availability:\n";
echo "- Revenue records: {$revenueCount}\n";
echo "- Recent visitor records: {$recentCount}\n\n";

// Get sample revenue and recent data
$sampleRevenue = Revenue::where('website_id', $website->id)->first();
$sampleRecent = Recent::where('website_id', $website->id)->whereNotNull('country')->first();

if ($sampleRevenue) {
    echo "Sample revenue record:\n";
    echo "- Date: {$sampleRevenue->date}\n";
    echo "- Amount: {$sampleRevenue->amount}\n";
    echo "- Currency: {$sampleRevenue->currency}\n";
    echo "- Visitor ID: {$sampleRevenue->visitor_id}\n\n";
}

if ($sampleRecent) {
    echo "Sample recent record:\n";
    echo "- Created: {$sampleRecent->created_at}\n";
    echo "- Country: {$sampleRecent->country}\n";
    echo "- Website ID: {$sampleRecent->website_id}\n\n";
}

// Test the fixed JOIN query
echo "Testing the FIXED revenue by country query...\n";

try {
    $result = \DB::table('revenue')
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

    echo "✅ Query executed successfully!\n";
    echo "Results found: " . $result->count() . "\n\n";
    
    if ($result->count() > 0) {
        echo "Revenue by Country:\n";
        echo "==================\n";
        $totalRevenue = 0;
        foreach ($result as $row) {
            echo "- {$row->country_value}: \${$row->total_revenue} ({$row->record_count} records)\n";
            $totalRevenue += $row->total_revenue;
        }
        echo "\nTotal Revenue: \${$totalRevenue}\n";
        
        // Check if we have "Unknown" countries
        $unknownCount = $result->where('country_value', 'Unknown')->count();
        if ($unknownCount > 0) {
            echo "\n⚠️  Warning: Found {$unknownCount} 'Unknown' country entries\n";
        } else {
            echo "\n✅ No 'Unknown' countries found - the issue is fixed!\n";
        }
        
    } else {
        echo "❌ No results found.\n";
        echo "\nDiagnosing the issue...\n";
        
        // Check if we have any recents with country data
        $recentsWithCountry = Recent::where('website_id', $website->id)
            ->whereNotNull('country')
            ->count();
        echo "- Recent records with country data: {$recentsWithCountry}\n";
        
        // Check if dates align
        $revenueDate = Revenue::where('website_id', $website->id)->value('date');
        $recentDate = Recent::where('website_id', $website->id)->value('created_at');
        echo "- Sample revenue date: {$revenueDate}\n";
        echo "- Sample recent date: " . ($recentDate ? Carbon::parse($recentDate)->format('Y-m-d') : 'null') . "\n";
    }

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "The SQL query has syntax issues that need to be fixed.\n";
}

echo "\nTest completed.\n";
