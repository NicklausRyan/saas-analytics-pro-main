<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=saas_analytics_pro_new', 'root', '');
    echo "Database connection successful!\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM stats WHERE name = 'country'");
    $result = $stmt->fetch();
    echo "Countries records found: " . $result['count'] . "\n";
    
    $stmt = $pdo->query("SELECT value, count, website_id FROM stats WHERE name = 'country' LIMIT 5");
    echo "\nSample country data:\n";
    while ($row = $stmt->fetch()) {
        echo "Value: '{$row['value']}' Count: {$row['count']} Website: {$row['website_id']}\n";
    }
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>
