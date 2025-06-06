<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Website;
use App\Models\Revenue;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    /**
     * Seed test data for the test.com domain
     *
     * @return void
     */
    public function run()
    {
        // Create a test user if it doesn't exist
        $user = User::where('email', 'test@example.com')->first();
        
        if (!$user) {
            $user = new User();
            $user->name = 'Test User';
            $user->email = 'test@example.com';
            $user->password = bcrypt('password');
            $user->email_verified_at = now();
            $user->api_token = \Str::random(60);
            $user->role = 0; // Regular user
            $user->locale = 'en';
            $user->timezone = 'UTC';
            $user->has_websites = 1;
            $user->can_track = 1;
            $user->save();
        }
        
        // Create test.com website if it doesn't exist
        $website = Website::where('domain', 'test.com')->first();
        
        if (!$website) {
            $website = new Website();
            $website->domain = 'test.com';
            $website->user_id = $user->id;
            $website->privacy = 0;
            $website->exclude_bots = 1;
            $website->save();
        }
        
        // Define date range for test data (last 30 days)
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        // Insert website stats data
        $this->seedVisitorData($website->id, $startDate, $endDate);
        $this->seedPageviewData($website->id, $startDate, $endDate);
        $this->seedCountryData($website->id, $startDate, $endDate);
        $this->seedBrowserData($website->id, $startDate, $endDate);
        $this->seedOSData($website->id, $startDate, $endDate);
        $this->seedDeviceData($website->id, $startDate, $endDate);
        $this->seedReferrerData($website->id, $startDate, $endDate);
        $this->seedPageData($website->id, $startDate, $endDate);
        $this->seedLandingPageData($website->id, $startDate, $endDate);
        $this->seedLanguageData($website->id, $startDate, $endDate);
        $this->seedCampaignData($website->id, $startDate, $endDate);
        $this->seedBounceData($website->id, $startDate, $endDate);
        $this->seedSessionData($website->id, $startDate, $endDate);
        $this->seedRevenueData($website->id, $startDate, $endDate);
        
        // Insert recents data
        $this->seedRecentData($website->id);
        
        $this->command->info('Test data for test.com has been seeded successfully!');
    }
    
    /**
     * Seed visitor data
     */
    private function seedVisitorData($websiteId, $startDate, $endDate)
    {
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Define base visitor count with slight variation by day of week
            $dayOfWeek = $date->dayOfWeek;
            $baseVisitors = 0;
            
            // Higher on weekdays, lower on weekends
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                $baseVisitors = rand(800, 1200);
            } else {
                $baseVisitors = rand(400, 700);
            }
            
            // Add the visitors stat for the day
            DB::table('stats')->insert([
                'website_id' => $websiteId,
                'name' => 'visitors',
                'value' => $date->format('Y-m-d'),
                'count' => $baseVisitors,
                'date' => $date->format('Y-m-d')
            ]);
            
            $date->addDay();
        }
    }
    
    /**
     * Seed pageview data
     */
    private function seedPageviewData($websiteId, $startDate, $endDate)
    {
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day to calculate pageviews
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            // Calculate pageviews (between 1.5 and 3.5 pages per visitor on average)
            $pagesPerVisitor = mt_rand(15, 35) / 10;
            $pageviews = round($visitorCount * $pagesPerVisitor);
            
            // Add the pageviews stat for the day
            DB::table('stats')->insert([
                'website_id' => $websiteId,
                'name' => 'pageviews',
                'value' => $date->format('Y-m-d'),
                'count' => $pageviews,
                'date' => $date->format('Y-m-d')
            ]);
            
            $date->addDay();
        }
    }
    
    /**
     * Seed country data
     */
    private function seedCountryData($websiteId, $startDate, $endDate)
    {
        $countries = [
            'US:United States' => 35,
            'GB:United Kingdom' => 15,
            'CA:Canada' => 12,
            'DE:Germany' => 8,
            'FR:France' => 7,
            'AU:Australia' => 6,
            'IN:India' => 5,
            'NL:Netherlands' => 3,
            'BR:Brazil' => 3,
            'JP:Japan' => 2,
            'ES:Spain' => 2,
            'IT:Italy' => 2
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            $remainingVisitors = $visitorCount;
            $processedVisitors = 0;
            
            foreach ($countries as $country => $percentage) {
                if ($country === array_key_last($countries)) {
                    // For the last country, use all remaining visitors
                    $countryVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-2, 2); // +/- 2% variation
                    $adjustedPercentage = max(1, $percentage + $variation);
                    $countryVisitors = round($visitorCount * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $countryVisitors = min($countryVisitors, $remainingVisitors);
                    $remainingVisitors -= $countryVisitors;
                    $processedVisitors += $countryVisitors;
                }
                
                if ($countryVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'country',
                        'value' => $country,
                        'count' => $countryVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed browser data
     */
    private function seedBrowserData($websiteId, $startDate, $endDate)
    {
        $browsers = [
            'Chrome' => 64,
            'Safari' => 18,
            'Firefox' => 8,
            'Edge' => 5,
            'Opera' => 2,
            'Samsung Internet' => 2,
            'Internet Explorer' => 1
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            $remainingVisitors = $visitorCount;
            
            foreach ($browsers as $browser => $percentage) {
                if ($browser === array_key_last($browsers)) {
                    // For the last browser, use all remaining visitors
                    $browserVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-1, 1); // +/- 1% variation
                    $adjustedPercentage = max(0.5, $percentage + $variation);
                    $browserVisitors = round($visitorCount * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $browserVisitors = min($browserVisitors, $remainingVisitors);
                    $remainingVisitors -= $browserVisitors;
                }
                
                if ($browserVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'browser',
                        'value' => $browser,
                        'count' => $browserVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed OS data
     */
    private function seedOSData($websiteId, $startDate, $endDate)
    {
        $operatingSystems = [
            'Windows' => 45,
            'macOS' => 25,
            'iOS' => 15,
            'Android' => 12,
            'Linux' => 2,
            'ChromeOS' => 1
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            $remainingVisitors = $visitorCount;
            
            foreach ($operatingSystems as $os => $percentage) {
                if ($os === array_key_last($operatingSystems)) {
                    // For the last OS, use all remaining visitors
                    $osVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-2, 2); // +/- 2% variation
                    $adjustedPercentage = max(0.5, $percentage + $variation);
                    $osVisitors = round($visitorCount * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $osVisitors = min($osVisitors, $remainingVisitors);
                    $remainingVisitors -= $osVisitors;
                }
                
                if ($osVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'os',
                        'value' => $os,
                        'count' => $osVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed device data
     */
    private function seedDeviceData($websiteId, $startDate, $endDate)
    {
        $devices = [
            'Desktop' => 65,
            'Mobile' => 28,
            'Tablet' => 7
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            $remainingVisitors = $visitorCount;
            
            foreach ($devices as $device => $percentage) {
                if ($device === array_key_last($devices)) {
                    // For the last device, use all remaining visitors
                    $deviceVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-2, 2); // +/- 2% variation
                    $adjustedPercentage = max(1, $percentage + $variation);
                    $deviceVisitors = round($visitorCount * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $deviceVisitors = min($deviceVisitors, $remainingVisitors);
                    $remainingVisitors -= $deviceVisitors;
                }
                
                if ($deviceVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'device',
                        'value' => $device,
                        'count' => $deviceVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed referrer data
     */
    private function seedReferrerData($websiteId, $startDate, $endDate)
    {
        $referrers = [
            'google.com' => 45,
            'facebook.com' => 15,
            'twitter.com' => 10,
            'linkedin.com' => 8,
            'bing.com' => 5,
            'instagram.com' => 5,
            'youtube.com' => 4,
            'reddit.com' => 3,
            'duckduckgo.com' => 2,
            't.co' => 2,
            'medium.com' => 1
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            // Only 60-70% of visitors have referrer data
            $visitorWithReferrers = round($visitorCount * mt_rand(60, 70) / 100);
            $remainingVisitors = $visitorWithReferrers;
            
            foreach ($referrers as $referrer => $percentage) {
                if ($referrer === array_key_last($referrers)) {
                    // For the last referrer, use all remaining visitors
                    $referrerVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-2, 2); // +/- 2% variation
                    $adjustedPercentage = max(0.5, $percentage + $variation);
                    $referrerVisitors = round($visitorWithReferrers * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $referrerVisitors = min($referrerVisitors, $remainingVisitors);
                    $remainingVisitors -= $referrerVisitors;
                }
                
                if ($referrerVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'referrer',
                        'value' => $referrer,
                        'count' => $referrerVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            // Add direct traffic (no referrer)
            $directVisitors = $visitorCount - $visitorWithReferrers;
            if ($directVisitors > 0) {
                DB::table('stats')->insert([
                    'website_id' => $websiteId,
                    'name' => 'referrer',
                    'value' => '',
                    'count' => $directVisitors,
                    'date' => $date->format('Y-m-d')
                ]);
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed page data
     */
    private function seedPageData($websiteId, $startDate, $endDate)
    {
        $pages = [
            '/' => 35,
            '/blog' => 15,
            '/pricing' => 12,
            '/features' => 10,
            '/about-us' => 8,
            '/contact' => 6,
            '/blog/top-10-analytics-tools' => 4,
            '/blog/how-to-increase-conversions' => 3,
            '/blog/web-design-trends' => 3,
            '/docs' => 2,
            '/faq' => 2
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get pageview count for this day
            $pageviewCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'pageviews')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            $remainingPageviews = $pageviewCount;
            
            foreach ($pages as $page => $percentage) {
                if ($page === array_key_last($pages)) {
                    // For the last page, use all remaining pageviews
                    $pagePageviews = $remainingPageviews;
                } else {
                    // Calculate pageview count based on percentage with some variation
                    $variation = mt_rand(-2, 2); // +/- 2% variation
                    $adjustedPercentage = max(0.5, $percentage + $variation);
                    $pagePageviews = round($pageviewCount * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining pageview count
                    $pagePageviews = min($pagePageviews, $remainingPageviews);
                    $remainingPageviews -= $pagePageviews;
                }
                
                if ($pagePageviews > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'page',
                        'value' => $page,
                        'count' => $pagePageviews,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed landing page data
     */
    private function seedLandingPageData($websiteId, $startDate, $endDate)
    {
        $landingPages = [
            '/' => 40,
            '/blog' => 20,
            '/pricing' => 12,
            '/features' => 10,
            '/blog/top-10-analytics-tools' => 8,
            '/blog/how-to-increase-conversions' => 6,
            '/about-us' => 4
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            $remainingVisitors = $visitorCount;
            
            foreach ($landingPages as $page => $percentage) {
                if ($page === array_key_last($landingPages)) {
                    // For the last landing page, use all remaining visitors
                    $landingPageVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-2, 2); // +/- 2% variation
                    $adjustedPercentage = max(0.5, $percentage + $variation);
                    $landingPageVisitors = round($visitorCount * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $landingPageVisitors = min($landingPageVisitors, $remainingVisitors);
                    $remainingVisitors -= $landingPageVisitors;
                }
                
                if ($landingPageVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'landing_page',
                        'value' => $page,
                        'count' => $landingPageVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed language data
     */
    private function seedLanguageData($websiteId, $startDate, $endDate)
    {
        $languages = [
            'en' => 60,
            'es' => 12,
            'fr' => 8,
            'de' => 6,
            'pt' => 4,
            'it' => 3,
            'nl' => 3,
            'ja' => 2,
            'ru' => 2
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            $remainingVisitors = $visitorCount;
            
            foreach ($languages as $language => $percentage) {
                if ($language === array_key_last($languages)) {
                    // For the last language, use all remaining visitors
                    $languageVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-1, 1); // +/- 1% variation
                    $adjustedPercentage = max(0.5, $percentage + $variation);
                    $languageVisitors = round($visitorCount * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $languageVisitors = min($languageVisitors, $remainingVisitors);
                    $remainingVisitors -= $languageVisitors;
                }
                
                if ($languageVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'language',
                        'value' => $language,
                        'count' => $languageVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed campaign data
     */
    private function seedCampaignData($websiteId, $startDate, $endDate)
    {
        $campaigns = [
            'summer_promo' => 35,
            'email_newsletter' => 25,
            'black_friday' => 20,
            'google_ads' => 15,
            'facebook_campaign' => 5
        ];
        
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            // Only 15-25% of visitors come from campaigns
            $campaignVisitors = round($visitorCount * mt_rand(15, 25) / 100);
            $remainingVisitors = $campaignVisitors;
            
            foreach ($campaigns as $campaign => $percentage) {
                if ($campaign === array_key_last($campaigns)) {
                    // For the last campaign, use all remaining visitors
                    $campaignSpecificVisitors = $remainingVisitors;
                } else {
                    // Calculate visitor count based on percentage with some variation
                    $variation = mt_rand(-2, 2); // +/- 2% variation
                    $adjustedPercentage = max(1, $percentage + $variation);
                    $campaignSpecificVisitors = round($campaignVisitors * $adjustedPercentage / 100);
                    
                    // Ensure we don't exceed the remaining visitor count
                    $campaignSpecificVisitors = min($campaignSpecificVisitors, $remainingVisitors);
                    $remainingVisitors -= $campaignSpecificVisitors;
                }
                
                if ($campaignSpecificVisitors > 0) {
                    DB::table('stats')->insert([
                        'website_id' => $websiteId,
                        'name' => 'campaign',
                        'value' => $campaign,
                        'count' => $campaignSpecificVisitors,
                        'date' => $date->format('Y-m-d')
                    ]);
                }
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed bounce data
     */
    private function seedBounceData($websiteId, $startDate, $endDate)
    {
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            // Bounce rate between 35% and 45%
            $bounceRate = mt_rand(35, 45) / 100;
            $bounceCount = round($visitorCount * $bounceRate);
            
            DB::table('stats')->insert([
                'website_id' => $websiteId,
                'name' => 'bounce',
                'value' => 'bounced',
                'count' => $bounceCount,
                'date' => $date->format('Y-m-d')
            ]);
            
            $date->addDay();
        }
    }
    
    /**
     * Seed session data
     */
    private function seedSessionData($websiteId, $startDate, $endDate)
    {
        $date = clone $startDate;
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            // Most visitors have one session, some have multiple
            // Let's say visitors have between 1.05 and 1.15 sessions on average
            $sessionMultiplier = mt_rand(105, 115) / 100;
            $sessionCount = round($visitorCount * $sessionMultiplier);
            
            DB::table('stats')->insert([
                'website_id' => $websiteId,
                'name' => 'session',
                'value' => 'sessions',
                'count' => $sessionCount,
                'date' => $date->format('Y-m-d')
            ]);
            
            // Average session duration between 120 and 240 seconds
            $avgDuration = mt_rand(120, 240);
            
            DB::table('stats')->insert([
                'website_id' => $websiteId,
                'name' => 'session_duration',
                'value' => 'avg_duration',
                'count' => $avgDuration,
                'date' => $date->format('Y-m-d')
            ]);
            
            $date->addDay();
        }
    }
    
    /**
     * Seed revenue data
     */
    private function seedRevenueData($websiteId, $startDate, $endDate)
    {
        $date = clone $startDate;
        
        $countries = [
            'US' => 40,
            'GB' => 15,
            'CA' => 12,
            'DE' => 10,
            'FR' => 8,
            'AU' => 6,
            'IN' => 5,
            'NL' => 4
        ];
        
        $browsers = [
            'Chrome' => 65,
            'Safari' => 20,
            'Firefox' => 8,
            'Edge' => 5,
            'Opera' => 2
        ];
        
        $sources = [
            'stripe' => 80,
            'manual' => 20
        ];
        
        while ($date->lte($endDate)) {
            // Get visitor count for this day
            $visitorCount = DB::table('stats')
                ->where('website_id', $websiteId)
                ->where('name', 'visitors')
                ->where('date', $date->format('Y-m-d'))
                ->value('count') ?? 0;
            
            // Conversion rate between 1.5% and 3%
            $conversionRate = mt_rand(15, 30) / 1000;
            $transactionCount = max(1, round($visitorCount * $conversionRate));
            
            for ($i = 0; $i < $transactionCount; $i++) {
                // Amount between $20 and $200
                $amount = mt_rand(2000, 20000) / 100;
                
                // Generate unique order ID
                $orderId = 'ORD-' . strtoupper(substr(md5(microtime()), 0, 10));
                
                // Random visitor ID
                $visitorId = 'VISITOR-' . strtoupper(substr(md5(mt_rand()), 0, 8));
                
                // Select country based on probability distribution
                $country = $this->getRandomWeighted($countries);
                
                // Select browser based on probability distribution
                $browser = $this->getRandomWeighted($browsers);
                
                // Select source based on probability distribution
                $source = $this->getRandomWeighted($sources);
                
                // Create revenue record
                Revenue::create([
                    'website_id' => $websiteId,
                    'amount' => $amount,
                    'currency' => 'USD',
                    'order_id' => $orderId,
                    'visitor_id' => $visitorId,
                    'source' => $source,
                    'date' => $date->format('Y-m-d'),
                    'created_at' => $date->copy()->addHours(mt_rand(0, 23))->addMinutes(mt_rand(0, 59))->format('Y-m-d H:i:s'),
                    'updated_at' => $date->copy()->addHours(mt_rand(0, 23))->addMinutes(mt_rand(0, 59))->format('Y-m-d H:i:s')
                ]);
            }
            
            $date->addDay();
        }
    }
    
    /**
     * Seed recent data
     */
    private function seedRecentData($websiteId)
    {
        // Pages people might visit
        $pages = [
            '/',
            '/blog',
            '/pricing',
            '/features',
            '/about-us',
            '/contact',
            '/blog/top-10-analytics-tools',
            '/blog/how-to-increase-conversions',
            '/blog/web-design-trends',
            '/docs',
            '/faq'
        ];
        
        // Referrers
        $referrers = [
            'google.com',
            'facebook.com',
            'twitter.com',
            'linkedin.com',
            'bing.com',
            '',  // Direct traffic
            '',
            ''
        ];
        
        // Operating systems
        $operatingSystems = [
            'Windows',
            'macOS',
            'iOS',
            'Android',
            'Linux',
            'ChromeOS'
        ];
        
        // Browsers
        $browsers = [
            'Chrome',
            'Safari',
            'Firefox',
            'Edge',
            'Opera',
            'Samsung Internet'
        ];
        
        // Devices
        $devices = [
            'Desktop',
            'Mobile',
            'Tablet'
        ];
        
        // Countries
        $countries = [
            'United States',
            'United Kingdom',
            'Canada',
            'Germany',
            'France',
            'Australia',
            'India',
            'Netherlands'
        ];
        
        // Cities
        $cities = [
            'New York',
            'London',
            'Toronto',
            'Berlin',
            'Paris',
            'Sydney',
            'Mumbai',
            'Amsterdam',
            'Los Angeles',
            'Chicago',
            'San Francisco'
        ];
        
        // Languages
        $languages = ['en', 'es', 'fr', 'de', 'pt', 'it', 'nl', 'ja', 'ru'];
        
        // Create 100 recent records spanning the last hour
        $now = Carbon::now();
        
        for ($i = 0; $i < 100; $i++) {
            $minutesAgo = $i % 60; // Distribute over the last hour
            $timestamp = $now->copy()->subMinutes($minutesAgo);
            
            DB::table('recents')->insert([
                'website_id' => $websiteId,
                'page' => $pages[array_rand($pages)],
                'referrer' => $referrers[array_rand($referrers)],
                'os' => $operatingSystems[array_rand($operatingSystems)],
                'browser' => $browsers[array_rand($browsers)],
                'device' => $devices[array_rand($devices)],
                'country' => $countries[array_rand($countries)],
                'city' => $cities[array_rand($cities)],
                'language' => $languages[array_rand($languages)],
                'created_at' => $timestamp
            ]);
        }
    }
    
    /**
     * Get a random value based on weighted probabilities
     */
    private function getRandomWeighted(array $weightedValues)
    {
        $rand = mt_rand(1, 100);
        $total = 0;
        
        foreach ($weightedValues as $value => $weight) {
            $total += $weight;
            if ($rand <= $total) {
                return $value;
            }
        }
        
        return array_key_first($weightedValues);
    }
}
