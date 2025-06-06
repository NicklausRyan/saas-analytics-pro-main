/* Account Page - No Animations JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    // Disable all jQuery animations globally for account pages
    if (typeof jQuery !== 'undefined') {
        jQuery.fx.off = true;
    }

    // Disable CSS transitions temporarily when elements are added/removed
    function disableTransitions() {
        const style = document.createElement('style');
        style.innerHTML = `
            *, *::before, *::after {
                transition: none !important;
                animation: none !important;
            }
        `;
        style.id = 'disable-transitions';
        document.head.appendChild(style);
        
        // Remove after a short delay to allow DOM changes
        setTimeout(() => {
            const disableStyle = document.getElementById('disable-transitions');
            if (disableStyle) {
                disableStyle.remove();
            }
        }, 50);
    }

    // Handle any dynamic content loading without animations
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                disableTransitions();
            }
        });
    });

    // Start observing the document with the configured parameters
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Handle form submissions without animations
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            disableTransitions();
        });
    });

    // Handle any Bootstrap components that might have animations
    if (typeof bootstrap !== 'undefined') {
        // Disable Bootstrap animations
        const originalShow = bootstrap.Modal.prototype.show;
        const originalHide = bootstrap.Modal.prototype.hide;
        
        bootstrap.Modal.prototype.show = function() {
            this._config.animation = false;
            return originalShow.apply(this, arguments);
        };
        
        bootstrap.Modal.prototype.hide = function() {
            this._config.animation = false;
            return originalHide.apply(this, arguments);
        };
    }

    // Remove any existing hover effects on cards
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'none';
            this.style.transition = 'none';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'none';
            this.style.transition = 'none';
        });
    });

    // Handle any existing JavaScript that might add animations
    const elementsWithAnimations = document.querySelectorAll('[class*="fade"], [class*="slide"], [class*="bounce"]');
    elementsWithAnimations.forEach(element => {
        element.style.animation = 'none';
        element.style.transition = 'none';
    });
});