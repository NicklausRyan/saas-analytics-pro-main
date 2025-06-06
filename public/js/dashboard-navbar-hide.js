// Hide the navbar only on the dashboard page
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the dashboard page
    if (window.location.pathname === '/dashboard' || 
        window.location.pathname === '/' || 
        window.location.href.includes('/dashboard')) {
        
        // Find the main navbar element and hide it
        const mainNavbar = document.getElementById('main-navbar');
        if (mainNavbar) {
            mainNavbar.style.display = 'none';
        }
    }
});
