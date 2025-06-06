<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking recents table structure...\n\n";

try {
    $columns = DB::select('DESCRIBE recents');
    
    echo "Columns in recents table:\n";
    foreach($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
    // Also check a sample record to see what data looks like
    echo "\nSample record from recents table:\n";
    $sample = DB::table('recents')->first();
    if ($sample) {
        foreach($sample as $field => $value) {
            echo "- {$field}: " . (is_string($value) ? substr($value, 0, 50) : $value) . "\n";
        }
    } else {
        echo "No records found in recents table.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
