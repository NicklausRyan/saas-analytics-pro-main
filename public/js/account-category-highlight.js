// This script adds category highlighting to the account page sidebar
document.addEventListener('DOMContentLoaded', function() {
    // Find all category items
    const navItems = document.querySelectorAll('.side-nav .nav-item[data-category]');
    
    // Set initial active category based on the currently active link
    const activeLink = document.querySelector('.side-nav .nav-link.active');
    if (activeLink) {
        const category = activeLink.closest('.nav-item[data-category]');
        if (category) {
            category.classList.add('active');
        }
    }
    
    // Add click event listeners to all nav links
    document.querySelectorAll('.side-nav .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            // Remove active class from all categories
            navItems.forEach(item => item.classList.remove('active'));
            
            // Add active class to parent category
            const category = this.closest('.nav-item[data-category]');
            if (category) {
                category.classList.add('active');
            }
        });
    });
});
