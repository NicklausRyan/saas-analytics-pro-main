<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\StripeClient;

class StripeWebhookController extends Controller
{
    /**
     * Handle the Stripe webhook request.
     *
     * @param Request $request
     * @param string $id Domain of the website
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request, $id)
    {
        $website = Website::where('domain', $id)->firstOrFail();
        
        // Check if website has a Stripe API key
        if (!$website->stripe_api_key) {
            return response()->json(['error' => 'No Stripe API key configured for this website'], 400);
        }
        
        $stripeClient = new StripeClient($website->stripe_api_key);
        
        // Get the webhook signature
        $signature = $request->header('Stripe-Signature');
        
        try {
            // Get the event by verifying the signature using the raw body and secret
            $event = $stripeClient->webhooks->constructEvent(
                $request->getContent(),
                $signature,
                env('STRIPE_WEBHOOK_SECRET', '')  // You should set this in your .env file
            );
            
            // Handle the event
            switch ($event->type) {
                case 'invoice.paid':
                case 'checkout.session.completed':
                case 'payment_intent.succeeded':
                    return $this->handleSuccessfulPayment($event->data->object, $website);
                default:
                    // Unexpected event type
                    return response()->json(['status' => 'Unhandled event type'], 200);
            }
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            // Other exceptions
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle successful payment events.
     *
     * @param object $paymentData
     * @param Website $website
     * @return \Illuminate\Http\Response
     */
    private function handleSuccessfulPayment($paymentData, Website $website)
    {
        // For checkout.session.completed or payment_intent.succeeded
        if (isset($paymentData->amount)) {
            $amount = $paymentData->amount / 100; // Convert from cents to dollars/euros
            $currency = $paymentData->currency;
            $orderId = $paymentData->id;
            
            // Try to get visitor_id from metadata
            $visitorId = null;
            if (isset($paymentData->metadata) && isset($paymentData->metadata->visitor_id)) {
                $visitorId = $paymentData->metadata->visitor_id;
            } elseif (isset($paymentData->client_reference_id)) {
                $visitorId = $paymentData->client_reference_id;
            }
            
            // Create the revenue record
            Revenue::create([
                'website_id' => $website->id,
                'amount' => $amount,
                'currency' => $currency,
                'order_id' => $orderId,
                'visitor_id' => $visitorId,
                'source' => 'stripe',
                'date' => Carbon::now()->format('Y-m-d')
            ]);
            
            return response()->json(['status' => 'Payment recorded successfully'], 200);
        }
        
        // For invoice.paid
        if (isset($paymentData->total) && $paymentData->status === 'paid') {
            $amount = $paymentData->total / 100; // Convert from cents to dollars/euros
            $currency = $paymentData->currency;
            $orderId = $paymentData->id;
            
            // Try to get visitor_id from metadata
            $visitorId = null;
            if (isset($paymentData->metadata) && isset($paymentData->metadata->visitor_id)) {
                $visitorId = $paymentData->metadata->visitor_id;
            } elseif (isset($paymentData->customer)) {
                $visitorId = $paymentData->customer;
            }
            
            // Create the revenue record
            Revenue::create([
                'website_id' => $website->id,
                'amount' => $amount,
                'currency' => $currency,
                'order_id' => $orderId,
                'visitor_id' => $visitorId,
                'source' => 'stripe',
                'date' => Carbon::now()->format('Y-m-d')
            ]);
            
            return response()->json(['status' => 'Invoice payment recorded successfully'], 200);
        }
        
        return response()->json(['status' => 'No payment amount found in the event data'], 400);
    }
}
