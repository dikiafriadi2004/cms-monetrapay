/**
 * SEO Character Count Indicators
 * Shows character count below meta_title and meta_description fields
 * with color warnings when approaching or exceeding recommended limits.
 *
 * - meta_title: warn (orange) > 50, danger (red) > 60
 * - meta_description: warn (orange) > 140, danger (red) > 160
 */
(function() {
    'use strict';

    function initSeoCounter(inputId, maxChars, warnAt) {
        const input = document.getElementById(inputId);
        if (!input) return;

        // Create counter element
        const counter = document.createElement('div');
        counter.className = 'seo-char-counter mt-1 text-xs';
        counter.setAttribute('aria-live', 'polite');
        input.parentNode.insertBefore(counter, input.nextSibling);

        function updateCounter() {
            const length = input.value.length;
            counter.textContent = length + ' / ' + maxChars + ' karakter';

            // Reset classes
            counter.classList.remove('text-slate-500', 'text-amber-600', 'text-red-600', 'font-medium');

            if (length > maxChars) {
                counter.classList.add('text-red-600', 'font-medium');
            } else if (length > warnAt) {
                counter.classList.add('text-amber-600', 'font-medium');
            } else {
                counter.classList.add('text-slate-500');
            }
        }

        // Listen for input events
        input.addEventListener('input', updateCounter);
        input.addEventListener('keyup', updateCounter);
        input.addEventListener('paste', function() {
            setTimeout(updateCounter, 0);
        });

        // Initial update
        updateCounter();
    }

    // Initialize when DOM is ready
    function init() {
        initSeoCounter('meta_title', 60, 50);
        initSeoCounter('meta_description', 160, 140);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
