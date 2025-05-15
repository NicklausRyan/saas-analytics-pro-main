/**
 * SaaS Analytics Pro - Revenue Tracking Script
 * Include this script after including the main tracking script.
 */

(function() {
    // Define the tracker object if it doesn't exist
    if (typeof _analytics === 'undefined') {
        console.error('SaaS Analytics Pro tracking script is not loaded. Please include the main tracking script first.');
        return;
    }

    // Add revenue tracking functionality to the analytics object
    _analytics.trackRevenue = function(options) {
        if (!options || typeof options !== 'object') {
            console.error('Revenue tracking requires an options object');
            return;
        }

        // Required fields
        if (!options.amount || isNaN(parseFloat(options.amount))) {
            console.error('Revenue tracking requires a valid amount');
            return;
        }

        if (!options.currency || typeof options.currency !== 'string' || options.currency.length !== 3) {
            console.error('Revenue tracking requires a valid currency code (3 letters)');
            return;
        }

        // Prepare data
        const data = {
            domain: _analytics.domain,
            amount: parseFloat(options.amount),
            currency: options.currency.toUpperCase(),
            orderId: options.orderId || null,
            visitorId: _analytics.visitorId || null
        };        // Send the data
        const xhr = new XMLHttpRequest();
        // Get the base URL from the tracker URL (replacing 'event' with 'track-revenue')
        const trackerUrl = _analytics.trackerUrl.replace('/event', '/track-revenue');
        xhr.open('POST', trackerUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(data));

        return true;
    };

    // Add Stripe integration
    _analytics.setupStripe = function(stripe) {
        if (!stripe || typeof stripe !== 'object') {
            console.error('Invalid Stripe object');
            return;
        }

        // Save the original confirmCardPayment method
        const originalConfirmCardPayment = stripe.confirmCardPayment;

        // Override the confirmCardPayment method
        stripe.confirmCardPayment = function(clientSecret, data) {
            // Add visitor ID to the payment metadata
            if (data && data.payment_method_data && !data.payment_method_data.metadata) {
                data.payment_method_data.metadata = {};
            }
            
            if (data && data.payment_method_data && data.payment_method_data.metadata) {
                data.payment_method_data.metadata.visitor_id = _analytics.visitorId;
            }

            // Call the original method
            return originalConfirmCardPayment.call(this, clientSecret, data);
        };

        return true;
    };
})();
