
// ============================================
// Navbar Component - Manual Toggle (Fixed)
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');
    const navLinks = document.querySelectorAll('.nav-link');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.getElementById('navbarNav');

    // ============================================
    // Manual Toggle Function
    // ============================================
    navbarToggler.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent event bubbling to document click

        const isOpen = navbarCollapse.classList.contains('show');

        if (isOpen) {
            // Close
            navbarCollapse.classList.remove('show');
            this.classList.add('collapsed');
            this.setAttribute('aria-expanded', 'false');
        } else {
            // Open
            navbarCollapse.classList.add('show');
            this.classList.remove('collapsed');
            this.setAttribute('aria-expanded', 'true');
        }
    });

    // Helper function to close menu
    function closeMenu() {
        navbarCollapse.classList.remove('show');
        navbarToggler.classList.add('collapsed');
        navbarToggler.setAttribute('aria-expanded', 'false');
    }

    // ============================================
    // Navbar Background on Scroll
    // ============================================
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });

    // ============================================
    // Close menu when clicking nav links
    // ============================================
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            // Close mobile menu
            closeMenu();

            // Handle anchor links
            if (href && href.includes('#')) {
                const url = new URL(href, window.location.origin);
                const targetId = url.hash.substring(1);

                if (url.pathname === window.location.pathname && targetId) {
                    e.preventDefault();

                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        const navbarHeight = navbar.offsetHeight;
                        const targetPosition = targetSection.offsetTop - navbarHeight;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            }
        });
    });

    // ============================================
    // Handle Hash on Page Load
    // ============================================
    if (window.location.hash) {
        setTimeout(() => {
            const targetId = window.location.hash.substring(1);
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                const navbarHeight = navbar.offsetHeight;
                const targetPosition = targetSection.offsetTop - navbarHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }, 100);
    }

    // ============================================
    // Close menu on outside click
    // ============================================
    document.addEventListener('click', function(event) {
        const isClickInsideNavbar = navbar.contains(event.target);
        const isClickOnToggler = navbarToggler.contains(event.target);
        const isNavbarOpen = navbarCollapse.classList.contains('show');

        // Don't close if clicking the toggler (it has its own handler)
        if (isClickOnToggler) {
            return;
        }

        // Close if clicking outside navbar and menu is open
        if (!isClickInsideNavbar && isNavbarOpen) {
            closeMenu();
        }
    });

    // ============================================
    // Close menu on ESC key
    // ============================================
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });
});
