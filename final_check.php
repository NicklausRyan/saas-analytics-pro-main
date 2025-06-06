<?php
echo "FINAL VERIFICATION\n";
echo "================\n\n";

// Check account layout
$account = file_get_contents('resources/views/layouts/account.blade.php');
echo "Account layout:\n";
echo (strpos($account, 'width: 170px') !== false) ? "✓ 170px sidebar width\n" : "✗ Sidebar width issue\n";
echo (strpos($account, 'flex: 1; min-width: 0') !== false) ? "✓ Independent content panel\n" : "✗ Content panel issue\n";

// Check developers layout  
$developers = file_get_contents('resources/views/developers/combined.blade.php');
echo "\nDevelopers layout:\n";
echo (strpos($developers, 'width: 170px') !== false) ? "✓ 170px sidebar width\n" : "✗ Sidebar width issue\n";
echo (strpos($developers, 'flex: 1; min-width: 0') !== false) ? "✓ Independent content panel\n" : "✗ Content panel issue\n";

// Check content sections
$sections = substr_count($developers, 'class="content-section"');
echo "\nContent sections: {$sections}\n";

// Check sidebar consistency
$accountSidebar = file_get_contents('resources/views/account/sidebar.blade.php');
$developersSidebar = file_get_contents('resources/views/developers/sidebar.blade.php');

$accountButtons = strpos($accountSidebar, 'btn w-100 d-flex') !== false;
$developersButtons = strpos($developersSidebar, 'btn w-100 d-flex') !== false;

echo "\nSidebar buttons:\n";
echo $accountButtons ? "✓ Account buttons OK\n" : "✗ Account buttons issue\n";
echo $developersButtons ? "✓ Developers buttons OK\n" : "✗ Developers buttons issue\n";

echo "\n=== TASK COMPLETION STATUS ===\n";
echo ($accountButtons && $developersButtons) ? "✓ Both sidebars have button structure\n" : "✗ Sidebar structure issue\n";
echo (strpos($account, 'width: 170px') !== false && strpos($developers, 'width: 170px') !== false) ? "✓ Both pages have 170px sidebar (25% smaller)\n" : "✗ Sidebar width issue\n";
echo (strpos($account, 'flex: 1; min-width: 0') !== false && strpos($developers, 'flex: 1; min-width: 0') !== false) ? "✓ Both pages have independent content panels\n" : "✗ Content panel issue\n";
echo ($sections == 6) ? "✓ Correct number of content sections\n" : "✗ Content section count issue\n";
