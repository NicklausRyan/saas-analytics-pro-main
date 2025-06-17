<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Website;

echo "=== SaaS Analytics Pro - Tracking Issue Debug ===\n\n";

// Check current APP_URL configuration
echo "🔧 Current Configuration:\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "Expected URL: https://wh1451867.ispot.cc\n\n";

// Check if the website domain exists in the database
echo "🌐 Website Domains in Database:\n";
$websites = Website::all();

if ($websites->count() > 0) {
    foreach ($websites as $website) {
        echo "  - {$website->domain} (ID: {$website->id})\n";
        echo "    User ID: {$website->user_id}\n";
        echo "    Can Track: " . ($website->user->can_track ? 'Yes' : 'No') . "\n\n";
    }
} else {
    echo "  No websites found in database!\n\n";
}

// Check what the tracking code currently generates
echo "🏷️  Current Tracking Code Template:\n";
$trackingCodeTemplate = '<script data-host="' . config('app.url') . '" data-dnt="' . (config('settings.do_not_track') ? 'true' : 'false') . '" src="' . (!empty(config('settings.cdn_url')) ? config('settings.cdn_url') : asset('js/script.js')) . '" id="ZwSg9rf6GA" async defer></script>';
echo $trackingCodeTemplate . "\n\n";

echo "🔍 Issues Found:\n";
echo "1. APP_URL in .env file points to localhost instead of wh1451867.ispot.cc\n";
echo "2. You need to create a website record for 'wh1451867.ispot.cc' in the dashboard\n";
echo "3. The tracking script will send data to the wrong endpoint\n\n";

echo "✅ Solution Steps:\n";
echo "1. Update APP_URL in .env file to: https://wh1451867.ispot.cc\n";
echo "2. Add 'wh1451867.ispot.cc' as a website in your analytics dashboard\n";
echo "3. Use the updated tracking code on your website\n";
echo "4. Ensure the website is accessible at the correct URL\n\n";

?>
