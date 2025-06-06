<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== COUNTRIES DATA ===\n";
$countries = DB::table('stats')->where('name', 'country')->select('value', 'count', 'website_id')->limit(10)->get();
foreach($countries as $c) {
    echo "Value: '{$c->value}' Count: {$c->count} Website: {$c->website_id}\n";
}

echo "\n=== WEBSITES ===\n";
$websites = DB::table('websites')->select('id', 'domain')->get();
foreach($websites as $w) {
    echo "ID: {$w->id} Domain: {$w->domain}\n";
}
?>
