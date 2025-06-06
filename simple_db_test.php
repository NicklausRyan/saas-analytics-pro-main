<?php
echo "Testing database connection...\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=saas_analytics_pro_new', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connection successful\n";
    
    // Check if stats table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'stats'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Stats table exists\n";
        
        // Check total records
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM stats");
        $total = $stmt->fetch()['total'];
        echo "✓ Total stats records: $total\n";
        
        // Check country records
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM stats WHERE name = 'country'");
        $count = $stmt->fetch()['count'];
        echo "✓ Country records: $count\n";
        
        if ($count > 0) {
            // Show sample country data
            $stmt = $pdo->query("SELECT value, count, website_id FROM stats WHERE name = 'country' LIMIT 3");
            echo "\nSample country data:\n";
            while ($row = $stmt->fetch()) {
                echo "  Value: '{$row['value']}', Count: {$row['count']}, Website: {$row['website_id']}\n";
            }
        }
    } else {
        echo "✗ Stats table does not exist\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
