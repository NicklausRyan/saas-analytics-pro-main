<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Website;
use App\Models\Stat;
use App\Models\Recent;
use App\Models\Revenue;
use App\Models\User;
use Carbon\Carbon;

// Initialize Laravel framework
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Creating comprehensive test data for test.com domain...\n";

// First, let's check if we have a user to associate the website with
$user = User::first();
if (!$user) {
    echo "Creating a test user...\n";
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
        'plan_id' => 1,
        'plan_ends_at' => Carbon::now()->addYear()
    ]);
}

// Create or find the test.com website
$website = Website::where('domain', 'test.com')->first();
if (!$website) {
    echo "Creating test.com website...\n";
    $website = Website::create([
        'domain' => 'test.com',
        'url' => 'https://test.com',
        'user_id' => $user->id,
        'privacy' => 0,
        'exclude_bots' => 1
    ]);
} else {
    echo "Found existing test.com website (ID: {$website->id})\n";
}

// Clear existing test data for this website
echo "Clearing existing test data...\n";
Stat::where('website_id', $website->id)->delete();
Recent::where('website_id', $website->id)->delete();
Revenue::where('website_id', $website->id)->delete();

// Date range for test data (last 30 days)
$endDate = Carbon::now();
$startDate = $endDate->copy()->subDays(30);

echo "Generating test data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}...\n";

// Arrays for test data
$countries = [
    'US' => 'United States',
    'GB' => 'United Kingdom', 
    'CA' => 'Canada',
    'DE' => 'Germany',
    'FR' => 'France',
    'AU' => 'Australia',
    'JP' => 'Japan',
    'IN' => 'India',
    'BR' => 'Brazil',
    'IT' => 'Italy'
];

$cities = [
    'US:New York', 'US:Los Angeles', 'US:Chicago', 'GB:London', 'CA:Toronto',
    'DE:Berlin', 'FR:Paris', 'AU:Sydney', 'JP:Tokyo', 'IN:Mumbai',
    'BR:São Paulo', 'IT:Rome', 'US:San Francisco', 'GB:Manchester', 'CA:Vancouver'
];

$browsers = [
    'Chrome' => 45,
    'Safari' => 25,
    'Firefox' => 15,
    'Edge' => 10,
    'Opera' => 3,
    'Internet Explorer' => 2
];

$operatingSystems = [
    'Windows' => 50,
    'macOS' => 25,
    'iOS' => 15,
    'Android' => 8,
    'Linux' => 2
];

$devices = [
    'Desktop' => 60,
    'Mobile' => 35,
    'Tablet' => 5
];

$pages = [
    '/', '/about', '/products', '/contact', '/blog', '/pricing', '/features',
    '/support', '/login', '/signup', '/blog/post-1', '/blog/post-2',
    '/products/item-1', '/products/item-2', '/checkout'
];

$referrers = [
    'google.com' => 40,
    'facebook.com' => 20,
    'twitter.com' => 15,
    'linkedin.com' => 10,
    'youtube.com' => 8,
    'instagram.com' => 4,
    'reddit.com' => 3
];

$languages = ['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'zh'];
$campaigns = ['summer-sale', 'black-friday', 'newsletter', 'social-media', 'google-ads'];
$events = ['click', 'download', 'signup', 'purchase', 'contact', 'video-play'];

// Revenue sources for attribution
$revenueSources = ['organic', 'paid', 'social', 'email', 'direct', 'referral'];

$totalInserts = 0;

// Generate data for each day
for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
    $dateString = $date->format('Y-m-d');
    echo "Processing {$dateString}...\n";
    
    // Generate base visitor/pageview counts for the day
    $baseVisitors = rand(800, 1500);
    $basePageviews = rand($baseVisitors * 1.2, $baseVisitors * 3);
    
    // Weekend adjustment
    if ($date->isWeekend()) {
        $baseVisitors = intval($baseVisitors * 0.7);
        $basePageviews = intval($basePageviews * 0.7);
    }
    
    // Insert visitors and pageviews stats
    Stat::create([
        'website_id' => $website->id,
        'name' => 'visitors',
        'value' => '',
        'count' => $baseVisitors,
        'date' => $dateString
    ]);
    
    Stat::create([
        'website_id' => $website->id,
        'name' => 'pageviews', 
        'value' => '',
        'count' => $basePageviews,
        'date' => $dateString
    ]);
    
    $totalInserts += 2;
    
    // Generate country distribution
    foreach ($countries as $code => $name) {
        $visitors = intval($baseVisitors * (rand(5, 25) / 100));
        if ($visitors > 0) {
            Stat::create([
                'website_id' => $website->id,
                'name' => 'country',
                'value' => $code,
                'count' => $visitors,
                'date' => $dateString
            ]);
            $totalInserts++;
        }
    }
    
    // Generate city distribution
    foreach ($cities as $city) {
        $visitors = rand(10, 100);
        Stat::create([
            'website_id' => $website->id,
            'name' => 'city',
            'value' => $city,
            'count' => $visitors,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate browser distribution
    foreach ($browsers as $browser => $percentage) {
        $visitors = intval($baseVisitors * ($percentage / 100));
        Stat::create([
            'website_id' => $website->id,
            'name' => 'browser',
            'value' => $browser,
            'count' => $visitors,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate OS distribution
    foreach ($operatingSystems as $os => $percentage) {
        $visitors = intval($baseVisitors * ($percentage / 100));
        Stat::create([
            'website_id' => $website->id,
            'name' => 'os',
            'value' => $os,
            'count' => $visitors,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate device distribution
    foreach ($devices as $device => $percentage) {
        $visitors = intval($baseVisitors * ($percentage / 100));
        Stat::create([
            'website_id' => $website->id,
            'name' => 'device',
            'value' => $device,
            'count' => $visitors,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate page views
    foreach ($pages as $page) {
        $pageviews = rand(50, 300);
        Stat::create([
            'website_id' => $website->id,
            'name' => 'page',
            'value' => $page,
            'count' => $pageviews,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate referrer distribution
    foreach ($referrers as $referrer => $percentage) {
        $visitors = intval($baseVisitors * ($percentage / 100));
        if ($visitors > 0) {
            Stat::create([
                'website_id' => $website->id,
                'name' => 'referrer',
                'value' => $referrer,
                'count' => $visitors,
                'date' => $dateString
            ]);
            $totalInserts++;
        }
    }
    
    // Generate language distribution
    foreach ($languages as $lang) {
        $visitors = rand(50, 200);
        Stat::create([
            'website_id' => $website->id,
            'name' => 'language',
            'value' => $lang,
            'count' => $visitors,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate campaign data
    foreach ($campaigns as $campaign) {
        $visitors = rand(20, 150);
        Stat::create([
            'website_id' => $website->id,
            'name' => 'campaign',
            'value' => $campaign,
            'count' => $visitors,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate events
    foreach ($events as $event) {
        $count = rand(30, 200);
        Stat::create([
            'website_id' => $website->id,
            'name' => 'event',
            'value' => $event,
            'count' => $count,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
    
    // Generate bounce rate data (implicitly calculated from single page sessions)
    $bounceCount = intval($baseVisitors * (rand(25, 65) / 100)); // 25-65% bounce rate
    Stat::create([
        'website_id' => $website->id,
        'name' => 'bounce',
        'value' => '',
        'count' => $bounceCount,
        'date' => $dateString
    ]);
    $totalInserts++;
    
    // Generate session duration data
    $sessionCount = intval($baseVisitors * 0.8); // Assume 80% of visitors create trackable sessions
    $totalSessionTime = $sessionCount * rand(30, 300); // 30 seconds to 5 minutes average
    
    Stat::create([
        'website_id' => $website->id,
        'name' => 'session',
        'value' => '',
        'count' => $sessionCount,
        'date' => $dateString
    ]);
    
    Stat::create([
        'website_id' => $website->id,
        'name' => 'session_duration',
        'value' => $totalSessionTime,
        'count' => 1,
        'date' => $dateString
    ]);
    $totalInserts += 2;
    
    // Generate revenue data
    $dailyRevenue = rand(500, 5000) / 100; // $5.00 to $50.00 per day
    $revenueEvents = rand(1, 10);
    
    for ($i = 0; $i < $revenueEvents; $i++) {
        $amount = rand(1000, 15000) / 100; // $10.00 to $150.00 per transaction
        $source = $revenueSources[array_rand($revenueSources)];
        $currency = 'USD';
        
        Revenue::create([
            'website_id' => $website->id,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => 'ORDER-' . rand(10000, 99999),
            'visitor_id' => 'VISITOR-' . rand(100000, 999999),
            'source' => $source,
            'date' => $dateString
        ]);
        $totalInserts++;
    }
}

// Generate recent pageviews for realtime data (last 24 hours)
echo "Generating recent pageviews for realtime data...\n";
$recentStart = Carbon::now()->subHours(24);
$recentEnd = Carbon::now();

for ($i = 0; $i < 50; $i++) {
    $randomTime = $recentStart->copy()->addMinutes(rand(0, 1440)); // Random time in last 24 hours
    
    Recent::create([
        'website_id' => $website->id,
        'page' => $pages[array_rand($pages)],
        'referrer' => array_rand($referrers) ?: null,
        'os' => array_rand($operatingSystems),
        'browser' => array_rand($browsers),
        'device' => array_rand($devices),
        'country' => array_rand($countries),
        'city' => $cities[array_rand($cities)],
        'language' => $languages[array_rand($languages)],
        'created_at' => $randomTime
    ]);
    $totalInserts++;
}

echo "\n=== Test Data Generation Complete ===\n";
echo "Total database inserts: {$totalInserts}\n";
echo "Website ID: {$website->id}\n";
echo "Domain: test.com\n";
echo "Date range: {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}\n";

// Display summary stats
$totalVisitors = Stat::where(['website_id' => $website->id, 'name' => 'visitors'])->sum('count');
$totalPageviews = Stat::where(['website_id' => $website->id, 'name' => 'pageviews'])->sum('count');
$totalRevenue = Revenue::where('website_id', $website->id)->sum('amount');
$totalBounces = Stat::where(['website_id' => $website->id, 'name' => 'bounce'])->sum('count');
$bounceRate = $totalVisitors > 0 ? ($totalBounces / $totalVisitors) * 100 : 0;

echo "\n=== Summary Statistics ===\n";
echo "Total Visitors: " . number_format($totalVisitors) . "\n";
echo "Total Pageviews: " . number_format($totalPageviews) . "\n";
echo "Total Revenue: $" . number_format($totalRevenue, 2) . "\n";
echo "Bounce Rate: " . number_format($bounceRate, 1) . "%\n";
echo "Revenue per Visitor: $" . number_format($totalRevenue / $totalVisitors, 2) . "\n";

echo "\n=== Data Types Generated ===\n";
echo "✓ Visitors and Pageviews\n";
echo "✓ Bounce Rate\n";
echo "✓ Session Duration\n";
echo "✓ Countries (10 countries)\n";
echo "✓ Cities (15 cities)\n";
echo "✓ Browsers (6 types)\n";
echo "✓ Operating Systems (5 types)\n";
echo "✓ Devices (3 types)\n";
echo "✓ Pages (15 pages)\n";
echo "✓ Referrers (7 sources)\n";
echo "✓ Languages (8 languages)\n";
echo "✓ Campaigns (5 campaigns)\n";
echo "✓ Events (6 event types)\n";
echo "✓ Revenue with Attribution\n";
echo "✓ Recent Pageviews (for realtime)\n";

echo "\nYou can now visit the analytics dashboard for test.com to see all the test data!\n";
