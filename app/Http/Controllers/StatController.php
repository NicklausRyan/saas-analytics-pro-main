<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateWebsitePasswordRequest;
use App\Traits\DateRangeTrait;
use App\Models\Website;
use App\Models\Recent;
use App\Models\Stat;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Csv as CSV;

class StatController extends Controller
{
    use DateRangeTrait;

    /**
     * Show the Overview stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        }

        $range = $this->range();

        $visitorsMap = $this->getTraffic($website, $range, 'visitors');
        $pageviewsMap = $this->getTraffic($website, $range, 'pageviews');

        $totalVisitors = $totalPageviews = 0;
        foreach ($visitorsMap as $key => $value) {
            $totalVisitors = $totalVisitors + $value;
        }

        foreach ($pageviewsMap as $key => $value) {
            $totalPageviews = $totalPageviews + $value;
        }

        $totalVisitorsOld = Stat::where([['website_id', '=', $website->id], ['name', '=', 'visitors']])
            ->whereBetween('date', [$range['from_old'], $range['to_old']])
            ->sum('count');

        $totalPageviewsOld = Stat::where([['website_id', '=', $website->id], ['name', '=', 'pageviews']])
            ->whereBetween('date', [$range['from_old'], $range['to_old']])
            ->sum('count');

        $pages = $this->getPages($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $totalReferrers = Stat::where([['website_id', '=', $website->id], ['name', '=', 'referrer']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('count');

        $referrers = $this->getReferrers($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        // Get standard visitors statistics
        $countries = $this->getCountries($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $browsers = $this->getBrowsers($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $operatingSystems = $this->getOperatingSystems($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();
            
        // Get attribution data with revenue metrics
        $countriesWithRevenue = $this->getRevenueByCountry($website, $range);
        $browsersWithRevenue = $this->getRevenueByBrowser($website, $range);
        $operatingSystemsWithRevenue = $this->getRevenueByOperatingSystem($website, $range);
        
        $events = $this->getEvents($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();
            
        // Calculate bounce rate
        $bounceCount = Stat::where([['website_id', '=', $website->id], ['name', '=', 'bounce']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('count');
            
        // Safe calculation to avoid division by zero
        $bounceRate = $totalVisitors > 0 ? $bounceCount / $totalVisitors : 0;
        
        // Get average session duration
        $sessionDurationSum = Stat::where([['website_id', '=', $website->id], ['name', '=', 'session_duration']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('value');
            
        $sessionCount = Stat::where([['website_id', '=', $website->id], ['name', '=', 'session']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('count');        
            
        // Safe calculation to avoid division by zero
        $avgSessionDuration = $sessionCount > 0 ? (int)($sessionDurationSum / $sessionCount) : 0;
        
        // Get revenue data for the dashboard
        $totalRevenue = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('amount');
            
        $totalRevenueOld = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from_old'], $range['to_old']])
            ->sum('amount');
            
        $primaryCurrency = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->orderBy('date', 'desc')
            ->value('currency') ?? '';
            
        // Get revenue data by day for the chart
        $revenueMap = $this->getRevenueByDay($website, $range);
        
        // Calculate revenue per visitor
        $revenuePerVisitor = $totalVisitors > 0 ? $totalRevenue / $totalVisitors : 0;
        
        // Calculate conversion rate (revenue events / total sessions)
        $revenueEventsCount = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->count();
        
        $conversionRate = $sessionCount > 0 ? $revenueEventsCount / $sessionCount : 0;
        
        return view('stats.container', [
            'view' => 'overview', 
            'website' => $website, 
            'range' => $range, 
            'referrers' => $referrers, 
            'pages' => $pages, 
            'visitorsMap' => $visitorsMap, 
            'pageviewsMap' => $pageviewsMap, 
            'countries' => $countries, 
            'browsers' => $browsers, 
            'operatingSystems' => $operatingSystems,
            'countriesWithRevenue' => $countriesWithRevenue,
            'browsersWithRevenue' => $browsersWithRevenue,
            'operatingSystemsWithRevenue' => $operatingSystemsWithRevenue,
            'events' => $events, 
            'totalVisitors' => $totalVisitors, 
            'totalPageviews' => $totalPageviews, 
            'totalVisitorsOld' => $totalVisitorsOld, 
            'totalPageviewsOld' => $totalPageviewsOld, 
            'totalReferrers' => $totalReferrers, 
            'bounceRate' => $bounceRate, 
            'avgSessionDuration' => $avgSessionDuration,
            'totalRevenue' => $totalRevenue,
            'totalRevenueOld' => $totalRevenueOld,
            'primaryCurrency' => $primaryCurrency,
            'revenueMap' => $revenueMap,
            'revenuePerVisitor' => $revenuePerVisitor,
            'conversionRate' => $conversionRate
        ]);
    }

    /**
     * Show the Realtime stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function realtime(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();

        if ($request->wantsJson()) {
            // Date ranges
            $to = Carbon::now();
            $from = $to->copy()->subMinutes(1);
            $to_old = (clone $from)->subSecond(1);
            $from_old = (clone $to_old)->subMinutes(1);

            // Get the available dates
            $visitorsMap = $pageviewsMap = $this->calcAllDates($from, $to, 'second', 'Y-m-d H:i:s', ['count' => 0]);

            $visitors = Recent::selectRaw('COUNT(`website_id`) as `count`, `created_at`')
                ->where('website_id', '=', $website->id)
                ->where(function ($query) use ($website)
                {
                    $query->where('referrer', '<>', $website->domain)
                        ->orWhereNull('referrer');
                })
                ->whereBetween('created_at', [$from->format('Y-m-d H:i:s'), $to->format('Y-m-d H:i:s')])
                ->groupBy('created_at')
                ->get();

            $pageviews = Recent::selectRaw('COUNT(`website_id`) as `count`, `created_at`')
                ->where([['website_id', '=', $website->id]])
                ->whereBetween('created_at', [$from->format('Y-m-d H:i:s'), $to->format('Y-m-d H:i:s')])
                ->groupBy('created_at')
                ->get();

            $recent = Recent::where([['website_id', '=', $website->id]])
                ->whereBetween('created_at', [$from->format('Y-m-d H:i:s'), $to->format('Y-m-d H:i:s')])
                ->orderBy('id', 'desc')
                ->limit(25)
                ->get();

            $visitorsOld = Recent::where('website_id', '=', $website->id)
                ->where(function ($query) use ($website)
                {
                    $query->where('referrer', '<>', $website->domain)
                        ->orWhereNull('referrer');
                })
                ->whereBetween('created_at', [$from_old->format('Y-m-d H:i:s'), $to_old->format('Y-m-d H:i:s')])
                ->count();

            $pageviewsOld = Recent::where([['website_id', '=', $website->id]])
                ->whereBetween('created_at', [$from_old->format('Y-m-d H:i:s'), $to_old->format('Y-m-d H:i:s')])
                ->count();

            $totalVisitors = $totalPageviews = 0;

            // Map the values to each date
            foreach ($visitors as $visitor) {
                // Map the value to each date
                $visitorsMap[$visitor->created_at->format('Y-m-d H:i:s')] = $visitor->count;
                $totalVisitors = $totalVisitors + $visitor->count;
            }

            foreach ($pageviews as $pageview) {
                // Map the value to each date
                $pageviewsMap[$pageview->created_at->format('Y-m-d H:i:s')] = $pageview->count;
                $totalPageviews = $totalPageviews + $pageview->count;
            }

            $visitorsCount = $pageviewsCount = [];
            foreach ($visitorsMap as $key => $value) {
                // Remap the key
                $visitorsCount[Carbon::createFromDate($key)->diffForHumans(['options' => Carbon::JUST_NOW])] = $value;
            }

            foreach ($pageviewsMap as $key => $value) {
                // Remap the key
                $pageviewsCount[Carbon::createFromDate($key)->diffForHumans(['options' => Carbon::JUST_NOW])] = $value;
            }

            return response()->json([
                'visitors' => $visitorsCount,
                'pageviews' => $pageviewsCount,
                'visitors_growth' => view('stats.growth', ['growthCurrent' => $totalVisitors, 'growthPrevious' => $visitorsOld])->render(),
                'pageviews_growth' => view('stats.growth', ['growthCurrent' => $totalPageviews, 'growthPrevious' => $pageviewsOld])->render(),
                'recent' => view('stats.recent', ['website' => $website, 'range' => $range, 'recent' => $recent])->render(),
                'status' => 200
            ], 200);
        }

        return view('stats.container', ['view' => 'realtime', 'website' => $website, 'range' => $range]);
    }

    /**
     * Show the Pages stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'page']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $pages = $this->getPages($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getPages($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getPages($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'pages', 'website' => $website, 'range' => $range, 'export' => 'stats.export.pages', 'pages' => $pages, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Landing Pages stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function landingPages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'landing_page']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $landingPages = $this->getLandingPages($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getLandingPages($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getLandingPages($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'landing-pages', 'website' => $website, 'range' => $range, 'export' => 'stats.export.landing_pages', 'landingPages' => $landingPages, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Exit Pages stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function exitPages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'exit_page']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $exitPages = $this->getExitPages($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getExitPages($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();        $last = $this->getExitPages($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'exit-pages', 'website' => $website, 'range' => $range, 'export' => 'stats.export.exit_pages', 'exitPages' => $exitPages, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Revenue stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */    public function revenue(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['order_id', 'currency']) ? $request->input('search_by') : 'order_id';
        $sortBy = in_array($request->input('sort_by'), ['amount', 'date', 'currency']) ? $request->input('sort_by') : 'date';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        // Get revenue data
        $revenueQuery = $this->getRevenue($website, $range, $search, $searchBy, $sortBy, $sort);
        $revenue = $revenueQuery->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        // Calculate total revenue
        $totalRevenue = $this->getRevenue($website, $range)->sum('amount');
        
        // Calculate additional revenue metrics
        $revenueSummary = $this->getRevenueSummary($website, $range);
        
        // Get revenue by day (for chart)
        $revenueByDay = $this->getRevenueByDay($website, $range);
        
        return view('stats.container', [
            'view' => 'revenue', 
            'website' => $website, 
            'range' => $range, 
            'export' => 'stats.export.revenue',
            'revenue' => $revenue,
            'totalRevenue' => $totalRevenue,
            'revenueSummary' => $revenueSummary,
            'revenueByDay' => $revenueByDay
        ]);
    }

    /**
     * Show the Referrers stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function referrers(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'referrer'], ['value', '<>', $website->domain], ['value', '<>', '']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $referrers = $this->getReferrers($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getReferrers($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getReferrers($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'referrers', 'website' => $website, 'range' => $range, 'export' => 'stats.export.referrers', 'referrers' => $referrers, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Search Engines stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchEngines(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');
        $websites = $this->getSearchEnginesList();

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'referrer']])
            ->whereIn('value', $websites)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $searchEngines = $this->getSearchEngines($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getSearchEngines($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getSearchEngines($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'search-engines', 'website' => $website, 'range' => $range, 'export' => 'stats.export.search_engines', 'searchEngines' => $searchEngines, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Social Networks stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function socialNetworks(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');
        $websites = $this->getSocialNetworksList();

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'referrer']])
            ->whereIn('value', $websites)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $socialNetworks = $this->getSocialNetworks($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getSocialNetworks($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getSocialNetworks($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'social-networks', 'website' => $website, 'range' => $range, 'export' => 'stats.export.social_networks', 'socialNetworks' => $socialNetworks, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Campaigns stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function campaigns(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'campaign']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $campaigns = $this->getCampaigns($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getCampaigns($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getCampaigns($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'campaigns', 'website' => $website, 'range' => $range, 'export' => 'stats.export.campaigns', 'campaigns' => $campaigns, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Continents stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function continents(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'continent']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $continents = $this->getContinents($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getContinents($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getContinents($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'continents', 'website' => $website, 'range' => $range, 'export' => 'stats.export.continents', 'continents' => $continents, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Countries stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countries(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'country']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $countriesChart = $this->getCountries($website, $range, $search, $searchBy, $sortBy, $sort)
            ->get();

        $countries = $this->getCountries($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getCountries($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getCountries($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'countries', 'website' => $website, 'range' => $range, 'export' => 'stats.export.countries', 'countries' => $countries, 'countriesChart' => $countriesChart, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Cities stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cities(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'city']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $cities = $this->getCities($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getCities($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getCities($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'cities', 'website' => $website, 'range' => $range, 'export' => 'stats.export.cities', 'cities' => $cities, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Languages stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function languages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'language']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $languages = $this->getLanguages($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getLanguages($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getLanguages($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'languages', 'website' => $website, 'range' => $range, 'export' => 'stats.export.languages', 'languages' => $languages, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Operating Systems stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function operatingSystems(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'os']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $operatingSystems = $this->getOperatingSystems($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getOperatingSystems($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getOperatingSystems($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'operating-systems', 'website' => $website, 'range' => $range, 'export' => 'stats.export.operating_systems', 'operatingSystems' => $operatingSystems, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Browsers stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function browsers(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'browser']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $browsers = $this->getBrowsers($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getBrowsers($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getBrowsers($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'browsers', 'website' => $website, 'range' => $range, 'export' => 'stats.export.browsers', 'browsers' => $browsers, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Screen Resolutions stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function screenResolutions(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'resolution']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $screenResolutions = $this->getScreenResolutions($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getScreenResolutions($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getScreenResolutions($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'screen-resolutions', 'website' => $website, 'range' => $range, 'export' => 'stats.export.screen_resolutions', 'screenResolutions' => $screenResolutions, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Devices stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function devices(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'device']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $devices = $this->getDevices($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getDevices($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getDevices($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'devices', 'website' => $website, 'range' => $range, 'export' => 'stats.export.devices', 'devices' => $devices, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Show the Events stats page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function events(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'event']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $events = $this->getEvents($website, $range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getEvents($website, $range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getEvents($website, $range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'events', 'website' => $website, 'range' => $range, 'export' => 'stats.export.events', 'events' => $events, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    /**
     * Export the Pages stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportPages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        return $this->exportCSV($request, $website, __('Pages'), $range, __('URL'), __('Pageviews'), $this->getPages($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Landing Pages stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportLandingPages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Landing pages'), $range, __('URL'), __('Visitors'), $this->getLandingPages($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }    /**
     * Export the Exit Pages stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportExitPages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Exit pages'), $range, __('URL'), __('Visitors'), $this->getExitPages($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }
    
    /**
     * Export the Revenue stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportRevenue(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['order_id', 'currency']) ? $request->input('search_by') : 'order_id';
        $sortBy = in_array($request->input('sort_by'), ['amount', 'date', 'currency']) ? $request->input('sort_by') : 'date';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';

        $revenue = $this->getRevenue($website, $range, $search, $searchBy, $sortBy, $sort)->get();
        
        $csv = CSV\Writer::createFromFileObject(new \SplTempFileObject);

        $csv->setDelimiter(',');
        $csv->setEnclosure('"');
        $csv->setEscape('\\');

        $csv->insertOne([__('Date'), __('Amount'), __('Currency'), __('Order ID')]);

        foreach ($revenue as $row) {
            $csv->insertOne([$row->date, $row->amount, $row->currency, $row->order_id]);
        }        return response($csv->getContent())
            ->withHeaders([
                'Content-Type' => 'text/csv',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Disposition' => 'attachment; filename="' . formatTitle([$website->domain, __('Revenue'), $range['from'], $range['to'], config('settings.title')]) . '.csv"',
            ]);
    }

    /**
     * Export the Referrers stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportReferrers(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Referrers'), $range, __('Website'), __('Visitors'), $this->getReferrers($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Search Engines stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportSearchEngines(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Search engines'), $range, __('Website'), __('Visitors'), $this->getSearchEngines($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Social Networks stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportSocialNetworks(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Social networks'), $range, __('Website'), __('Visitors'), $this->getSocialNetworks($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Campaigns stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportCampaigns(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Campaigns'), $range, __('Name'), __('Visitors'), $this->getCampaigns($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Continents stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportContinents(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Continents'), $range, __('Name'), __('Visitors'), $this->getContinents($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Countries stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportCountries(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');



        return $this->exportCSV($request, $website, __('Countries'), $range, __('Name'), __('Visitors'), $this->getCountries($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Cities stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportCities(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Cities'), $range, __('Name'), __('Visitors'), $this->getCities($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Languages stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportLanguages(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Languages'), $range, __('Name'), __('Visitors'), $this->getLanguages($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Operating Systems stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportOperatingSystems(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Operating systems'), $range, __('Name'), __('Visitors'), $this->getOperatingSystems($website, $range, $search, $searchBy, $sortBy, $sort)->get());
                   }

    /**
     * Export the Browsers stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportBrowsers(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Browsers'), $range, __('Name'), __('Visitors'), $this->getBrowsers($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Screen Resolutions stats
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportScreenResolutions(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Screen resolutions'), $range, __('Size'), __('Visitors'), $this->getScreenResolutions($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Devices stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportDevices(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Devices'), $range, __('Type'), __('Visitors'), $this->getDevices($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Export the Events stats.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|CSV\Writer
     * @throws CSV\CannotInsertRecord
     */
    public function exportEvents(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();

        if ($this->statsGuard($website)) {
            return view('stats.password', ['website' => $website]);
        };

        $range = $this->range();
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';


        return $this->exportCSV($request, $website, __('Events'), $range, __('Name'), __('Completions'), $this->getEvents($website, $range, $search, $searchBy, $sortBy, $sort)->get());
    }

    /**
     * Get the Pages.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getPages($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'page']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Landing Pages.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getLandingPages($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'landing_page']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }    /**
     * Get the Exit Pages.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getExitPages($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'exit_page']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }
    
    /**
     * Get the Revenue data.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $searchBy
     * @param null $sortBy
     * @param null $sort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getRevenue($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        $query = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']]);
            
        if ($search) {
            if ($searchBy == 'order_id') {
                $query->where('order_id', 'like', '%' . $search . '%');
            } elseif ($searchBy == 'currency') {
                $query->where('currency', 'like', '%' . $search . '%');
            }
        }
        
        if ($sortBy && $sort) {
            $query->orderBy($sortBy, $sort);
        }
            
        return $query;
    }
      /**
     * Get summary statistics for revenue
     *
     * @param $website
     * @param $range
     * @return array
     */
    private function getRevenueSummary($website, $range)
    {
        // Get all revenue data in range
        $revenueData = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->get();
            
        // Calculate average order value
        $totalOrders = $revenueData->count();
        $totalAmount = $revenueData->sum('amount');
        $averageOrderValue = $totalOrders > 0 ? $totalAmount / $totalOrders : 0;
        
        // Get revenue by source
        $revenueBySource = \App\Models\Revenue::selectRaw('source, SUM(amount) as total')
            ->where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('source')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->source => $item->total];
            })
            ->toArray();
        
        // Get max daily revenue
        $maxDailyRevenue = \App\Models\Revenue::selectRaw('date, SUM(amount) as total')
            ->where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('date')
            ->orderBy('total', 'desc')
            ->first();
            
        // Calculate per day average
        $days = Carbon::createFromFormat('Y-m-d', $range['from'])->diffInDays(Carbon::createFromFormat('Y-m-d', $range['to'])) + 1;
        $averagePerDay = $days > 0 ? $totalAmount / $days : 0;
        
        // Get all-time revenue for a broader perspective
        $allTimeRevenue = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->sum('amount');
            
        // Get month-to-date revenue
        $currentMonth = Carbon::now()->format('Y-m');
        $monthToDateRevenue = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->sum('amount');
            
        // Calculate growth rate by comparing to previous period
        $previousFrom = Carbon::createFromFormat('Y-m-d', $range['from'])->subDays($days);
        $previousTo = Carbon::createFromFormat('Y-m-d', $range['from'])->subDay();
        
        $previousPeriodRevenue = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$previousFrom->format('Y-m-d'), $previousTo->format('Y-m-d')])
            ->sum('amount');
            
        $growthRate = $previousPeriodRevenue > 0 
            ? (($totalAmount - $previousPeriodRevenue) / $previousPeriodRevenue) * 100 
            : ($totalAmount > 0 ? 100 : 0);
            
        return [
            'totalOrders' => $totalOrders,
            'totalAmount' => $totalAmount,
            'averageOrderValue' => $averageOrderValue,
            'revenueBySource' => $revenueBySource,
            'maxDailyRevenue' => $maxDailyRevenue ? [
                'date' => $maxDailyRevenue->date,
                'amount' => $maxDailyRevenue->total
            ] : null,
            'averagePerDay' => $averagePerDay,
            'primaryCurrency' => $revenueData->isNotEmpty() ? $revenueData->first()->currency : '',
            'allTimeRevenue' => $allTimeRevenue,
            'monthToDateRevenue' => $monthToDateRevenue,
            'previousPeriodRevenue' => $previousPeriodRevenue,
            'growthRate' => $growthRate        ];
    }

    /**
     * Get the Revenue data grouped by day.
     *
     * @param $website
     * @param $range
     * @return array
     */
    private function getRevenueByDay($website, $range)
    {
        $data = \App\Models\Revenue::selectRaw('date, SUM(amount) as total')
            ->where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->total;
            })
            ->toArray();
            
        $revenueMap = [];
        
        $startDate = Carbon::createFromFormat('Y-m-d', $range['from']);
        $endDate = Carbon::createFromFormat('Y-m-d', $range['to']);
        
        while($startDate->lte($endDate)) {
            $date = $startDate->format('Y-m-d');
            
            $revenueMap[$date] = isset($data[$date]) ? (float)$data[$date] : 0;
            
            $startDate->addDay();
        }
        
        return $revenueMap;
    }

    /**
     * Get the Referrers.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getReferrers($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'referrer'], ['value', '<>', $website->domain], ['value', '<>', '']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Search Engines.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getSearchEngines($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        $websites = $this->getSearchEnginesList();

        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'referrer']])
            ->whereIn('value', $websites)
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Social Networks.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getSocialNetworks($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        $websites = $this->getSocialNetworksList();

        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'referrer']])
            ->whereIn('value', $websites)
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Campaigns.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getCampaigns($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'campaign']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Continents.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getContinents($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'continent']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Countries.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getCountries($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'country']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Cities.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getCities($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'city']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Languages.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getLanguages($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'language']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Operating Systems.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getOperatingSystems($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'os']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Browsers.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getBrowsers($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'browser']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Screen Resolutions.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getScreenResolutions($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'resolution']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Devices.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getDevices($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'device']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get the Events.
     *
     * @param $website
     * @param $range
     * @param null $search
     * @param null $sort
     * @return mixed
     */
    private function getEvents($website, $range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', 'event']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    /**
     * Get aggregated revenue by country
     *
     * @param $website
     * @param $range
     * @return array
     */
    private function getRevenueByCountry($website, $range)
    {
        // Get all revenues in range
        $revenueEvents = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->get();
            
        // Get countries stats
        $countries = $this->getCountries($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();
            
        // Prepare result data structure
        $result = [];
        
        foreach ($countries as $country) {
            $countryCode = explode(':', $country->value)[0] ?? '';
            
            // Sum revenue for this country
            $countryRevenue = $revenueEvents->where('country_code', $countryCode)->sum('amount');
            
            $result[] = [
                'value' => $country->value,
                'visits' => $country->count, 
                'revenue' => $countryRevenue,
                'revenuePerVisitor' => $country->count > 0 ? $countryRevenue / $country->count : 0
            ];
        }
        
        return $result;
    }
    
    /**
     * Get aggregated revenue by browser
     *
     * @param $website
     * @param $range
     * @return array
     */
    private function getRevenueByBrowser($website, $range)
    {
        // Get all revenues in range
        $revenueEvents = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->get();
            
        // Get browser stats
        $browsers = $this->getBrowsers($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();
            
        // Prepare result data structure
        $result = [];
        
        foreach ($browsers as $browser) {
            // Sum revenue for this browser
            $browserRevenue = $revenueEvents->where('browser', $browser->value)->sum('amount');
            
            $result[] = [
                'value' => $browser->value,
                'visits' => $browser->count, 
                'revenue' => $browserRevenue,
                'revenuePerVisitor' => $browser->count > 0 ? $browserRevenue / $browser->count : 0
            ];
        }
        
        return $result;
    }
    
    /**
     * Get aggregated revenue by operating system
     *
     * @param $website
     * @param $range
     * @return array
     */
    private function getRevenueByOperatingSystem($website, $range)
    {
        // Get all revenues in range
        $revenueEvents = \App\Models\Revenue::where('website_id', '=', $website->id)
            ->whereBetween('date', [$range['from'], $range['to']])
            ->get();
            
        // Get OS stats
        $operatingSystems = $this->getOperatingSystems($website, $range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();
            
        // Prepare result data structure
        $result = [];
        
        foreach ($operatingSystems as $os) {
            // Sum revenue for this OS
            $osRevenue = $revenueEvents->where('os', $os->value)->sum('amount');
            
            $result[] = [
                'value' => $os->value,
                'visits' => $os->count, 
                'revenue' => $osRevenue,
                'revenuePerVisitor' => $os->count > 0 ? $osRevenue / $os->count : 0
            ];
        }
        
        return $result;
    }

    /**
     * Validate the link's password.
     *
     * @param ValidateWebsitePasswordRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validatePassword(ValidateWebsitePasswordRequest $request, $id)
    {
        session([md5($id) => true]);
        return redirect()->back();
    }

    /**
     * Guard the stats pages.
     *
     * @param $website
     * @return bool
     */
    private function statsGuard($website)
    {
        // If the link stats is not set to public
        if($website->privacy !== 0) {
            $user = Auth::user();

            // If the website's privacy is set to private
            if ($website->privacy == 1) {
                // If the user is not authenticated
                // Or if the user is not the owner of the link and not an admin
                if ($user == null || $user->id != $website->user_id && $user->role != 1) {
                    abort(403);
                }
            }

            // If the website's privacy is set to password
            if ($website->privacy == 2) {
                // If there's no passowrd validation in the current session
                if (!session(md5($website->domain))) {
                    // If the user is not authenticated
                    // Or if the user is not the owner of the link and not an admin
                    if ($user == null || $user->id != $website->user_id && $user->role != 1) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Get traffic statistics (visitors or pageviews) by day
     *
     * @param $website
     * @param $range
     * @param $type
     * @return array
     */
    private function getTraffic($website, $range, $type)
    {
        // Validate the traffic type
        if (!in_array($type, ['visitors', 'pageviews'])) {
            $type = 'visitors';
        }

        $data = Stat::selectRaw('`date`, SUM(`count`) as `count`')
            ->where([['website_id', '=', $website->id], ['name', '=', $type]])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->count;
            })
            ->toArray();

        $trafficMap = [];
        
        // If the unit is 'hour', format needs to be different
        if ($range['unit'] == 'hour') {
            // For hourly data, we need to create a map with all hours
            $startDate = Carbon::createFromFormat('Y-m-d', $range['from'])->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $range['to'])->endOfDay();
            
            while ($startDate->lte($endDate)) {
                $hour = $startDate->format('H');
                $date = $startDate->format('Y-m-d');
                
                $key = $hour;
                $trafficMap[$key] = isset($data[$date]) ? (int)$data[$date] : 0;
                
                $startDate->addHour();
            }
        } else {
            // For daily, monthly, or yearly data
            $format = $range['format'] ?: 'Y-m-d';
            
            // Initialize all dates in the range with 0 values
            $trafficMap = $this->calcAllDates($range['from'], $range['to'], $range['unit'], $format, 0);
            
            // Fill in the actual data
            foreach ($data as $date => $count) {
                $key = Carbon::createFromFormat('Y-m-d', $date)->format($format);
                $trafficMap[$key] = (int)$count;
            }
        }
        
        return $trafficMap;
    }
}
