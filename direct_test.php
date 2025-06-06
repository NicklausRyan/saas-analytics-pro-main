<?php

echo "Direct Revenue Test\n";
echo "==================\n";

// Simple database check first
try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Laravel loaded successfully\n";
    
    // Test database connection
    $websites = \App\Models\Website::count();
    $revenue = \App\Models\Revenue::count();
    
    echo "📊 Database status:\n";
    echo "   - Websites: {$websites}\n";
    echo "   - Revenue records: {$revenue}\n";
    
    // Get a website to test with
    $website = \App\Models\Website::first();
    if ($website) {
        echo "🌐 Testing with website: {$website->domain}\n";
        
        // Try the simple query
        $countries = \DB::table('revenue')
            ->join('recents', 'recents.website_id', '=', 'revenue.website_id')
            ->where('revenue.website_id', $website->id)
            ->select('recents.country', \DB::raw('COUNT(*) as count'))
            ->groupBy('recents.country')
            ->get();
            
        echo "🔍 Simple JOIN test results: " . $countries->count() . " country groups found\n";
        
        foreach ($countries->take(5) as $country) {
            echo "   - {$country->country}: {$country->count} records\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\nTest completed.\n";
