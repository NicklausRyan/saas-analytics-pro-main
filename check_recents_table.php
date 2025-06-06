<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Recents table columns:\n";
$columns = Schema::getColumnListing('recents');
print_r($columns);

echo "\nSample recents data:\n";
$sampleData = DB::table('recents')->limit(3)->get();
foreach ($sampleData as $record) {
    print_r($record);
}

echo "\nRevenue table columns:\n";
$revenueColumns = Schema::getColumnListing('revenue');
print_r($revenueColumns);

echo "\nSample revenue data:\n";
$sampleRevenue = DB::table('revenue')->limit(3)->get();
foreach ($sampleRevenue as $record) {
    print_r($record);
}
