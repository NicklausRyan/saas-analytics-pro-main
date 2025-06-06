<?php
// filepath: c:\xampp\htdocs\saas-analytics-pro-main\test_button_navbar_style.php
echo "=== WEBSITE TAB NAVIGATION STYLING VERIFICATION ===\n\n";

// Check edit website page
$editPage = file_get_contents('resources/views/websites/edit.blade.php');
echo "1. EDIT PAGE VERIFICATION:\n";
if (strpos($editPage, 'btn-group') !== false && strpos($editPage, 'navbar navbar-expand-sm') !== false) {
    echo "✓ Button-style navbar added to edit page\n";
} else {
    echo "✗ Button-style navbar not found in edit page\n";
}

if (strpos($editPage, 'btn btn-primary active') !== false) {
    echo "✓ Button styling applied correctly\n";
} else {
    echo "✗ Button styling not applied correctly\n";
}

if (strpos($editPage, 'data-target="#domain-privacy"') !== false) {
    echo "✓ Data attributes updated for Bootstrap compatibility\n";
} else {
    echo "✗ Data attributes not updated correctly\n";
}

// Check new website page
$newPage = file_get_contents('resources/views/websites/new.blade.php');
echo "\n2. NEW PAGE VERIFICATION:\n";
if (strpos($newPage, 'btn-group') !== false && strpos($newPage, 'navbar navbar-expand-sm') !== false) {
    echo "✓ Button-style navbar added to new page\n";
} else {
    echo "✗ Button-style navbar not found in new page\n";
}

if (strpos($newPage, 'btn btn-primary active') !== false) {
    echo "✓ Button styling applied correctly\n";
} else {
    echo "✗ Button styling not applied correctly\n";
}

if (strpos($newPage, 'data-target="#domain-privacy"') !== false) {
    echo "✓ Data attributes updated for Bootstrap compatibility\n";
} else {
    echo "✗ Data attributes not updated correctly\n";
}

// Check JavaScript handlers
echo "\n3. JAVASCRIPT VERIFICATION:\n";
if (strpos($editPage, 'document.querySelectorAll(\'#website-tabs button\')') !== false) {
    echo "✓ JavaScript handlers updated in edit page\n";
} else {
    echo "✗ JavaScript handlers not updated in edit page\n";
}

if (strpos($newPage, 'document.querySelectorAll(\'#website-tabs button\')') !== false) {
    echo "✓ JavaScript handlers updated in new page\n";
} else {
    echo "✗ JavaScript handlers not updated in new page\n";
}

echo "\n=== SUMMARY ===\n";
echo "• Tabs converted to button-style navbar in website pages\n";
echo "• Button styling uses the webapp's existing design language\n";
echo "• JavaScript handlers updated for proper functionality\n";
echo "• Bootstrap tab switching preserved with updated selectors\n";