<?php 
// Database configuration - get this from your Laravel .env file or config
\System.Management.Automation.Internal.Host.InternalHost = 'localhost';
\ = 'root';
\ = '';
\ = 'saas_analytics_pro_new'; // Adjust if yours is different

// Create connection
\ = new mysqli(\System.Management.Automation.Internal.Host.InternalHost, \, \, \);

// Check connection
if (\->connect_errno) {
    echo 'Failed to connect to MySQL: ' . \->connect_error;
    exit();
}

// Query to get table structure
\ = \->query('DESCRIBE stats');

if (\) {
    echo \
Stats
Table
Structure:\\n\;
    while (\ = \->fetch_assoc()) {
        print_r(\);
    }
    \->free();
} else {
    echo 'Error: ' . \->error;
}

\->close();
?>
