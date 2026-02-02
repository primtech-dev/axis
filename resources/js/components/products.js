// ============================================
// Products Page - Interactive Features
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    // Only run on products index page, not on product detail page
    const isProductsIndexPage = document.querySelector('.products-section');
    const isProductDetailPage = document.querySelector('.product-content-section');

    // Add ripple effect to product cards - works on both pages
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        card.addEventListener('mouseenter', function(e) {
            this.style.setProperty('--mouse-x', e.offsetX + 'px');
            this.style.setProperty('--mouse-y', e.offsetY + 'px');
        });
    });

    // Track product clicks for analytics
    const productLinks = document.querySelectorAll('.product-card a');

    productLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const productCard = this.closest('.product-card');
            const productTitle = productCard.querySelector('.product-title')?.textContent;

            console.log('Product clicked:', productTitle);

            // You can add Google Analytics tracking here
            // gtag('event', 'product_click', {
            //     'product_name': productTitle
            // });
        });
    });

    // Smooth scroll for CTA buttons - ONLY on products index page
    if (isProductsIndexPage && !isProductDetailPage) {
        const ctaButtons = document.querySelectorAll('.products-cta-section a[href^="#"]');

        ctaButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href && href.includes('#')) {
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
    }

    // Add animation class when products enter viewport
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const productObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                productObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all product cards
    productCards.forEach(card => {
        productObserver.observe(card);
    });

    // Filter products by search (if you want to add search feature later)
    const searchInput = document.getElementById('productSearch');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            productCards.forEach(card => {
                const title = card.querySelector('.product-title')?.textContent.toLowerCase();

                if (title && title.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            // Check if any products are visible
            const visibleProducts = Array.from(productCards).filter(card =>
                card.style.display !== 'none'
            );

            // Show/hide no results message
            const noResults = document.querySelector('.no-products');
            if (noResults) {
                if (visibleProducts.length === 0 && searchTerm !== '') {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        });
    }

    // Add keyboard navigation for product cards
    productCards.forEach((card, index) => {
        card.setAttribute('tabindex', '0');

        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const link = this.querySelector('a');
                if (link) {
                    link.click();
                }
            }
        });
    });

    // WhatsApp button click tracking
    const whatsappButtons = document.querySelectorAll('.btn-whatsapp');

    whatsappButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('WhatsApp button clicked from products page');
            // Add analytics tracking here
        });
    });
});
