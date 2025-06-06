<?php
/**
 * This script adds header and footer includes to all admin views
 * It helps fix section inheritance issues in Blade templates
 */

// Directory containing admin views
$viewsDirectory = __DIR__ . '/../resources/views/admin';

// Files to ignore (don't modify these)
$ignoredFiles = [
    'sidebar.blade.php',
    'container.blade.php',
    'dashboard/index.blade.php', // Already has correct structure
    'shared/header.blade.php',   // These are the includes themselves
    'shared/footer.blade.php',
    'partials/',                 // Ignore partials folders
];

// Function to process each view file
function processViewFile($file, $ignoredFiles) {
    // Check if file should be ignored
    foreach ($ignoredFiles as $ignore) {
        if (strpos($file, $ignore) !== false) {
            echo "Skipping: $file\n";
            return;
        }
    }
    
    echo "Processing: $file\n";
    
    // Read file content
    $content = file_get_contents($file);
    
    // Check if already has our header/footer
    if (strpos($content, '@include(\'admin.shared.header\')') !== false) {
        echo "  Already has header include\n";
        return;
    }
    
    // Add header include at the beginning
    if (strpos($content, '@section') === 0) {
        // If it starts with @section, add header before it
        $content = "@include('admin.shared.header')\n\n" . $content;
    }
    
    // Add footer include at the end if not already there
    if (strpos($content, '@include(\'admin.shared.footer\')') === false) {
        $content .= "\n\n@include('admin.shared.footer')";
    }
    
    // Write updated content back
    file_put_contents($file, $content);
    echo "  Updated with header/footer includes\n";
}

// Function to recursively scan directory
function scanDirectory($dir, $ignoredFiles) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            scanDirectory($path, $ignoredFiles);
        } else if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            processViewFile($path, $ignoredFiles);
        }
    }
}

echo "Starting to update admin view files...\n";
scanDirectory($viewsDirectory, $ignoredFiles);
echo "Completed updating admin view files.\n";
