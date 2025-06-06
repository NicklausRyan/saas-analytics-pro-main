/**
 * Account Tabs JS
 * Enhanced version that handles tab interactions and ensures proper sizing of panels
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get all tab elements
    const tabLinks = document.querySelectorAll('#account-tabs .nav-link');
    const tabContents = document.querySelectorAll('#account-tabs-content .tab-pane');
      // Fix tab panel heights and ensure proper sizing
    function adjustPanelSizes() {
        // Reset display for measuring
        tabContents.forEach(pane => {
            if (pane.classList.contains('active')) {
                pane.style.display = 'block';
            } else {
                pane.style.display = 'none';
            }
        });

        // Ensure the content panels don't stretch too wide
        const tabContent = document.getElementById('account-tabs-content');
        if (tabContent) {
            tabContent.style.width = '100%';
            tabContent.style.position = 'relative';
            tabContent.style.display = 'block';
        }
        
        // Apply proper form layout inside each tab
        document.querySelectorAll('.tab-pane .form-group').forEach(formGroup => {
            formGroup.style.width = '100%';
        });
        
        // Apply the proper height to the tab navigation container
        const sideNav = document.querySelector('.account-container .side-nav');
        const contentHeight = document.querySelector('.account-container .tab-content').offsetHeight;
        
        if (sideNav && contentHeight && window.innerWidth >= 992) {
            // Only set a minimum height, don't restrict if navigation is taller
            sideNav.style.minHeight = (contentHeight * 0.9) + 'px';
        }
        
        // Ensure proper positioning of all tabs
        if (window.innerWidth >= 992) {
            // On desktop, all tab content is properly aligned next to sidebar
            document.querySelectorAll('#account-tabs-content .tab-pane').forEach(pane => {
                pane.style.position = 'relative';
                pane.style.width = '100%';
                pane.style.top = '0';
                pane.style.left = '0';
            });
        }
    }
    
    // Handle tab clicks with improved animation
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add active class immediately for better UX
            tabLinks.forEach(l => l.classList.remove('active-animating'));
            this.classList.add('active-animating');
            
            // Allow a tiny delay for Bootstrap to do its thing
            setTimeout(adjustPanelSizes, 50);
            
            // Add another adjustment after all animations are likely complete
            setTimeout(adjustPanelSizes, 300);
        });
    });
    
    // Initial adjustment
    adjustPanelSizes();
    
    // Secondary adjustment after everything has loaded
    setTimeout(adjustPanelSizes, 500);
    
    // Adjust on resize too, with debounce for performance
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(adjustPanelSizes, 100);
    });
});