<?php
echo "Testing basic environment and database...\n";

// Read .env file directly
$env = file_get_contents('.env');
if (strpos($env, 'APP_INSTALLED=true') !== false) {
    echo "✓ APP_INSTALLED=true found in .env\n";
} else {
    echo "✗ APP_INSTALLED=true NOT found in .env\n";
}

if (strpos($env, 'DB_DATABASE=saas_analytics_pro_new') !== false) {
    echo "✓ DB_DATABASE found in .env\n";
} else {
    echo "✗ DB_DATABASE NOT found correctly in .env\n";
}

// Test direct database connection
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=saas_analytics_pro_new',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ Direct database connection successful\n";
    
    // Test if database has data
    $stmt = $pdo->query('SELECT COUNT(*) FROM users');
    $count = $stmt->fetchColumn();
    echo "✓ Database has $count users\n";
    
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\nNow testing with Laravel...\n";

try {
    require_once 'vendor/autoload.php';
    
    // Create a simple .env loader
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    echo "✓ Laravel autoloader loaded\n";
    echo "APP_INSTALLED from getenv: " . getenv('APP_INSTALLED') . "\n";
    echo "DB_DATABASE from getenv: " . getenv('DB_DATABASE') . "\n";
    
} catch (Exception $e) {
    echo "✗ Laravel loading failed: " . $e->getMessage() . "\n";
}
