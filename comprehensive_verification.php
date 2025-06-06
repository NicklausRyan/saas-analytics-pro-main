<?php
// Comprehensive verification of account and developers pages

echo "=== COMPREHENSIVE SIDEBAR VERIFICATION ===\n\n";

echo "1. LAYOUT STRUCTURE VERIFICATION\n";
echo "================================\n";

// Check account layout
$accountLayout = 'resources/views/layouts/account.blade.php';
$accountContent = file_get_contents($accountLayout);

echo "Account Layout:\n";
if (strpos($accountContent, 'width: 170px; flex-shrink: 0') !== false) {
    echo "✓ Sidebar width: 170px (25% smaller from original 225px)\n";
} else {
    echo "✗ Sidebar width not found\n";
}

if (strpos($accountContent, 'flex: 1; min-width: 0') !== false) {
    echo "✓ Independent content panel with flex: 1\n";
} else {
    echo "✗ Independent content panel not found\n";
}

if (strpos($accountContent, 'd-flex mt-3') !== false && strpos($accountContent, 'gap: 1rem') !== false) {
    echo "✓ Flexbox layout with proper gap\n";
} else {
    echo "✗ Flexbox layout structure not found\n";
}

echo "\n";

// Check developers layout
$developersLayout = 'resources/views/developers/combined.blade.php';
$developersContent = file_get_contents($developersLayout);

echo "Developers Layout:\n";
if (strpos($developersContent, 'width: 170px; flex-shrink: 0') !== false) {
    echo "✓ Sidebar width: 170px (matches account page)\n";
} else {
    echo "✗ Sidebar width not found\n";
}

if (strpos($developersContent, 'flex: 1; min-width: 0') !== false) {
    echo "✓ Independent content panel with flex: 1\n";
} else {
    echo "✗ Independent content panel not found\n";
}

if (strpos($developersContent, 'd-flex mt-3') !== false && strpos($developersContent, 'gap: 1rem') !== false) {
    echo "✓ Flexbox layout with proper gap\n";
} else {
    echo "✗ Flexbox layout structure not found\n";
}

echo "\n2. SIDEBAR BUTTON STRUCTURE VERIFICATION\n";
echo "=======================================\n";

// Check account sidebar
$accountSidebar = 'resources/views/account/sidebar.blade.php';
$accountSidebarContent = file_get_contents($accountSidebar);

echo "Account Sidebar:\n";
if (strpos($accountSidebarContent, 'btn w-100 d-flex align-items-center justify-content-start') !== false) {
    echo "✓ Button structure: Full width, flex, proper alignment\n";
} else {
    echo "✗ Button structure not found\n";
}

if (strpos($accountSidebarContent, 'py-2 px-3') !== false) {
    echo "✓ Button padding: py-2 px-3\n";
} else {
    echo "✗ Button padding not found\n";
}

if (strpos($accountSidebarContent, 'btn-primary') !== false && strpos($accountSidebarContent, 'btn-outline-secondary') !== false) {
    echo "✓ Active/inactive button states\n";
} else {
    echo "✗ Button states not found\n";
}

// Count account menu items
$accountMenuCount = substr_count($accountSidebarContent, 'mb-3');
echo "✓ Account menu items: {$accountMenuCount}\n";

echo "\n";

// Check developers sidebar  
$developersSidebar = 'resources/views/developers/sidebar.blade.php';
$developersSidebarContent = file_get_contents($developersSidebar);

echo "Developers Sidebar:\n";
if (strpos($developersSidebarContent, 'btn w-100 d-flex align-items-center justify-content-start') !== false) {
    echo "✓ Button structure: Full width, flex, proper alignment (matches account)\n";
} else {
    echo "✗ Button structure not found\n";
}

if (strpos($developersSidebarContent, 'py-2 px-3') !== false) {
    echo "✓ Button padding: py-2 px-3 (matches account)\n";
} else {
    echo "✗ Button padding not found\n";
}

if (strpos($developersSidebarContent, 'btn-primary') !== false && strpos($developersSidebarContent, 'btn-outline-secondary') !== false) {
    echo "✓ Active/inactive button states (matches account)\n";
} else {
    echo "✗ Button states not found\n";
}

// Count developers menu items
$developersMenuCount = substr_count($developersSidebarContent, 'mb-3');
echo "✓ Developers menu items: {$developersMenuCount}\n";

if (strpos($developersSidebarContent, 'developer-nav-btn') !== false) {
    echo "✓ Developer-specific navigation class\n";
} else {
    echo "✗ Developer navigation class not found\n";
}

if (strpos($developersSidebarContent, 'data-section=') !== false) {
    echo "✓ Data section attributes for JavaScript navigation\n";
} else {
    echo "✗ Data section attributes not found\n";
}

echo "\n3. CONTENT SECTIONS VERIFICATION\n";
echo "================================\n";

// Count content sections in developers page
$contentSections = substr_count($developersContent, 'class="content-section"');
echo "Developers content sections: {$contentSections}\n";

// List all content sections
preg_match_all('/id="([^"]+)".*class="content-section"|class="content-section".*id="([^"]+)"/', $developersContent, $matches);
$sectionIds = array_merge($matches[1], $matches[2]);
$sectionIds = array_filter($sectionIds); // Remove empty matches

echo "Content section IDs found:\n";
foreach ($sectionIds as $id) {
    echo "  - {$id}\n";
}

// Verify JavaScript navigation exists
if (strpos($developersSidebarContent, 'showSection') !== false) {
    echo "✓ JavaScript navigation function found\n";
} else {
    echo "✗ JavaScript navigation function not found\n";
}

if (strpos($developersSidebarContent, 'addEventListener') !== false) {
    echo "✓ Event listeners for navigation\n";
} else {
    echo "✗ Event listeners not found\n";
}

echo "\n4. CONSISTENCY CHECK\n";
echo "====================\n";

// Check if both pages use the same structure
$accountHasFlexbox = strpos($accountContent, 'd-flex mt-3') !== false;
$developersHasFlexbox = strpos($developersContent, 'd-flex mt-3') !== false;

if ($accountHasFlexbox && $developersHasFlexbox) {
    echo "✓ Both pages use consistent flexbox layout\n";
} else {
    echo "✗ Layout inconsistency between pages\n";
}

$accountSidebarWidth = strpos($accountContent, 'width: 170px') !== false;
$developersSidebarWidth = strpos($developersContent, 'width: 170px') !== false;

if ($accountSidebarWidth && $developersSidebarWidth) {
    echo "✓ Both pages use consistent sidebar width (170px)\n";
} else {
    echo "✗ Sidebar width inconsistency between pages\n";
}

$accountButtonStructure = strpos($accountSidebarContent, 'btn w-100 d-flex') !== false;
$developersButtonStructure = strpos($developersSidebarContent, 'btn w-100 d-flex') !== false;

if ($accountButtonStructure && $developersButtonStructure) {
    echo "✓ Both sidebars use consistent button structure\n";
} else {
    echo "✗ Button structure inconsistency between sidebars\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
echo "Both account and developers pages should now have:\n";
echo "- Consistent 170px sidebar width (25% smaller than original)\n";
echo "- Independent content panels that maintain full width\n";
echo "- Button-style sidebar navigation\n";
echo "- Proper flexbox layout structure\n";
echo "- Matching visual styling and behavior\n";
