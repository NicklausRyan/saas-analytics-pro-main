<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Website;
use App\Models\User;

echo "=== Adding wh1451867.ispot.cc to Analytics Dashboard ===\n\n";

// Get the first user (admin)
$user = User::first();

if (!$user) {
    echo "❌ No users found in database. Please create a user account first.\n";
    exit;
}

echo "👤 Using user: {$user->email} (ID: {$user->id})\n";

// Check if website already exists
$existingWebsite = Website::where('domain', 'wh1451867.ispot.cc')->first();

if ($existingWebsite) {
    echo "✅ Website 'wh1451867.ispot.cc' already exists (ID: {$existingWebsite->id})\n";
} else {
    // Create the website
    $website = new Website();
    $website->domain = 'wh1451867.ispot.cc';
    $website->user_id = $user->id;
    $website->privacy = 0; // Public
    $website->password = null; // No password protection
    $website->save();
    
    echo "✅ Successfully added 'wh1451867.ispot.cc' to the database (ID: {$website->id})\n";
}

echo "\n🏷️  Updated Tracking Code for wh1451867.ispot.cc:\n";
echo '<script data-host="https://wh1451867.ispot.cc" data-dnt="false" src="https://wh1451867.ispot.cc/js/script.js" id="ZwSg9rf6GA" async defer></script>' . "\n\n";

echo "📋 Next Steps:\n";
echo "1. Clear any Laravel cache: php artisan cache:clear\n";
echo "2. Add the tracking script above to your website\n";
echo "3. Visit your website to test tracking\n";
echo "4. Check the analytics dashboard at: https://wh1451867.ispot.cc/stats/wh1451867.ispot.cc\n\n";

?>
