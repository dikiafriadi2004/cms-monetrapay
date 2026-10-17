import './bootstrap';

// Mark JS availability for progressive-enhancement animations.
document.documentElement.classList.add('js-enabled');

// Smooth scroll behavior
document.documentElement.style.scrollBehavior = 'smooth';

document.addEventListener('DOMContentLoaded', () => {

    // ── Scroll animations ──
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    if (animatedElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        animatedElements.forEach((el) => observer.observe(el));
    }

    // ── Image lazy loading with skeleton shimmer ──
    document.querySelectorAll('img[loading="lazy"]').forEach((img) => {
        if (img.closest('.no-skeleton')) return;
        if (img.complete && img.naturalWidth > 0) return;

        img.classList.add('img-skeleton');
        if (!img.style.minHeight && img.getAttribute('height')) {
            img.style.minHeight = img.getAttribute('height') + 'px';
        }
        const onLoad = () => {
            img.classList.remove('img-skeleton');
            img.style.minHeight = '';
        };
        if (img.complete) { onLoad(); }
        else { img.addEventListener('load', onLoad, { once: true }); }
    });

    // ── Sticky header scroll effect ──
    const header = document.querySelector('.header-sticky');
    if (header) {
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    header.classList.toggle('scrolled', window.scrollY > 10);
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // ── Counter animation ──
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length > 0) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-count'), 10);
                const duration = 1500;
                const start = performance.now();
                function update(now) {
                    const eased = 1 - Math.pow(1 - Math.min((now - start) / duration, 1), 3);
                    el.textContent = Math.round(target * eased).toLocaleString('id-ID');
                    if (eased < 1) requestAnimationFrame(update);
                }
                requestAnimationFrame(update);
                counterObserver.unobserve(el);
            });
        }, { threshold: 0.5 });
        counters.forEach((el) => counterObserver.observe(el));
    }

    // ── Smooth scroll for anchor links ──
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', function (e) {
            const id = this.getAttribute('href');
            if (id === '#') return;
            const target = document.querySelector(id);
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });

});
