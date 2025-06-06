<?php
// Database Connection Test Script

echo "<h1>Database Connection Test</h1>";
echo "<p>This script tests the connection to your MySQL database.</p>";

// Define database credentials directly
$host = '127.0.0.1'; 
$port = '3306';
$database = 'saas_analytics_pro_new';
$username = 'root';
$password = '';

echo "<p><strong>Connection Parameters:</strong><br>";
echo "Host: $host<br>";
echo "Port: $port<br>";
echo "Database: $database<br>";
echo "Username: $username<br>";
echo "Password: " . (empty($password) ? "(empty)" : "(set)") . "</p>";

try {
    // Create connection using PDO
    $dsn = "mysql:host=$host;port=$port;dbname=$database";
    $conn = new PDO($dsn, $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green'><strong>✓ SUCCESS:</strong> Connected to the database successfully!</p>";
    
    // Check for users table
    $stmt = $conn->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "<p><strong>Users in database:</strong> $userCount</p>";
    
} catch(PDOException $e) {
    echo "<p style='color:red'><strong>✗ ERROR:</strong> Connection failed: " . $e->getMessage() . "</p>";
    
    echo "<h2>Troubleshooting Steps:</h2>";
    echo "<ol>";
    echo "<li>Make sure MySQL server is running in XAMPP Control Panel</li>";
    echo "<li>Check that your database name, username and password are correct</li>";
    echo "<li>Verify that the MySQL port is set correctly (usually 3306)</li>";
    echo "<li>Try connecting with the mysql command line client to confirm access</li>";
    echo "</ol>";
}
