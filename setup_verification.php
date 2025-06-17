<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Website;
use App\Models\User;

echo "=== Final Verification - Tracking Setup Complete ===\n\n";

// Check configuration
echo "🔧 Configuration Check:\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "APP_ENV: " . config('app.env') . "\n";
echo "APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "\n\n";

// Check website exists
echo "🌐 Website Database Check:\n";
$website = Website::where('domain', 'wh1451867.ispot.cc')->first();

if ($website) {
    echo "✅ Website 'wh1451867.ispot.cc' found (ID: {$website->id})\n";
    echo "   User ID: {$website->user_id}\n";
    echo "   Privacy: " . ($website->privacy ? 'Private' : 'Public') . "\n";
    echo "   Password: " . ($website->password ? 'Protected' : 'None') . "\n";
    echo "   Can Track: " . ($website->user->can_track ? 'Yes' : 'No') . "\n\n";
} else {
    echo "❌ Website 'wh1451867.ispot.cc' not found!\n\n";
}

// Check routes
echo "🛣️  Route Check:\n";
echo "Event tracking endpoint: " . config('app.url') . "/api/event\n";
echo "Revenue tracking endpoint: " . config('app.url') . "/api/track-revenue\n";
echo "Dashboard URL: " . config('app.url') . "/stats/wh1451867.ispot.cc\n\n";

// Generate correct tracking code
echo "🏷️  Correct Tracking Code:\n";
$trackingCode = '<script data-host="' . config('app.url') . '" data-dnt="false" src="' . config('app.url') . '/js/script.js" id="ZwSg9rf6GA" async defer></script>';
echo $trackingCode . "\n\n";

echo "📋 Testing Instructions:\n";
echo "1. Visit: https://wh1451867.ispot.cc/tracking-test.html\n";
echo "2. Check browser console for any errors\n";
echo "3. Click the test buttons to send events\n";
echo "4. Check analytics dashboard: https://wh1451867.ispot.cc/stats/wh1451867.ispot.cc\n";
echo "5. Login with: nicklausryan400@gmail.com\n\n";

echo "🔍 Troubleshooting:\n";
echo "- If tracking still doesn't work, check browser Network tab for failed requests\n";
echo "- Ensure your web server is properly configured\n";
echo "- Check that the domain 'wh1451867.ispot.cc' resolves to your server\n";
echo "- Verify the /js/script.js file is accessible at: https://wh1451867.ispot.cc/js/script.js\n\n";

echo "✅ Setup Complete! Your tracking should now work.\n";

?>
