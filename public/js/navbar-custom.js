/**
 * Custom JavaScript for the responsive navbar
 */
document.addEventListener('DOMContentLoaded', function() {
    // Ensure both desktop and mobile dropdowns work correctly
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                const dropdownMenu = this.nextElementSibling;
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                } else {
                    dropdownMenu.classList.add('show');
                }
            }
        });
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(function(dropdown) {
            if (!dropdown.parentElement.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    });

    // Handle sidebar toggle on mobile
    const sidebarToggle = document.querySelector('.slide-menu-toggle');
    const sideMenu = document.getElementById('slide-menu');
    const closeButton = document.querySelector('.slide-menu-toggle.close');
    
    if (sidebarToggle && sideMenu && closeButton) {
        sidebarToggle.addEventListener('click', function() {
            sideMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        closeButton.addEventListener('click', function() {
            sideMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
});
