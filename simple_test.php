<?php

require __DIR__.'/vendor/autoload.php';

echo "Testing Laravel database connection...\n";

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Laravel bootstrapped successfully.\n";

use App\Models\Website;
use App\Models\Revenue;

$websiteCount = Website::count();
$revenueCount = Revenue::count();

echo "Websites: {$websiteCount}\n";
echo "Revenue records: {$revenueCount}\n";

echo "Test completed successfully!\n";
