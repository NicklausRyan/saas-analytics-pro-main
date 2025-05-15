<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class RevenueController extends Controller
{
    /**
     * Track revenue from client-side.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function track(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'domain' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'orderId' => 'nullable|string',
            'visitorId' => 'nullable|string',
        ]);
        
        try {
            // Find the website
            $website = Website::where('domain', $validated['domain'])->firstOrFail();
            
            // Create revenue record
            Revenue::create([
                'website_id' => $website->id,
                'amount' => $validated['amount'],
                'currency' => strtoupper($validated['currency']),
                'order_id' => $validated['orderId'] ?? null,
                'visitor_id' => $validated['visitorId'] ?? null,
                'source' => 'manual',
                'date' => Carbon::now()->format('Y-m-d')
            ]);
            
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Revenue tracking error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to track revenue'], 500);
        }
    }
}
