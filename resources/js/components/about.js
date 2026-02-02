// ============================================
// About Us Page - Counter Animation
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    // Animated Counter
    const counters = document.querySelectorAll('.stat-number');

    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };

        updateCounter();
    };

    // Intersection Observer for counter animation
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                animateCounter(counter);
                observer.unobserve(counter); // Animate only once
            }
        });
    }, observerOptions);

    // Observe all counters
    counters.forEach(counter => {
        observer.observe(counter);
    });

    // Smooth scroll for CTA buttons
    const ctaButtons = document.querySelectorAll('.about-cta-section a[href^="#"]');

    ctaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            if (href.includes('#')) {
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
                } else {
                    // If target not found, navigate to home page with hash
                    window.location.href = href;
                }
            }
        });
    });
});
