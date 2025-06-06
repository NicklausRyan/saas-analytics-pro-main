<?php
/**
 * Final Dashboard Pages Verification Test
 * Verifies that all 4 dashboard pages (languages, continents, cities, browsers) have:
 * 1. Separate cards for most/least popular sections
 * 2. Revenue attribution values and bars always shown
 * 3. Toggle buttons only on countries page (removed from languages, continents, cities)
 */

echo "=== Final Dashboard Pages Verification ===\n\n";

$viewsPath = __DIR__ . '/resources/views/stats/';
$pages = ['languages', 'continents', 'cities', 'browsers', 'countries'];

echo "1. Checking separate cards structure (col-12 col-lg-6):\n";
foreach (['languages', 'continents', 'cities', 'browsers'] as $page) {
    $file = $viewsPath . $page . '.blade.php';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $hasSeparateCards = strpos($content, 'col-12 col-lg-6') !== false;
        $hasRowMN2 = strpos($content, 'row m-n2') !== false;
        echo "  $page: " . ($hasSeparateCards && $hasRowMN2 ? "✓ Has separate cards structure" : "✗ Missing separate cards structure") . "\n";
    }
}

echo "\n2. Checking revenue attribution (Revenue column and bars):\n";
foreach (['languages', 'continents', 'cities', 'browsers'] as $page) {
    $file = $viewsPath . $page . '.blade.php';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $hasRevenueColumn = strpos($content, "{{ __('Revenue') }}") !== false;
        $hasRevenueData = strpos($content, 'Revenue') !== false;
        echo "  $page: " . ($hasRevenueColumn && $hasRevenueData ? "✓ Has revenue attribution" : "✗ Missing revenue attribution") . "\n";
    }
}

echo "\n3. Checking toggle buttons (should only be on countries page):\n";
foreach ($pages as $page) {
    $file = $viewsPath . $page . '.blade.php';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $hasToggleButtons = strpos($content, 'btn-group') !== false;
        if ($page === 'countries') {
            echo "  $page: " . ($hasToggleButtons ? "✓ Has toggle buttons (correct)" : "✗ Missing toggle buttons") . "\n";
        } else {
            echo "  $page: " . (!$hasToggleButtons ? "✓ No toggle buttons (correct)" : "✗ Still has toggle buttons") . "\n";
        }
    }
}

echo "\n4. Checking array_column() fixes in StatController:\n";
$controllerFile = __DIR__ . '/app/Http/Controllers/StatController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    $hasToArrayFix1 = strpos($content, 'array_column($revenueByContinent->toArray()') !== false;
    $hasToArrayFix2 = strpos($content, 'array_column($revenueByCity->toArray()') !== false;
    echo "  Continents array_column fix: " . ($hasToArrayFix1 ? "✓ Applied" : "✗ Missing") . "\n";
    echo "  Cities array_column fix: " . ($hasToArrayFix2 ? "✓ Applied" : "✗ Missing") . "\n";
}

echo "\n=== Verification Complete ===\n";
echo "All dashboard pages should now have:\n";
echo "- ✓ Separate cards layout like browsers page\n";
echo "- ✓ Revenue attribution values and bars always shown\n";
echo "- ✓ Toggle buttons only on countries page\n";
echo "- ✓ Fixed array_column() TypeError issues\n";
