// ============================================
// Product Detail Page - Interactive Features
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    // Smooth scroll for breadcrumb and internal links
    const internalLinks = document.querySelectorAll('a[href^="#"]');

    internalLinks.forEach(link => {
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
                } else {
                    window.location.href = href;
                }
            }
        });
    });

    // Image zoom on click
    const featuredImage = document.querySelector('.product-featured-image img');

    if (featuredImage) {
        featuredImage.style.cursor = 'pointer';

        featuredImage.addEventListener('click', function() {
            // Create modal for zoomed image
            const modal = document.createElement('div');
            modal.className = 'image-zoom-modal';
            modal.innerHTML = `
                <div class="zoom-modal-content">
                    <span class="zoom-close">&times;</span>
                    <img src="${this.src}" alt="${this.alt}">
                </div>
            `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Close modal on click
            modal.addEventListener('click', function(e) {
                if (e.target === modal || e.target.classList.contains('zoom-close')) {
                    modal.classList.add('fade-out');
                    setTimeout(() => {
                        document.body.removeChild(modal);
                        document.body.style.overflow = '';
                    }, 300);
                }
            });

            // Close on ESC key
            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    modal.click();
                    document.removeEventListener('keydown', escHandler);
                }
            });
        });
    }

    // Add copy to clipboard for terms and conditions
    const termsContent = document.querySelector('.product-terms');

    if (termsContent) {
        const copyButton = document.createElement('button');
        copyButton.className = 'btn btn-sm btn-outline-primary copy-terms-btn';
        copyButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
            </svg>
            Salin S&K
        `;

        const subtitle = termsContent.querySelector('.section-subtitle');
        if (subtitle) {
            subtitle.style.position = 'relative';
            subtitle.style.display = 'flex';
            subtitle.style.justifyContent = 'space-between';
            subtitle.style.alignItems = 'center';
            subtitle.appendChild(copyButton);

            copyButton.addEventListener('click', function() {
                const termsText = termsContent.querySelector('.terms-content').innerText;

                navigator.clipboard.writeText(termsText).then(() => {
                    const originalText = copyButton.innerHTML;
                    copyButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                        Tersalin!
                    `;
                    copyButton.classList.remove('btn-outline-primary');
                    copyButton.classList.add('btn-success');

                    setTimeout(() => {
                        copyButton.innerHTML = originalText;
                        copyButton.classList.remove('btn-success');
                        copyButton.classList.add('btn-outline-primary');
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                });
            });
        }
    }

    // Reading progress bar
    const progressBar = document.createElement('div');
    progressBar.className = 'reading-progress';
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', function() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
        progressBar.style.width = scrollPercent + '%';
    });

    // Table of contents (if content has headings)
    const contentSection = document.querySelector('.description-content, .terms-content');

    if (contentSection) {
        const headings = contentSection.querySelectorAll('h2, h3');

        if (headings.length > 2) {
            const toc = document.createElement('div');
            toc.className = 'table-of-contents';
            toc.innerHTML = '<h4>Daftar Isi</h4><ul></ul>';

            const tocList = toc.querySelector('ul');

            headings.forEach((heading, index) => {
                // Add ID to heading if it doesn't have one
                if (!heading.id) {
                    heading.id = 'heading-' + index;
                }

                const li = document.createElement('li');
                li.className = heading.tagName.toLowerCase();
                li.innerHTML = `<a href="#${heading.id}">${heading.textContent}</a>`;
                tocList.appendChild(li);
            });

            // Insert TOC before content
            contentSection.parentElement.insertBefore(toc, contentSection);
        }
    }

    const contentImages = document.querySelectorAll('.description-content img, .terms-content img');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;

                    // Only apply lazy loading if image hasn't loaded yet
                    if (!img.complete) {
                        img.style.opacity = '0';
                        img.style.transition = 'opacity 0.5s ease';

                        img.addEventListener('load', function() {
                            img.style.opacity = '1';
                        });
                    } else {
                        // Image already loaded, just show it
                        img.style.opacity = '1';
                    }

                    imageObserver.unobserve(img);
                }
            });
        });

        contentImages.forEach(img => imageObserver.observe(img));
    }

    // WhatsApp button tracking
    const whatsappButtons = document.querySelectorAll('.btn-whatsapp');

    whatsappButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productTitle = document.querySelector('.product-detail-title')?.textContent;
            console.log('WhatsApp clicked for product:', productTitle);

            // Add analytics tracking here
            // gtag('event', 'whatsapp_click', {
            //     'product_name': productTitle
            // });
        });
    });

    // Scroll to top button
    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.className = 'scroll-to-top';
    scrollTopBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
        </svg>
    `;
    scrollTopBtn.title = 'Kembali ke atas';
    document.body.appendChild(scrollTopBtn);

    // Show/hide scroll to top button
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    });

    scrollTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });


});
