<?php
/**
 * This script removes @section('site_title',...) directives from admin views
 * to prevent section nesting issues
 */

echo "Running script to clean up admin views..." . PHP_EOL;

// Directory containing admin views
$viewsDirectory = __DIR__ . '/../resources/views/admin';

// Excluded directories
$excludedDirs = [
    'layouts', 
    'shared'
];

// Function to clean a file
function cleanFile($filePath) {
    echo "Processing: " . $filePath . PHP_EOL;
    
    // Read file content
    $content = file_get_contents($filePath);
    
    // Replace @section('site_title', ...) with <!-- site_title: ... -->
    $pattern = "/@section\('site_title',\s*(.*?)\)/";
    $replacement = "<!-- site_title: $1 -->";
    $newContent = preg_replace($pattern, $replacement, $content);
    
    // Write back if changed
    if ($content !== $newContent) {
        file_put_contents($filePath, $newContent);
        echo "  - Replaced site_title section directive" . PHP_EOL;
    } else {
        echo "  - No changes needed" . PHP_EOL;
    }
}

// Function to process directory recursively
function processDirectory($dir, $excludedDirs) {
    $baseName = basename($dir);
    
    // Skip excluded directories
    if (in_array($baseName, $excludedDirs)) {
        echo "Skipping directory: " . $dir . PHP_EOL;
        return;
    }
    
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            processDirectory($path, $excludedDirs);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            cleanFile($path);
        }
    }
}

// Process admin views directory
processDirectory($viewsDirectory, $excludedDirs);

echo "Finished cleaning up admin views." . PHP_EOL;
