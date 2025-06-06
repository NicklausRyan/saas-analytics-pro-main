<?php
/**
 * Script to remove sidebar includes from all blade files
 */

// Files to process
$files = [
    'resources/views/pages/show.blade.php',
    'resources/views/stats/password.blade.php',
    'resources/views/stats/tracking-code.blade.php',
    'resources/views/stats/container.blade.php',
    'resources/views/pricing/index.blade.php',
    'resources/views/developers/stats/index.blade.php',
    'resources/views/developers/websites/index.blade.php',
    'resources/views/developers/index.blade.php',
    'resources/views/developers/account/index.blade.php',
    'resources/views/contact/index.blade.php',
    'resources/views/checkout/complete.blade.php',
    'resources/views/checkout/pending.blade.php',
    'resources/views/checkout/index.blade.php',
    'resources/views/checkout/cancelled.blade.php',
    'resources/views/auth/passwords/reset.blade.php'
];

// Base directory
$baseDir = dirname(__DIR__);

foreach ($files as $file) {
    $filePath = $baseDir . '/' . $file;
    
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        
        // Remove the sidebar include
        $newContent = preg_replace('/@include\(\'shared\.sidebars\.user\'\)[\s]*$/m', '', $content);
        
        if ($newContent !== $content) {
            file_put_contents($filePath, $newContent);
            echo "Removed sidebar include from: $file\n";
        } else {
            echo "No changes made to: $file\n";
        }
    } else {
        echo "File not found: $file\n";
    }
}

echo "Sidebar removal complete!\n";
