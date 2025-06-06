<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking websites in database...\n";
$websites = \App\Models\Website::all(['id', 'domain']);

if ($websites->isEmpty()) {
    echo "No websites found in database.\n";
} else {
    foreach($websites as $w) {
        echo $w->id . ': ' . $w->domain . "\n";
    }
}
