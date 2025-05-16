<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Analytics Pro - Revenue Tracker Test</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        .container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            color: #007bff;
            margin-top: 30px;
        }
        .button-group {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .results {
            background-color: #2d2d2d;
            color: #fff;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 20px;
        }
        .instructions {
            background-color: #e9f5ff;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }
        .note {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SaaS Analytics Pro - Revenue Tracker Test</h1>
        
        <div class="instructions">
            <p><strong>Instructions:</strong> This page allows you to test the revenue tracking functionality of SaaS Analytics Pro. Make sure to:</p>
            <ol>
                <li>Update the domain in the analytics.js script tag below to match your SaaS Analytics Pro installation</li>
                <li>Open the browser console (F12) to see detailed test results</li>
                <li>Use the buttons below to run different test scenarios</li>
            </ol>
        </div>

        <div class="note">
            <p><strong>Note:</strong> These test events will be sent to your actual analytics dashboard. Check the revenue page to verify tracking is working correctly.</p>
        </div>
        
        <h2>Test Revenue Tracking</h2>
        <div class="button-group">
            <button id="basicTest">Test Basic Tracking</button>
            <button id="advancedTest">Test Advanced Tracking</button>
            <button id="currenciesTest">Test Multiple Currencies</button>
            <button id="errorTest">Test Error Handling</button>
            <button id="runAllTests">Run All Tests</button>
        </div>
        
        <h2>Test Data</h2>
        <table>
            <tr>
                <th>Test</th>
                <th>Description</th>
                <th>Expected Result</th>
            </tr>
            <tr>
                <td>Basic Tracking</td>
                <td>Sends a simple revenue tracking event ($49.99 USD)</td>
                <td>Event appears in dashboard</td>
            </tr>
            <tr>
                <td>Advanced Tracking</td>
                <td>Sends tracking with metadata ($149.99 USD)</td>
                <td>Event appears with source "test-suite"</td>
            </tr>
            <tr>
                <td>Multiple Currencies</td>
                <td>Sends events in USD, EUR, GBP, JPY, and CAD</td>
                <td>All events appear with different currencies</td>
            </tr>
            <tr>
                <td>Error Handling</td>
                <td>Tests validation of required parameters</td>
                <td>Errors logged to console, no events sent</td>
            </tr>
        </table>
        
        <div class="results" id="results">
            Console output will appear here...
        </div>
    </div>

    <!-- Update the domain in this script tag to match your installation -->
    <script src="/js/analytics.js" data-domain="example.com"></script>
    <script src="/js/revenue-tracker.js"></script>
    <script src="/js/revenue-tracker-test.js"></script>

    <script>
        // Helper function to display console outputs in the results div
        (function() {
            const resultsDiv = document.getElementById('results');
            const originalConsole = {
                log: console.log,
                error: console.error,
                warn: console.warn,
                info: console.info,
                group: console.group,
                groupEnd: console.groupEnd
            };

            function appendToResults(method, args) {
                const message = Array.from(args).map(arg => {
                    if (typeof arg === 'object') {
                        return JSON.stringify(arg, null, 2);
                    }
                    return arg;
                }).join(' ');
                
                const line = document.createElement('div');
                
                if (method === 'error') {
                    line.style.color = '#ff6b6b';
                } else if (method === 'warn') {
                    line.style.color = '#ffa94d';
                } else if (method === 'group') {
                    line.style.fontWeight = 'bold';
                    line.style.marginTop = '10px';
                } else if (method === 'groupEnd') {
                    line.style.borderBottom = '1px dashed #666';
                    line.style.marginBottom = '10px';
                }
                
                line.textContent = method === 'groupEnd' ? '----------------------' : message;
                resultsDiv.appendChild(line);
                resultsDiv.scrollTop = resultsDiv.scrollHeight;
            }

            // Override console methods
            console.log = function() {
                appendToResults('log', arguments);
                originalConsole.log.apply(console, arguments);
            };
            
            console.error = function() {
                appendToResults('error', arguments);
                originalConsole.error.apply(console, arguments);
            };
            
            console.warn = function() {
                appendToResults('warn', arguments);
                originalConsole.warn.apply(console, arguments);
            };
            
            console.info = function() {
                appendToResults('info', arguments);
                originalConsole.info.apply(console, arguments);
            };
            
            console.group = function() {
                appendToResults('group', arguments);
                originalConsole.group.apply(console, arguments);
            };
            
            console.groupEnd = function() {
                appendToResults('groupEnd', arguments);
                originalConsole.groupEnd.apply(console, arguments);
            };
        })();

        // Set up event listeners for buttons
        document.getElementById('basicTest').addEventListener('click', function() {
            RevenueTrackerTest.testBasicTracking();
        });
        
        document.getElementById('advancedTest').addEventListener('click', function() {
            RevenueTrackerTest.testAdvancedTracking();
        });
        
        document.getElementById('currenciesTest').addEventListener('click', function() {
            RevenueTrackerTest.testMultipleCurrencies();
        });
        
        document.getElementById('errorTest').addEventListener('click', function() {
            RevenueTrackerTest.testErrorHandling();
        });
        
        document.getElementById('runAllTests').addEventListener('click', function() {
            RevenueTrackerTest.runAll();
        });
    </script>
</body>
</html>
