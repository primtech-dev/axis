// ============================================
// Contact Page - Simple Interactions
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            if (href !== '#' && href.includes('#')) {
                e.preventDefault();

                const targetId = href.split('#')[1];
                const targetSection = document.getElementById(targetId);

                if (targetSection) {
                    const navbar = document.getElementById('mainNavbar');
                    const navbarHeight = navbar ? navbar.offsetHeight : 0;
                    const targetPosition = targetSection.offsetTop - navbarHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // WhatsApp click tracking (optional)
    const whatsappLinks = document.querySelectorAll('a[href*="wa.me"]');

    whatsappLinks.forEach(link => {
        link.addEventListener('click', function() {
            console.log('WhatsApp link clicked');
            // You can add analytics tracking here
        });
    });

    // Email click tracking (optional)
    const emailLinks = document.querySelectorAll('a[href^="mailto:"]');

    emailLinks.forEach(link => {
        link.addEventListener('click', function() {
            console.log('Email link clicked');
            // You can add analytics tracking here
        });
    });

    // Phone click tracking (optional)
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');

    phoneLinks.forEach(link => {
        link.addEventListener('click', function() {
            console.log('Phone link clicked');
            // You can add analytics tracking here
        });
    });

    // Add loading animation for map
    const mapIframe = document.querySelector('.map-wrapper iframe');

    if (mapIframe) {
        const mapWrapper = mapIframe.parentElement;

        // Create loading overlay
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'map-loading-overlay';
        loadingOverlay.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;

        // Add CSS for loading overlay
        const style = document.createElement('style');
        style.textContent = `
            .map-loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
                border-radius: 20px;
                transition: opacity 0.3s ease;
            }
            .map-loading-overlay.fade-out {
                opacity: 0;
                pointer-events: none;
            }
        `;
        document.head.appendChild(style);

        // Add loading overlay to map wrapper
        mapWrapper.style.position = 'relative';
        mapWrapper.appendChild(loadingOverlay);

        // Remove overlay when map loads
        mapIframe.addEventListener('load', function() {
            setTimeout(() => {
                loadingOverlay.classList.add('fade-out');
                setTimeout(() => {
                    loadingOverlay.remove();
                }, 300);
            }, 500);
        });
    }
});
