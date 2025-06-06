document.addEventListener('DOMContentLoaded', function() {
    // Add mobile toggle functionality for admin sidebar
    const adminSidebar = document.getElementById('admin-sidebar-nav');
    const adminSidebarToggle = document.getElementById('admin-sidebar-toggle');
    
    if (adminSidebarToggle && adminSidebar) {
        adminSidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('show');
        });
    }
    
    // Add smooth transition for sub-menu items
    const collapseTriggers = document.querySelectorAll('[data-toggle="collapse"]');
    if (collapseTriggers) {
        collapseTriggers.forEach(function(trigger) {
            trigger.addEventListener('click', function() {
                // Toggle the expand icon rotation
                const expandIcon = this.querySelector('.sidebar-expand');
                if (expandIcon) {
                    expandIcon.style.transform = this.getAttribute('aria-expanded') === 'true' ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
        });
    }
    
    // Highlight active menu item
    const currentUrl = window.location.href;
    const sidebarLinks = document.querySelectorAll('.admin-sidebar .nav-link');
    
    sidebarLinks.forEach(function(link) {
        const href = link.getAttribute('href');
        if (href && currentUrl.includes(href) && href !== '#') {
            link.classList.add('active');
            
            // If this is in a submenu, expand the parent menu
            const parentMenu = link.closest('.sub-menu');
            if (parentMenu) {
                const parentId = parentMenu.id;
                const parentTrigger = document.querySelector(`[href="#${parentId}"]`);
                
                if (parentTrigger) {
                    parentTrigger.setAttribute('aria-expanded', 'true');
                    parentMenu.classList.add('show');
                }
            }
        }
    });
});
