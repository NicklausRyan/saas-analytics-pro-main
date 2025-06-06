<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Stat;
use App\Models\Revenue;
use App\Models\Recent;
use Carbon\Carbon;

echo "=== SaaS Analytics Pro - Test Data Summary ===\n\n";

$websiteId = 4; // test.com

// Get date range
$minDate = Stat::where('website_id', $websiteId)->min('date');
$maxDate = Stat::where('website_id', $websiteId)->max('date');

echo "📊 Analytics Overview for test.com\n";
echo "📅 Date Range: {$minDate} to {$maxDate}\n\n";

// Visitors and Pageviews Summary
$totalVisitors = Stat::where('website_id', $websiteId)
    ->where('name', 'visitors')
    ->sum('count');

$totalPageviews = Stat::where('website_id', $websiteId)
    ->where('name', 'pageviews')
    ->sum('count');

echo "👥 Total Visitors: " . number_format($totalVisitors) . "\n";
echo "📄 Total Pageviews: " . number_format($totalPageviews) . "\n";

if ($totalVisitors > 0) {
    echo "📈 Pages per Visit: " . number_format($totalPageviews / $totalVisitors, 2) . "\n";
}

// Revenue Summary
$totalRevenue = Revenue::where('website_id', $websiteId)->sum('amount');
$revenueCount = Revenue::where('website_id', $websiteId)->count();

echo "\n💰 Revenue Analytics:\n";
echo "💵 Total Revenue: $" . number_format($totalRevenue, 2) . "\n";
echo "🛒 Total Transactions: " . number_format($revenueCount) . "\n";

if ($totalVisitors > 0) {
    echo "💰 Revenue per Visitor: $" . number_format($totalRevenue / $totalVisitors, 2) . "\n";
}

// Geographic Distribution
echo "\n🌍 Top Countries:\n";
$countries = Stat::where('website_id', $websiteId)
    ->where('name', 'LIKE', 'country_%')
    ->selectRaw('SUBSTRING(name, 9) as country, SUM(count) as total')
    ->groupBy('country')
    ->orderBy('total', 'desc')
    ->limit(5)
    ->get();

foreach ($countries as $country) {
    echo "  🏳️  {$country->country}: " . number_format($country->total) . " visitors\n";
}

// Browser Analytics
echo "\n🌐 Top Browsers:\n";
$browsers = Stat::where('website_id', $websiteId)
    ->where('name', 'LIKE', 'browser_%')
    ->selectRaw('SUBSTRING(name, 9) as browser, SUM(count) as total')
    ->groupBy('browser')
    ->orderBy('total', 'desc')
    ->limit(5)
    ->get();

foreach ($browsers as $browser) {
    echo "  🔗 {$browser->browser}: " . number_format($browser->total) . " visitors\n";
}

// Recent Activity
$recentCount = Recent::where('website_id', $websiteId)->count();
echo "\n⚡ Real-time Data:\n";
echo "🕐 Recent Pageviews: " . number_format($recentCount) . " entries\n";

// Performance Metrics
$bounceRateData = Stat::where('website_id', $websiteId)
    ->where('name', 'bounce_rate')
    ->avg('value');

$avgSessionData = Stat::where('website_id', $websiteId)
    ->where('name', 'average_session_duration')
    ->avg('value');

echo "\n📊 Performance Metrics:\n";
if ($bounceRateData) {
    echo "📉 Average Bounce Rate: " . number_format($bounceRateData, 1) . "%\n";
}
if ($avgSessionData) {
    echo "⏱️  Average Session Duration: " . number_format($avgSessionData / 60, 1) . " minutes\n";
}

// Data Types Generated
echo "\n✅ Data Types Successfully Generated:\n";
$statTypes = Stat::where('website_id', $websiteId)
    ->distinct()
    ->pluck('name')
    ->sort();

$categories = [
    'Basic Metrics' => ['visitors', 'pageviews', 'bounce_rate', 'average_session_duration'],
    'Geographic' => $statTypes->filter(fn($name) => str_starts_with($name, 'country_') || str_starts_with($name, 'city_'))->take(3),
    'Technology' => $statTypes->filter(fn($name) => str_starts_with($name, 'browser_') || str_starts_with($name, 'os_') || str_starts_with($name, 'device_'))->take(3),
    'Content' => $statTypes->filter(fn($name) => str_starts_with($name, 'page_'))->take(3),
    'Marketing' => $statTypes->filter(fn($name) => str_starts_with($name, 'referrer_') || str_starts_with($name, 'campaign_'))->take(3),
];

foreach ($categories as $category => $types) {
    if (count($types) > 0) {
        echo "  📂 {$category}: " . count($types) . " metrics\n";
    }
}

echo "\n🎉 Test data generation completed successfully!\n";
echo "🔗 You can now login to the dashboard with:\n";
echo "   Email: test@example.com\n";
echo "   Password: password\n";
echo "   URL: http://127.0.0.1:8000/login\n\n";

echo "=== Analytics Ready for Testing ===\n";
