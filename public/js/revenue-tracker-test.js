/**
 * SaaS Analytics Pro - Revenue Tracker Test Suite
 * 
 * This file contains functions to test the revenue tracking functionality.
 * To use, include this script after revenue-tracker.js and call the test functions as needed.
 */

(function() {
    // Store reference to the analytics tracker
    const _analytics = window._analytics || {};

    // Test suite container
    const RevenueTrackerTest = {
        /**
         * Test basic revenue tracking with minimal parameters
         */
        testBasicTracking: function() {
            console.group('Basic Revenue Tracking Test');
            console.log('Sending basic revenue tracking event...');
            
            try {
                _analytics.trackRevenue({
                    amount: 49.99,
                    currency: 'USD',
                    orderId: 'TEST-' + Math.floor(Math.random() * 10000)
                });
                
                console.log('✅ Basic revenue tracking event sent successfully');
            } catch (err) {
                console.error('❌ Failed to send basic revenue tracking event:', err);
            }
            
            console.groupEnd();
        },
        
        /**
         * Test revenue tracking with additional parameters
         */
        testAdvancedTracking: function() {
            console.group('Advanced Revenue Tracking Test');
            console.log('Sending advanced revenue tracking event...');
            
            try {
                _analytics.trackRevenue({
                    amount: 149.99,
                    currency: 'USD',
                    orderId: 'TEST-ADV-' + Math.floor(Math.random() * 10000),
                    source: 'test-suite',
                    meta: {
                        productId: 'PRO-PLAN',
                        customerType: 'new',
                        test: true
                    }
                });
                
                console.log('✅ Advanced revenue tracking event sent successfully');
            } catch (err) {
                console.error('❌ Failed to send advanced revenue tracking event:', err);
            }
            
            console.groupEnd();
        },
        
        /**
         * Test multiple currencies
         */
        testMultipleCurrencies: function() {
            const currencies = ['USD', 'EUR', 'GBP', 'JPY', 'CAD'];
            
            console.group('Multiple Currencies Test');
            
            currencies.forEach((currency, i) => {
                const amount = 25 + (i * 10);
                console.log(`Sending revenue tracking event with ${currency}...`);
                
                try {
                    _analytics.trackRevenue({
                        amount: amount,
                        currency: currency,
                        orderId: `TEST-${currency}-${Math.floor(Math.random() * 10000)}`,
                        source: 'currency-test'
                    });
                    
                    console.log(`✅ Successfully sent ${currency} tracking event`);
                } catch (err) {
                    console.error(`❌ Failed to send ${currency} tracking event:`, err);
                }
            });
            
            console.groupEnd();
        },
        
        /**
         * Test error handling
         */
        testErrorHandling: function() {
            console.group('Error Handling Test');
            
            // Test missing required parameters
            console.log('Testing missing amount parameter...');
            try {
                _analytics.trackRevenue({
                    currency: 'USD',
                    orderId: 'TEST-ERROR-1'
                });
                console.error('❌ Failed: Should have thrown error for missing amount');
            } catch (err) {
                console.log('✅ Correctly threw error for missing amount:', err.message);
            }
            
            console.log('Testing missing currency parameter...');
            try {
                _analytics.trackRevenue({
                    amount: 49.99,
                    orderId: 'TEST-ERROR-2'
                });
                console.error('❌ Failed: Should have thrown error for missing currency');
            } catch (err) {
                console.log('✅ Correctly threw error for missing currency:', err.message);
            }
            
            console.groupEnd();
        },
        
        /**
         * Run all tests
         */
        runAll: function() {
            console.group('SaaS Analytics Pro Revenue Tracker Tests');
            console.log('Starting test suite...');
            
            this.testBasicTracking();
            this.testAdvancedTracking();
            this.testMultipleCurrencies();
            this.testErrorHandling();
            
            console.log('All tests completed');
            console.groupEnd();
        }
    };
    
    // Add test suite to window
    window.RevenueTrackerTest = RevenueTrackerTest;
    
    console.log('SaaS Analytics Pro Revenue Tracker Test Suite loaded. Use RevenueTrackerTest.runAll() to run all tests.');
})();
