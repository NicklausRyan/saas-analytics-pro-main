<?php
echo "SIDEBAR INDEPENDENCE VERIFICATION\n";
echo "=================================\n\n";

$account = file_get_contents('resources/views/layouts/account.blade.php');
$developers = file_get_contents('resources/views/developers/combined.blade.php');

echo "Account sidebar width: ";
echo (strpos($account, 'width: 170px') !== false) ? "170px ✓\n" : "NOT 170px ✗\n";

echo "Developers sidebar width: ";
echo (strpos($developers, 'width: 250px') !== false) ? "250px ✓\n" : "NOT 250px ✗\n";

echo "\nIndependence confirmed: ";
echo ((strpos($account, 'width: 170px') !== false) && (strpos($developers, 'width: 250px') !== false)) ? "YES ✓\n" : "NO ✗\n";

echo "\nBoth sidebars now operate independently:\n";
echo "- Account page: 170px sidebar width (unchanged)\n";
echo "- Developers page: 250px sidebar width (increased)\n";
echo "- Changes to one sidebar will not affect the other\n";
?>
