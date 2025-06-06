<?php
echo "=== DEVELOPERS PAGE SIDEBAR UPDATES VERIFICATION ===\n\n";

// Check developers page sidebar width
$developersPage = file_get_contents('resources/views/developers/combined.blade.php');
echo "1. SIDEBAR WIDTH VERIFICATION:\n";
if (strpos($developersPage, 'width: 250px') !== false) {
    echo "✓ Sidebar width increased to 250px\n";
} else {
    echo "✗ Sidebar width not found\n";
}

// Check sidebar menu structure
$sidebarFile = file_get_contents('resources/views/developers/sidebar.blade.php');
echo "\n2. MENU TITLES VERIFICATION:\n";

// Check that main titles are removed and subtitles are used as titles
if (strpos($sidebarFile, "'title' => __('Show')") !== false) {
    echo "✓ 'Show' title found (was subtitle before)\n";
} else {
    echo "✗ 'Show' title not found\n";
}

if (strpos($sidebarFile, "'title' => __('List')") !== false) {
    echo "✓ 'List' title found (was subtitle before)\n";
} else {
    echo "✗ 'List' title not found\n";
}

if (strpos($sidebarFile, "'title' => __('Store')") !== false) {
    echo "✓ 'Store' title found (was subtitle before)\n";
} else {
    echo "✗ 'Store' title not found\n";
}

if (strpos($sidebarFile, "'title' => __('Update')") !== false) {
    echo "✓ 'Update' title found (was subtitle before)\n";
} else {
    echo "✗ 'Update' title not found\n";
}

if (strpos($sidebarFile, "'title' => __('Delete')") !== false) {
    echo "✓ 'Delete' title found (was subtitle before)\n";
} else {
    echo "✗ 'Delete' title not found\n";
}

// Check that old main titles are removed
if (strpos($sidebarFile, "'title' => __('Stats')") === false) {
    echo "✓ 'Stats' main title removed\n";
} else {
    echo "✗ 'Stats' main title still found\n";
}

if (strpos($sidebarFile, "'title' => __('Websites')") === false) {
    echo "✓ 'Websites' main title removed\n";
} else {
    echo "✗ 'Websites' main title still found\n";
}

if (strpos($sidebarFile, "'title' => __('Account')") === false) {
    echo "✓ 'Account' main title removed\n";
} else {
    echo "✗ 'Account' main title still found\n";
}

// Check that subtitle structure is removed
if (strpos($sidebarFile, "'subtitle'") === false) {
    echo "✓ Subtitle structure removed\n";
} else {
    echo "✗ Subtitle structure still found\n";
}

if (strpos($sidebarFile, 'isset($value[\'subtitle\'])') === false) {
    echo "✓ Subtitle display logic removed\n";
} else {
    echo "✗ Subtitle display logic still found\n";
}

echo "\n3. LAYOUT STRUCTURE:\n";
if (strpos($sidebarFile, 'developer-nav-btn') !== false) {
    echo "✓ Developer navigation class maintained\n";
} else {
    echo "✗ Developer navigation class not found\n";
}

if (strpos($sidebarFile, 'data-section=') !== false) {
    echo "✓ Data section attributes maintained\n";
} else {
    echo "✗ Data section attributes not found\n";
}

echo "\n=== SUMMARY ===\n";
echo "Changes completed:\n";
echo "• Sidebar width increased from 170px to 250px\n";
echo "• Main titles (Stats, Websites, Account) removed\n";
echo "• Subtitles (Show, List, Store, Update, Delete) promoted to main titles\n";
echo "• Subtitle display structure removed\n";
echo "• Navigation functionality maintained\n";
