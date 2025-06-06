<?php
// Simple test to verify sidebar and layout structure
require_once 'vendor/autoload.php';

echo "=== Testing Sidebar Structure ===\n\n";

// Test 1: Check if developers sidebar file exists and has proper structure
$developersSidebar = 'resources/views/developers/sidebar.blade.php';
if (file_exists($developersSidebar)) {
    echo "✓ Developers sidebar file exists\n";
    $content = file_get_contents($developersSidebar);
    
    // Check for key elements
    if (strpos($content, 'developer-nav-btn') !== false) {
        echo "✓ Developer navigation buttons class found\n";
    } else {
        echo "✗ Developer navigation buttons class NOT found\n";
    }
    
    if (strpos($content, 'btn w-100 d-flex') !== false) {
        echo "✓ Button styling classes found\n";
    } else {
        echo "✗ Button styling classes NOT found\n";
    }
    
    if (strpos($content, 'data-section=') !== false) {
        echo "✓ Data section attributes found\n";
    } else {
        echo "✗ Data section attributes NOT found\n";
    }
} else {
    echo "✗ Developers sidebar file does not exist\n";
}

echo "\n";

// Test 2: Check if account sidebar file exists and has proper structure
$accountSidebar = 'resources/views/account/sidebar.blade.php';
if (file_exists($accountSidebar)) {
    echo "✓ Account sidebar file exists\n";
    $content = file_get_contents($accountSidebar);
    
    // Check for key elements
    if (strpos($content, 'btn w-100 d-flex') !== false) {
        echo "✓ Account button styling classes found\n";
    } else {
        echo "✗ Account button styling classes NOT found\n";
    }
    
    if (strpos($content, 'isActiveRoute') !== false) {
        echo "✓ Active route detection found\n";
    } else {
        echo "✗ Active route detection NOT found\n";
    }
} else {
    echo "✗ Account sidebar file does not exist\n";
}

echo "\n";

// Test 3: Check developers combined page structure
$developersCombined = 'resources/views/developers/combined.blade.php';
if (file_exists($developersCombined)) {
    echo "✓ Developers combined page exists\n";
    $content = file_get_contents($developersCombined);
    
    // Check for new flexbox structure
    if (strpos($content, 'width: 170px; flex-shrink: 0') !== false) {
        echo "✓ Sidebar width (170px) structure found\n";
    } else {
        echo "✗ Sidebar width structure NOT found\n";
    }
    
    if (strpos($content, 'flex: 1; min-width: 0') !== false) {
        echo "✓ Content panel flex structure found\n";
    } else {
        echo "✗ Content panel flex structure NOT found\n";
    }
    
    if (strpos($content, 'content-section') !== false) {
        echo "✓ Content sections found\n";
    } else {
        echo "✗ Content sections NOT found\n";
    }
    
    // Count content sections
    $sectionCount = substr_count($content, 'class="content-section"');
    echo "✓ Found {$sectionCount} content sections\n";
    
} else {
    echo "✗ Developers combined page does not exist\n";
}

echo "\n";

// Test 4: Check account layout structure  
$accountLayout = 'resources/views/layouts/account.blade.php';
if (file_exists($accountLayout)) {
    echo "✓ Account layout file exists\n";
    $content = file_get_contents($accountLayout);
    
    // Check for new flexbox structure
    if (strpos($content, 'width: 170px; flex-shrink: 0') !== false) {
        echo "✓ Account layout sidebar width (170px) structure found\n";
    } else {
        echo "✗ Account layout sidebar width structure NOT found\n";
    }
    
    if (strpos($content, 'flex: 1; min-width: 0') !== false) {
        echo "✓ Account layout content panel flex structure found\n";
    } else {
        echo "✗ Account layout content panel flex structure NOT found\n";
    }
} else {
    echo "✗ Account layout file does not exist\n";
}

echo "\n=== Test Complete ===\n";
