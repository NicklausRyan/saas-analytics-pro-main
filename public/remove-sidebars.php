<?php
/**
 * Script to remove sidebar includes from all blade files
 */

// Define the base directory
$baseDir = __DIR__ . '/../resources/views';

// Function to search and remove @include('shared.sidebars.user') from files
function removeUserSidebar($dir) {
    global $baseDir;
    $files = scandir($dir);
    $results = [];
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            // Recursively process subdirectories
            $results = array_merge($results, removeUserSidebar($path));
        } else if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            // Process .php files
            $content = file_get_contents($path);
            
            if (strpos($content, "@include('shared.sidebars.user')") !== false) {
                // Remove the sidebar include
                $newContent = str_replace("@include('shared.sidebars.user')", "", $content);
                
                if ($newContent !== $content) {
                    file_put_contents($path, $newContent);
                    $relativePath = str_replace($baseDir . '/', '', $path);
                    $results[] = "Removed sidebar include from: $relativePath";
                }
            }
        }
    }
    
    return $results;
}

// Process the views directory and get results
$results = removeUserSidebar($baseDir);

// Output results
header('Content-Type: text/html');
echo "<!DOCTYPE html>
<html>
<head>
    <title>Sidebar Removal Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .success { color: green; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 5px 0; padding: 5px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <h1>Sidebar Removal Complete</h1>
    <p class='success'>Successfully processed " . count($results) . " files:</p>
    <ul>";

foreach ($results as $result) {
    echo "<li>$result</li>";
}

echo "</ul>
</body>
</html>";
