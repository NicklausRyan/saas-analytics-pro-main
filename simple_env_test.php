<?php
// Simple test to check .env file loading
echo "=== Testing .env file loading ===\n";

// Load .env manually
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo "Found .env file with " . count($lines) . " lines\n";
    
    foreach ($lines as $line) {
        if (strpos($line, 'APP_INSTALLED') === 0) {
            echo "Found: $line\n";
        }
        if (strpos($line, 'DB_DATABASE') === 0) {
            echo "Found: $line\n";
        }
    }
} else {
    echo ".env file not found!\n";
}

echo "\n=== Current environment variables ===\n";
echo "APP_INSTALLED (getenv): " . getenv('APP_INSTALLED') . "\n";
echo "DB_DATABASE (getenv): " . getenv('DB_DATABASE') . "\n";

// Try to start Laravel app
echo "\n=== Loading Laravel ===\n";
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    
    echo "Laravel app loaded successfully\n";
    
    // Get environment values through Laravel
    echo "APP_INSTALLED (Laravel env): " . env('APP_INSTALLED') . "\n";
    echo "DB_DATABASE (Laravel env): " . env('DB_DATABASE') . "\n";
    
} catch (Exception $e) {
    echo "Error loading Laravel: " . $e->getMessage() . "\n";
}
