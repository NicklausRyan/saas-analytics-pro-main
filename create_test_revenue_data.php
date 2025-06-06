<?php
/**
 * Create test revenue data with proper attribution
 */

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Website;
use App\Models\Revenue;
use App\Models\Stat;
use Carbon\Carbon;

echo "Creating test revenue data with attribution...\n";

// Get the first website
$website = Website::first();
if (!$website) {
    echo "No website found. Please create a website first.\n";
    exit(1);
}

echo "Using website: {$website->domain} (ID: {$website->id})\n";

// Get recent stats data to attribute revenue to
$stats = Stat::where('website_id', $website->id)
    ->whereIn('name', ['country', 'browser', 'os'])
    ->where('date', '>=', Carbon::now()->subDays(7)->format('Y-m-d'))
    ->get();

if ($stats->isEmpty()) {
    echo "No stats data found. Creating some basic stats first...\n";
    
    // Create some basic stats data
    $countries = ['US:United States', 'GB:United Kingdom', 'CA:Canada', 'DE:Germany', 'FR:France'];
    $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
    $operatingSystems = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];
    
    $dates = [];
    for ($i = 6; $i >= 0; $i--) {
        $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');
    }
    
    foreach ($dates as $date) {
        $visitorId = 'test_visitor_' . $date . '_' . rand(1000, 9999);
        
        // Create country stat
        Stat::create([
            'website_id' => $website->id,
            'visitor_id' => $visitorId,
            'name' => 'country',
            'value' => $countries[array_rand($countries)],
            'count' => rand(1, 5),
            'date' => $date
        ]);
        
        // Create browser stat
        Stat::create([
            'website_id' => $website->id,
            'visitor_id' => $visitorId,
            'name' => 'browser',
            'value' => $browsers[array_rand($browsers)],
            'count' => rand(1, 3),
            'date' => $date
        ]);
        
        // Create OS stat
        Stat::create([
            'website_id' => $website->id,
            'visitor_id' => $visitorId,
            'name' => 'os',
            'value' => $operatingSystems[array_rand($operatingSystems)],
            'count' => rand(1, 2),
            'date' => $date
        ]);
        
        echo "Created stats for visitor: $visitorId on $date\n";
    }
    
    // Refresh stats data
    $stats = Stat::where('website_id', $website->id)
        ->whereIn('name', ['country', 'browser', 'os'])
        ->where('date', '>=', Carbon::now()->subDays(7)->format('Y-m-d'))
        ->get();
}

echo "Found " . $stats->count() . " stats records\n";

// Clear existing test revenue data
Revenue::where('website_id', $website->id)->delete();
echo "Cleared existing revenue data\n";

// Create revenue entries with proper visitor attribution
$currencies = ['USD', 'EUR', 'GBP'];
$sources = ['stripe', 'paypal', 'manual'];

$visitorsWithStats = $stats->groupBy('visitor_id');
$revenueCount = 0;

foreach ($visitorsWithStats as $visitorId => $visitorStats) {
    // 30% chance this visitor made a purchase
    if (rand(1, 100) <= 30) {
        $firstStat = $visitorStats->first();
        $amount = rand(1999, 9999) / 100; // $19.99 to $99.99
        
        Revenue::create([
            'website_id' => $website->id,
            'visitor_id' => $visitorId,
            'amount' => $amount,
            'currency' => $currencies[array_rand($currencies)],
            'order_id' => 'TEST-' . strtoupper(uniqid()),
            'source' => $sources[array_rand($sources)],
            'date' => $firstStat->date
        ]);
        
        $revenueCount++;
        echo "Created revenue entry: $amount for visitor $visitorId on {$firstStat->date}\n";
    }
}

echo "\n✅ Test revenue data creation complete!\n";
echo "Created $revenueCount revenue entries with proper attribution\n";
echo "Website: {$website->domain}\n";
echo "You can now view the analytics dashboard to see revenue attribution working.\n";
