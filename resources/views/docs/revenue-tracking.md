# Stripe Revenue Tracking Integration Guide

SaaS Analytics Pro now includes revenue tracking capabilities that integrate with Stripe. This guide will walk you through setting up revenue tracking for your website.

## Prerequisites

1. A Stripe account (sign up at [stripe.com](https://stripe.com) if you don't have one)
2. SaaS Analytics Pro installed and tracking your website traffic
3. A basic understanding of JavaScript and PHP

## Step 1: Set Up Your Stripe API Key

1. Log into your Stripe Dashboard
2. Go to "Developers" > "API keys"
3. Copy your "Secret key" (starts with `sk_`)
4. Log into your SaaS Analytics Pro dashboard
5. Go to your website settings
6. Paste your Stripe API Key into the "Stripe API Key" field
7. Save your changes

## Step 2: Set Up Stripe Webhook

1. In your Stripe Dashboard, go to "Developers" > "Webhooks"
2. Click "Add endpoint"
3. For the endpoint URL, enter:
   ```
   https://your-domain.com/api/stripe-webhook/your-website-domain
   ```
   Replace `your-domain.com` with your SaaS Analytics Pro installation domain, and `your-website-domain` with the domain of your website in SaaS Analytics Pro.
4. For events to send, select:
   - `checkout.session.completed`
   - `payment_intent.succeeded`
   - `invoice.paid`
5. Click "Add endpoint"
6. Copy the "Signing secret"
7. Add the signing secret to your SaaS Analytics Pro installation's `.env` file:
   ```
   STRIPE_WEBHOOK_SECRET=whsec_...
   ```

## Step 3: Include the Revenue Tracking Script

On your website, include the revenue tracking script after the main SaaS Analytics Pro tracking script:

```html
<!-- SaaS Analytics Pro main tracker -->
<script src="https://your-domain.com/js/analytics.js" data-domain="your-website.com"></script>

<!-- SaaS Analytics Pro revenue tracker -->
<script src="https://your-domain.com/js/revenue-tracker.js"></script>
```

## Step 4: Track Revenue Manually (Optional)

If you need to manually track revenue (outside of Stripe), you can use the JavaScript API:

```javascript
// Track a purchase
_analytics.trackRevenue({
    amount: 49.99,        // Required: Amount in your currency
    currency: 'USD',      // Required: 3-letter currency code
    orderId: 'ORDER123'   // Optional: Order identifier
});
```

## Step 5: Integrate with Stripe.js (Optional)

If you're using Stripe.js for payments, you can add visitor tracking automatically:

```javascript
// Initialize Stripe
const stripe = Stripe('pk_test_your_public_key');

// Set up SaaS Analytics Pro integration
_analytics.setupStripe(stripe);

// Now use Stripe as normal - visitor ID will be added automatically
stripe.confirmCardPayment('client_secret', {
    payment_method: {
        card: cardElement,
        billing_details: {
            name: 'Jenny Rosen'
        }
    }
});
```

## Viewing Revenue Data

To view your revenue data:

1. Log into your SaaS Analytics Pro dashboard
2. Select your website
3. Click on "Revenue" in the navigation menu
4. Use the date range selector to view revenue for specific periods

## Understanding the Revenue Dashboard

The Revenue Dashboard provides comprehensive analytics about your sales performance:

### Revenue Overview

- **Total Revenue**: Shows total revenue for the selected period with growth rate compared to previous period
- **All Time Revenue**: Displays total revenue since tracking began
- **Month to Date Revenue**: Shows revenue for the current month
- **Average Order Value**: Displays the average value per transaction

### Revenue Analytics

- **Revenue Comparison**: Visual comparison between current and previous periods with growth rate
- **Revenue Sources**: Breakdown of revenue by source with percentage contribution
- **Key Metrics**: Quick overview of order count, average order value, and daily average
- **Best Performing Day**: Highlights your highest revenue day within the selected period
- **All-time Performance**: Shows your lifetime revenue totals and month-to-date figures

### Revenue Trend

The Revenue Trend chart visualizes your daily revenue over the selected period, allowing you to identify patterns and trends in your sales data.

## Troubleshooting

- **No revenue data showing up?** Make sure your Stripe API key is correct and the webhook is properly configured.
- **Webhook errors?** Check your server logs for details on what might be failing.

## Need Help?

If you encounter any issues, please contact support at support@yourdomain.com.
